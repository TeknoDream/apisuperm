<?php
function sWhere($tipo=1,$busc,$_PROYECTO){
	$sWhere='';
		if($_PROYECTO==16&&$busc!='') include "swhere_016.php";
	elseif($_PROYECTO==19&&$busc!='') include "swhere_019.php";
	elseif($_PROYECTO==20&&$busc!='') include "swhere_020.php";
	elseif($_PROYECTO==22&&$busc!='') include "swhere_022.php";
	elseif($_PROYECTO==23&&$busc!='') include "swhere_023.php";
	elseif($_PROYECTO==24&&$busc!='') include "swhere_024.php";
	elseif($_PROYECTO==25&&$busc!='') include "swhere_025.php";
	elseif($_PROYECTO==26&&$busc!='') include "swhere_026.php";
	elseif($_PROYECTO==27&&$busc!='') include "swhere_027.php";
	elseif($_PROYECTO==28&&$busc!='') include "swhere_028.php";
	elseif($_PROYECTO==29&&$busc!='') include "swhere_029.php";
	elseif($_PROYECTO==31&&$busc!='') include "swhere_031.php";
	elseif($_PROYECTO==32&&$busc!='') include "swhere_032.php";
	elseif($_PROYECTO==36&&$busc!='') include "swhere_036.php";
	return $sWhere;
}
function sWhere_cons($tipo=1,$busc){
	if($busc!=''){
		/*ORDEN DE TRABAJO*/
		if($tipo==1){
			$sWhere=" AND (
						m_progmmto.DESC_PROGMMTO LIKE :Buscar OR
						m_progmmto.COMENT_PROGMMTO LIKE :Buscar) ";
		}
		/*TRATAMIENTO RM*/
		elseif($tipo==2){
			$sWhere=" AND (
						x_tratamientos_det.TITLE_TRATADET LIKE :Buscar) ";
		}
		////////////
		//EQUIPOS RM
		elseif($tipo==3){
			$sWhere=" AND (
					m_equipo.ID_EQUIPO LIKE :Buscar OR
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar) ";
		}
		//EMPLEADOS EMPRESA RM
		elseif($tipo==4){
			$sWhere=" AND (
				e_empresa_empleados.NOMB_EMPLEADO LIKE :Buscar OR
				e_empresa_empleados.APEL_EMPLEADO LIKE :Buscar OR
				e_empresa_empleados.CORREO_EMPLEADO LIKE :Buscar OR
				e_empresa_empleados.TEL1_EMPLEADO LIKE :Buscar OR
				e_empresa_empleados.TEL2_EMPLEADO LIKE :Buscar OR
				CONCAT(e_empresa_empleados.NOMB_EMPLEADO,' ',e_empresa_empleados.APEL_EMPLEADO) LIKE :Buscar) ";
		}
		//REPUESTOS RM
		elseif($tipo==6){
			$sWhere=" AND (m_repuestos.COD_REP LIKE :Buscar OR
					m_repuestos.NOMB_REP LIKE :Buscar OR	
					m_repuestos_proveedor.COMENT_PROVEEDOR LIKE :Buscar OR
					e_empresa.NOMBRE_EMPRESA LIKE :Buscar) ";
		}
		//CRESP RM
		elseif($tipo==8){
			$sWhere=" AND (s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar OR	
					s_cresp.COMENT_RESP LIKE :Buscar OR
					s_cresp.DIRECCION LIKE :Buscar OR
					s_cresp.TELEFONO LIKE :Buscar) ";
		}
		//EMPLEADOS RM
		elseif($tipo==9){
			$sWhere=" AND (r_empleados.NOMB_EMPLEADO LIKE :Buscar OR
					r_empleados.APEL_EMPLEADO LIKE :Buscar OR	
					CONCAT(r_empleados.NOMB_EMPLEADO,' ',r_empleados.APEL_EMPLEADO) LIKE :Buscar OR
					r_empleados.DOCUMENTO LIKE :Buscar OR	
					r_empleados.DIREC_EMPLEADO LIKE :Buscar OR						
					r_empleados.CORREO_EMPLEADO LIKE :Buscar OR
					r_empleados.TEL1_EMPLEADO LIKE :Buscar OR
					r_empleados.TEL2_EMPLEADO LIKE :Buscar OR
					m_oficios.NOMB_OFICIO LIKE :Buscar OR
					m_oficios.DESC_OFICIO LIKE :Buscar) ";
		}
		//OFICIOS
		elseif($tipo==10){
			$sWhere=" AND (
					m_oficios.NOMB_OFICIO LIKE :Buscar OR
					m_oficios.DESC_OFICIO LIKE :Buscar) ";
		}
		elseif($tipo==11){
			
			$sWhere=" AND (adm_usuarios.ALIAS LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
					adm_usuarios_datos.DOCUMENTO LIKE :Buscar OR
					adm_usuarios_datos.TELEFONO_U LIKE :Buscar OR
					adm_usuarios_datos.DIRECCION_U LIKE :Buscar OR 
					adm_usuarios_datos.CORREO_U LIKE :Buscar) ";

		}
		////
		/*ACTIVIDAD DE MMTO*/
		elseif($tipo==13){
			$sWhere=" AND (m_progmmto.DESC_PROGMMTO LIKE :Buscar OR
						m_progmmto.COMENT_PROGMMTO LIKE :Buscar)";
	
		}
		elseif($tipo==14){
			$sWhere=" AND (b_items.COD_ITEM LIKE :Buscar OR
					b_items.NOMB_ITEM LIKE :Buscar OR	
					b_items.REF_ITEM LIKE :Buscar OR
					m_gtipoitem.GTIPOITEM LIKE :Buscar OR 
					m_tipoitem.NOMB_TITEM LIKE :Buscar
					) ";
		}
		
		elseif($tipo==15){ // SUBSIDIOS CRM
			$sWhere=" AND (			
					b_entidades_sub.NOMB_ENTIDAD LIKE :Buscar OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR	
					DATE_FORMAT(m_separacion.FECHA_PAGOPRIM,'%d/%m/%Y') LIKE :Buscar) ";
			
		}
		//EMPRESAS PROVEEDORES MERCAYA Y RM
		elseif($tipo==16){
			$sWhere=" AND (e_empresa.ALIAS_EMPRESA LIKE :Buscar OR
					e_empresa.NOMBRE_EMPRESA LIKE :Buscar OR	
					e_empresa.BIO_EMPRESA LIKE :Buscar OR
					e_empresa.NIT_EMPRESA LIKE :Buscar) ";
		}
		elseif($tipo==17){
			$sWhere=" AND (e_empresa_sucursal.NOMBRE_SUCURSAL LIKE :Buscar OR
					e_empresa_sucursal.DIREC_SUCURSAL LIKE :Buscar OR	
					e_empresa.NOMBRE_EMPRESA LIKE :Buscar OR
					e_empresa.BIO_EMPRESA LIKE :Buscar OR
					e_empresa.NIT_EMPRESA LIKE :Buscar) ";
		}
		elseif($tipo==18){
			$sWhere.=" AND (m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
						m_ubicaciones.DESC_UBIC LIKE :Buscar)";		
		}
		elseif($tipo==19){
			
		}
		elseif($tipo==20){
			$sWhere=" AND (fac_ciudades.NOMB_CIUDAD LIKE :Buscar OR
					fac_ciudades.DISTRITO_CIUDAD LIKE :Buscar OR	
					fac_pais.NOMB_PAIS LIKE :Buscar) ";
		}
		elseif($tipo==21){
			$sWhere=" AND (m_tags.TAG LIKE :Buscar) ";
		}
		elseif($tipo==22){
			$sWhere=" AND (m_grupoitem.NOMB_GITEM LIKE :Buscar) ";
		}
		elseif($tipo==23){
			
		}
		elseif($tipo==24){
			$sWhere=" AND (u_inmuebles.TITULO_INMUEBLE LIKE :Buscar OR
					u_inmuebles.DIRECCION_INMUEBLE LIKE :Buscar OR
					u_inmobiliarias.NOMB_INMOBILIARIA LIKE :Buscar OR
					u_inmuebles.COD_INMUEBLE LIKE :Buscar OR 
					s_barrios.NOMB_BARRIO LIKE :Buscar) ";
		}
		elseif($tipo==25){
			$sWhere=" AND (adm_usuarios.ALIAS LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR					
					u_inmuebles_visitas.FECHA_VISITA LIKE :Buscar) ";
		}
		elseif($tipo==26){ //INMUEBLE CRM
			$sWhere=" AND (
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos_segmento.DESC_SEGMENTO LIKE :Buscar OR
					m_proyectos.DESC_PROYECTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR
					m_proyectos.DIRECCION_PROY LIKE :Buscar	OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar ) ";
		}
		elseif($tipo==27){ //SEGMENTOS CRP
			$sWhere=" AND (
					m_proyectos_segmento.DESC_SEGMENTO LIKE :Buscar OR
					m_proyectos_segmento.DESC_SEGMENTO LIKE :Buscar OR
					m_proyectos.DESC_PROYECTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR
					m_proyectos.DIRECCION_PROY LIKE :Buscar) ";
		}
		elseif($tipo==28){ //PEPELES EN CRM	
			$sWhere=" AND (m_papeles.PAPEL LIKE :Buscar OR
					m_papeles.DESC_PAPEL LIKE :Buscar) ";
		}
		elseif($tipo==29){ //PROYECTOS
			$sWhere=" AND (m_proyectos.NOMB_PROYECTO LIKE :Buscar OR
					m_proyectos.DESC_PROYECTO LIKE :Buscar OR
					m_proyectos.LIC_PROYECTO LIKE :Buscar) ";
		}
		elseif($tipo==30){ //INMUEBLE URBANIA
			$sWhere=" AND (u_inmuebles.DIRECCION_INMUEBLE LIKE :Buscar OR
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					s_barrios.NOMB_BARRIO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar) ";
		}
		elseif($tipo==31){ //CLIENTES CRM
			$sWhere=" AND (u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar) ";
		}
		elseif($tipo==32){ //ENTIDADES CRM
			$sWhere=" AND (b_entidades.NOMB_ENTIDAD LIKE :Buscar OR
					b_entidades.DESC_ENTIDAD LIKE :Buscar) ";
		}
		elseif($tipo==33){//SOLO PAGOS RECIBIDOS DE ACUERDO DE PAGOS
			$sWhere=" AND (			
					b_entidades.NOMB_ENTIDAD LIKE :Buscar OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR				
					DATE_FORMAT(m_separacion_pagos_recibido.FECHAR_PAGO,'%d/%m/%Y') LIKE :Buscar OR
					DATE_FORMAT(m_separacion_pagos.FECHAP_PAGO,'%d/%m/%Y') LIKE :Buscar) ";					
		}
		elseif($tipo==34){ //CLIENTE APTO PCG
			$sWhere=" AND (u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar) ";
		}
		elseif($tipo==35){ /*MERCARAPIDO*/
			$sWhere=" WHERE (
						m_shopping_cart_almacen.ID_SCART LIKE :Buscar OR
						b_items.COD_ITEM LIKE :Buscar OR
						b_items.NOMB_ITEM LIKE :Buscar OR
						m_gtipoitem.GTIPOITEM LIKE :Buscar OR 
						m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
						CONCAT(adm_usuarios_sol.NOMBRE_U,' ',adm_usuarios_sol.APELLIDO_U) LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==36){
			$sWhere=" WHERE (
						b_items_manual.ID_MANUAL LIKE :Buscar OR
						b_items.COD_ITEM LIKE :Buscar OR
						b_items.NOMB_ITEM LIKE :Buscar OR
						m_gtipoitem.GTIPOITEM LIKE :Buscar OR 
						m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 
						e_empresa_c.NOMBRE_EMPRESA LIKE :Buscar OR
						e_empresa_sucursal.NOMBRE_SUCURSAL LIKE :Buscar)";
		}
		elseif($tipo==37){
			$sWhere=" WHERE (
						b_items_movimiento.ID_MOVIMIENTO LIKE :Buscar OR
						b_items.COD_ITEM LIKE :Buscar OR
						b_items.NOMB_ITEM LIKE :Buscar OR
						m_gtipoitem.GTIPOITEM LIKE :Buscar OR 
						m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
						m_ubicaciones_d.NOMB_UBIC LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==38){
			$sWhere=" WHERE (
						b_items_precio.ID_PRECIO LIKE :Buscar OR
						b_items.COD_ITEM LIKE :Buscar OR
						b_items.NOMB_ITEM LIKE :Buscar OR
						m_gtipoitem.GTIPOITEM LIKE :Buscar OR 
						m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==39){
			$sWhere=" WHERE (
						b_items_proveedores.ID_ITEMPROV LIKE :Buscar OR
						b_items.COD_ITEM LIKE :Buscar OR
						b_items.NOMB_ITEM LIKE :Buscar OR
						m_gtipoitem.GTIPOITEM LIKE :Buscar OR 
						m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR
						e_empresa.NOMBRE_EMPRESA LIKE :Buscar OR
						e_empresa_sucursal.NOMBRE_SUCURSAL LIKE :Buscar) ";
		}
		elseif($tipo==40){
			$sWhere=" AND (b_promocion.DESC_PROMO LIKE :Buscar OR
						m_tipoitem.NOMB_TITEM LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==41){
			$sWhere=" AND (b_prom_tempo.DESC_PTEMPO LIKE :Buscar OR
						m_grupoitem.NOMB_GITEM LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==42){
			$sWhere=" AND (m_zonas.NOMB_ZONA LIKE :Buscar OR
						m_zonas.DESC_ZONA LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==43){
			$sWhere=" AND (adm_usuarios_datos.DOCUMENTO LIKE :Buscar OR
						adm_usuarios_datos.TELEFONO_U LIKE :Buscar OR
						adm_usuarios_datos.DIRECCION_U LIKE :Buscar OR
						adm_usuarios_sol.CORREO_U LIKE :Buscar OR
						CONCAT(adm_usuarios_sol.NOMBRE_U,' ',adm_usuarios_sol.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==44){
			$sWhere=" AND (m_tipoitem.NOMB_TITEM LIKE :Buscar OR
						m_tipoitem.DESC_TITEM LIKE :Buscar OR
						m_gtipoitem.GTIPOITEM LIKE :Buscar) ";
		}
		elseif($tipo==45){ //MODELOS PCG
			$sWhere=" AND (u_modelo.NOMB_MODELO LIKE :Buscar OR
						m_tinmueble.TINMUEBLE LIKE :Buscar OR
						m_proyectos.NOMB_PROYECTO LIKE :Buscar) ";
		}
		elseif($tipo==46){ //OTROS PAGOS CRM - RECIBIDOS
			$sWhere=" AND (			
					b_entidades_valor.NOMB_ENTIDAD LIKE :Buscar OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR				
					DATE_FORMAT(m_separacion_valores_recibido.FECHAR_VALOR,'%d/%m/%Y') LIKE :Buscar OR
					DATE_FORMAT(m_separacion_valores.FECHAE_SEPARACION,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==47){ //SUBSIDIOS PAGADOS	
			$sWhere=" AND (			
					b_entidades.NOMB_ENTIDAD LIKE :Buscar OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR				
					DATE_FORMAT(m_separacion.FECHA_PAGOPRIM,'%d/%m/%Y') LIKE :Buscar OR
					DATE_FORMAT(m_separacion_subsidio_recibido.FECHAR_PAGO,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==48){ //ENTREGA DE PAPELES
			$sWhere=" AND (
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR

					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR										


					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 
				
					m_papeles.PAPEL LIKE :Buscar OR
					DATE_FORMAT(IFNULL(m_separacion_papeles_recibido.FECHA_PAPEL,m_separacion.FECHA_SEPARACION),'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==49){ //HISTORIAL DE PRECIOS MODELOS CRM
			$sWhere=" AND (u_modelo.NOMB_MODELO LIKE :Buscar OR
						m_tinmueble.TINMUEBLE LIKE :Buscar OR
						u_modelo_precios.PISO LIKE :Buscar OR
						DATE_FORMAT(u_modelo_precios.FECHA_PRECIO,'%d/%m/%Y')  LIKE :Buscar OR
						m_proyectos_segmento.DESC_SEGMENTO  LIKE :Buscar OR
						m_proyectos.NOMB_PROYECTO LIKE :Buscar OR
						adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
						adm_usuarios_op.APELLIDO_U LIKE :Buscar OR
						CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar) ";
		}
		elseif($tipo==50){ //PAGOS DE SEPARACION - PAGOS
			$sWhere=" AND (			
					b_entidades.NOMB_ENTIDAD LIKE :Buscar OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR				
					DATE_FORMAT(m_separacion.FECHA_PAGOPRIM,'%d/%m/%Y') LIKE :Buscar OR
					DATE_FORMAT(m_separacion_inicial.FECHAR_PAGO,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==51){ //NOTAS INMUEBLES CRM
			$sWhere=" AND (
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR

					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR										


					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 	

					t_notas.TITULO LIKE :Buscar OR
					t_notas.NOTA LIKE :Buscar OR
					DATE_FORMAT(t_notas.FECHA_NOTA,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==52){ //DESCUENTO MERCAYA
			$sWhere=" AND (m_cupones.NOMBRE_CUPON LIKE :Buscar OR
					m_cupones.DESCRIP_CUPON LIKE :Buscar) ";
		}
		elseif($tipo==53){ //DESCUENTO MERCAYA
			$sWhere=" AND (m_cupones.NOMBRE_CUPON LIKE :Buscar OR
					m_cupones.DESCRIP_CUPON LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
					m_cupones_asignado.CORREO_U LIKE :Buscar) ";
		}
		elseif($tipo==54){			
			$sWhere=" AND (adm_usuarios.ALIAS LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
					adm_usuarios_datos.DOCUMENTO LIKE :Buscar OR
					adm_usuarios_datos.TELEFONO_U LIKE :Buscar OR
					adm_usuarios_datos.DIRECCION_U LIKE :Buscar OR 
					adm_usuarios.CORREO_U LIKE :Buscar OR 
					adm_grupos.DESC_GRUPO LIKE :Buscar) ";
		}
		elseif($tipo==55){			
			$sWhere=" AND (m_tcliente.TCLIENTE LIKE :Buscar OR
					m_tcliente.DESC_TCLIENTE LIKE :Buscar )";
		}
		elseif($tipo==56){ //BUSCAR CLIENTES
			 
			$sWhere=" AND (
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR				
					
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 

					adm_usuarios_as.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_as.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios_as.NOMBRE_U,' ',adm_usuarios_as.APELLIDO_U) LIKE :Buscar OR 

					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR
					DATE_FORMAT(m_separacion.FECHA_SEPARACION,'%d/%m/%Y') LIKE :Buscar OR
					
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.DESC_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR
					
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar
			)";
		}
		elseif($tipo==57){			
			$sWhere=" AND (fac_equipodef_sub.DESC_EQ LIKE :Buscar OR
					fac_equipodef.DESC_EQ LIKE :Buscar )";
		}
		elseif($tipo==58){			
			$sWhere=" AND (m_caracteristicas.NOMB_CARACTER LIKE :Buscar)";
		}
		//ORDEN DE TRABAJO
		elseif($tipo==59){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR							
					m_ot.ID_OT LIKE :Buscar  OR					
					m_ot.DESC_OT LIKE :Buscar  OR

					t_emp_propio.NOMB_EMPLEADO LIKE :Buscar OR
					t_emp_propio.APEL_EMPLEADO LIKE :Buscar OR
					CONCAT(t_emp_propio.NOMB_EMPLEADO,' ',t_emp_propio.APEL_EMPLEADO) LIKE :Buscar OR

					t_emp_proveedor.NOMB_EMPLEADO LIKE :Buscar OR
					t_emp_proveedor.APEL_EMPLEADO LIKE :Buscar OR
					CONCAT(t_emp_proveedor.NOMB_EMPLEADO,' ',t_emp_proveedor.APEL_EMPLEADO) LIKE :Buscar OR

					t_usuarios.NOMBRE_U LIKE :Buscar OR
					t_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(t_usuarios.NOMBRE_U,' ',t_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//Actualización de Contador
		elseif($tipo==60){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar OR
					m_equipo.COMENTARIO_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					m_modelos.DESC_MODELO LIKE :Buscar OR
					e_PROVEEDOR.NOMBRE_EMPRESA LIKE :Buscar OR
					e_FABRICANTE.NOMBRE_EMPRESA LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar  OR									
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//CAMBIO DE UBICACION
		elseif($tipo==61){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar OR
					m_equipo.COMENTARIO_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					m_modelos.DESC_MODELO LIKE :Buscar OR
					e_PROVEEDOR.NOMBRE_EMPRESA LIKE :Buscar OR
					e_FABRICANTE.NOMBRE_EMPRESA LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar  OR								
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
					m_ubicaciones_old.NOMB_UBIC LIKE :Buscar)";
		}
		//CONSUMO
		elseif($tipo==62){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar OR
					m_equipo.COMENTARIO_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					m_modelos.DESC_MODELO LIKE :Buscar OR
					e_PROVEEDOR.NOMBRE_EMPRESA LIKE :Buscar OR
					e_FABRICANTE.NOMBRE_EMPRESA LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar  OR								
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR					
					m_equipo_consumo.OSB_CONSUMO LIKE :Buscar OR  
					m_repuestos.NOMB_REP LIKE :Buscar OR 
					c_proveedor_consumo.NOMBRE_EMPRESA LIKE :Buscar)";
		}
		
		//Cambios de Estado
		elseif($tipo==63){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar OR
					m_equipo.COMENTARIO_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					m_modelos.DESC_MODELO LIKE :Buscar OR
					e_PROVEEDOR.NOMBRE_EMPRESA LIKE :Buscar OR
					e_FABRICANTE.NOMBRE_EMPRESA LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar  OR						
					m_ot.ID_OT LIKE :Buscar  OR					
					m_ot.DESC_OT LIKE :Buscar  OR									
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
					fac_estados_eq.ESTADO LIKE :Buscar)";
		}
		//PROGMMTO GASTO
		elseif($tipo==64){
			$sWhere=" AND (m_progmmto_presu.OTROS_OTPROGGASTO LIKE :Buscar)";
		}
		//PROGMMTO MANO DE OBRA
		elseif($tipo==65){
			$sWhere=" AND (
						m_oficios.NOMB_OFICIO LIKE :Buscar OR
						m_oficios.DESC_OFICIO LIKE :Buscar OR
						CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
						m_progmmto_presu_empleado.VALOR_OTPROGOFICIO LIKE :Buscar) ";
	
		}
		//PROGMMTO REPUESTO O REPUESTO
		elseif($tipo==66){			
			$sWhere=" AND (m_repuestos.NOMB_REP LIKE :Buscar)";
		}
		//PROGRAMA
		elseif($tipo==67){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar OR
					m_equipo.COMENTARIO_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					m_modelos.DESC_MODELO LIKE :Buscar OR					
					s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar  OR								
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR					
					m_progmmto.DESC_PROGMMTO LIKE :Buscar OR
					m_progmmto.COMENT_PROGMMTO LIKE :Buscar)";
		}
		//ESTADO OT
		elseif($tipo==68){			
			$sWhere=" AND (
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_equipo.REF_EQ LIKE :Buscar OR
					m_equipo.SERIAL_EQ LIKE :Buscar OR
					m_equipo.COMENTARIO_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_ubicaciones.NOMB_UBIC LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					m_modelos.DESC_MODELO LIKE :Buscar OR
					e_PROVEEDOR.NOMBRE_EMPRESA LIKE :Buscar OR
					e_FABRICANTE.NOMBRE_EMPRESA LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR
					s_cresp.ABR_RESP LIKE :Buscar  OR						
					m_ot.ID_OT LIKE :Buscar  OR					
					m_ot.DESC_OT LIKE :Buscar  OR
					

					t_emp_propio.NOMB_EMPLEADO LIKE :Buscar OR
					t_emp_propio.APEL_EMPLEADO LIKE :Buscar OR
					CONCAT(t_emp_propio.NOMB_EMPLEADO,' ',t_emp_propio.APEL_EMPLEADO) LIKE :Buscar OR

					t_emp_proveedor.NOMB_EMPLEADO LIKE :Buscar OR
					t_emp_proveedor.APEL_EMPLEADO LIKE :Buscar OR
					CONCAT(t_emp_proveedor.NOMB_EMPLEADO,' ',t_emp_proveedor.APEL_EMPLEADO) LIKE :Buscar OR

					t_usuarios.NOMBRE_U LIKE :Buscar OR
					t_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(t_usuarios.NOMBRE_U,' ',t_usuarios.APELLIDO_U) LIKE :Buscar OR


					fac_tmmto_desc.DESC_TMMTO LIKE :Buscar  OR
					m_tfalla.NOMB_FALLA LIKE :Buscar  OR
					m_ttrabajo.DESC_TTRABAJO LIKE :Buscar  OR					
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
					m_ot_seguimiento.DESC_OTSEG LIKE :Buscar OR
					fac_estados_ot.ESTADO LIKE :Buscar)";
		}
		//OT GASTO
		elseif($tipo==69){
			$sWhere=" AND (m_ot_gastos.OTROS_OTGASTO LIKE :Buscar OR
							CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
	
		}
		//OT MANO DE OBRA
		elseif($tipo==70){
			$sWhere=" AND (
						t_emp_propio.NOMB_EMPLEADO LIKE :Buscar OR
						t_emp_propio.APEL_EMPLEADO LIKE :Buscar OR
						CONCAT(t_emp_propio.NOMB_EMPLEADO,' ',t_emp_propio.APEL_EMPLEADO) LIKE :Buscar OR

						t_emp_proveedor.NOMB_EMPLEADO LIKE :Buscar OR
						t_emp_proveedor.APEL_EMPLEADO LIKE :Buscar OR
						CONCAT(t_emp_proveedor.NOMB_EMPLEADO,' ',t_emp_proveedor.APEL_EMPLEADO) LIKE :Buscar OR

						adm_usuarios.NOMBRE_U LIKE :Buscar OR
						adm_usuarios.APELLIDO_U LIKE :Buscar OR
						CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar) ";
	
		}
		//OT REPUESTO O INGRESO(COMPRA)
		elseif($tipo==71){			
			$sWhere=" AND (m_repuestos.NOMB_REP LIKE :Buscar OR 
						e_empresa.NOMBRE_EMPRESA LIKE :Buscar OR
						CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//AGRUPACION
		elseif($tipo==72){			
			$sWhere=" AND (m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR 
						m_agrupaciones.DESC_AGRUPACION LIKE :Buscar)";
		}
		//MODELOS
		elseif($tipo==73){	
			$sWhere=" AND (m_modelos.MODELO LIKE :Buscar OR
                    m_modelos.DESC_MODELO LIKE :Buscar)";
		}
		//GRUPOS
		elseif($tipo==74){	
			$sWhere=" AND (adm_grupos.DESC_GRUPO LIKE :Buscar OR adm_grupos.COMEN_GRUPO LIKE :Buscar)";
		}
		//VENTANAS RM
		elseif($tipo==75){	
			$sWhere=" AND (adm_ventanas_names.VENTANA_NOMBRE LIKE :Buscar OR 
						adm_ventanas_grupo_name.DESC_GVENTANA LIKE :Buscar)";
		}
		elseif($tipo==76){	
			$sWhere=" AND (adm_ventanas.VENTANA_NOMBRE LIKE :Buscar OR adm_ventanas_grupo.DESC_GVENTANA LIKE :Buscar)";
		}
		//CIUDAD BONITA
		elseif($tipo==77){
			$sWhere=" AND (fac_tag.NOMB_TAG LIKE :Buscar OR
							fac_tag.DESC_TAG LIKE :Buscar) ";
		}
		//ITEMS CIUDAD BONITA
		elseif($tipo==78){
			$sWhere=" AND (b_item_desc.NOMB_ITEM LIKE :Buscar OR
							b_item_desc.DESC_ITEM LIKE :Buscar) ";
		}
		//CARACTERISTICAS CB
		elseif($tipo==79){
			$sWhere=" AND (fac_caracteristica.NOMB_CAR LIKE :Buscar OR
							fac_caracteristica.DESC_CAR LIKE :Buscar) ";
		}
		//PROMOS CB
		elseif($tipo==80){
			$sWhere=" AND (
							adm_usuarios.NOMBRE_U LIKE :Buscar OR
							adm_usuarios.APELLIDO_U LIKE :Buscar OR
							b_promocion_desc.DESC_PROMO LIKE :Buscar
							CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar) ";
		}
		//PRECIOS CB
		elseif($tipo==81){
			$sWhere=" AND (b_item_desc.NOMB_ITEM LIKE :Buscar OR
							b_item_desc.DESC_ITEM LIKE :Buscar OR
							adm_usuarios.NOMBRE_U LIKE :Buscar OR
							adm_usuarios.APELLIDO_U LIKE :Buscar OR
							CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar) ";
		}
		//COMPRAS CB
		elseif($tipo==82){
			$sWhere=" AND (
							b_shopping.ID_SCART LIKE :Buscar OR
							b_shopping.IPUS_SCART LIKE :Buscar OR
							adm_usuarios_est.NOMBRE_U LIKE :Buscar OR
							adm_usuarios_est.APELLIDO_U LIKE :Buscar OR
							CONCAT(adm_usuarios_est.NOMBRE_U,' ',adm_usuarios_est.APELLIDO_U) LIKE :Buscar OR
							
							adm_usuarios.NOMBRE_U LIKE :Buscar OR
							adm_usuarios.APELLIDO_U LIKE :Buscar OR
							CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR
							b_shopping_user.MENSAJE_U LIKE :Buscar
							) ";
		}
		// PREPROG PRES MANO DE OBRA
		elseif($tipo==83){
			$sWhere=" AND (adm_oficios_desc.NOMB_OFICIO LIKE :Buscar OR
							adm_oficios_desc.DESC_OFICIO LIKE :Buscar) ";
		
		}
		//TRATAMIENTO ROCKETMP
		elseif($tipo==84){
			$sWhere=" AND (x_tratamientos.NOMBRE_TRATA LIKE :Buscar OR
							x_tratamientos.DESC_TRATA LIKE :Buscar) ";
		}
		//REPUESTO ROCKETMP
		elseif($tipo==85){
			$sWhere=" AND (adm_repuestos.COD_REP LIKE :Buscar OR
							adm_repuestos_desc.NOMB_REP LIKE :Buscar OR
							adm_repuestos_desc.DESC_REP LIKE :Buscar OR
							adm_empresas.NOMB_MEMPRESA LIKE :Buscar) ";
		}
		//OFICIOS
		elseif($tipo==86){
			$sWhere=" AND (adm_oficios_desc.NOMB_OFICIO LIKE :Buscar OR
							adm_oficios_desc.DESC_OFICIO LIKE :Buscar) ";
			
		}
		//APLICAR TRATAMINENTO ROCKETMP
		elseif($tipo==87){
			$sWhere=" AND (m_equipo.ID_EQUIPO LIKE :Buscar OR
							m_equipo.COD_EQ LIKE :Buscar OR
							m_equipo.DESC_EQ LIKE :Buscar OR
							m_equipo.REF_EQ LIKE :Buscar OR
							m_equipo.SERIAL_EQ LIKE :Buscar						

							x_tratamientos.NOMBRE_TRATA LIKE :Buscar OR
							x_tratamientos.DESC_TRATA LIKE :Buscar
							x_tratamientos_det.TITLE_TRATADET LIKE :Buscar) ";
		}
		//EQUIPOS PRE ROCKETMP
		elseif($tipo==88){
			$sWhere=" AND (adm_equipo.REF_EQ LIKE :Buscar OR
							adm_equipo_desc.DESC_EQ LIKE :Buscar OR
							adm_equipo_desc.COMENTARIO_EQ LIKE :Buscar OR
							adm_empresas.NOMB_MEMPRESA LIKE :Buscar) ";
		}
		//PROGRAMAS PRE ROCKETMP
		elseif($tipo==89){
			$sWhere=" AND (adm_progmmto_desc.DESC_PROGMMTO LIKE :Buscar OR
							adm_progmmto_desc.COMENT_PROGMMTO LIKE :Buscar OR
							adm_empresas.NOMB_MEMPRESA LIKE :Buscar) ";
		}
		//PREPROG PRESGRAL
		elseif($tipo==90){
			$sWhere=" AND (adm_progmmto_presu.OTROS_PROGMMTOGASTO :Buscar) ";
		}
		//PREOFICIO SALARIO
		elseif($tipo==91){
			$sWhere=" AND (
					adm_oficios_desc.NOMB_OFICIO LIKE :Buscar OR
					adm_oficios_desc.DESC_OFICIO LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar) ";
		}
		//OFICIO SALARIO
		elseif($tipo==92){
			$sWhere=" AND (
					m_oficios.NOMB_OFICIO LIKE :Buscar OR
					m_oficios.DESC_OFICIO LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//FALLO
		elseif($tipo==93){
			$sWhere=" AND (
					adm_fallos_desc.NOMB_FALLO LIKE :Buscar OR
					adm_fallos_desc.DESC_FALLO LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//HEAD PLANILLAS
		elseif($tipo==94){
			$sWhere=" AND (
					DATE_FORMAT(CONVERT_TZ(m_planillas.FECHA_PLANILLA,'+00:00','$_TZ'),'%d/%m/%Y')  LIKE :Buscar OR
					m_planillas.ID_PLANILLA LIKE :Buscar OR
					m_ubicaciones.NOMB_UBICLIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//DETALLE PLANILLAS
		elseif($tipo==95){
			$sWhere=" AND (
					adm_fallos_desc.NOMB_FALLO LIKE :Buscar OR
					adm_fallos_desc.DESC_FALLO LIKE :Buscar OR

					m_equipo.COD_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR

					DATE_FORMAT(CONVERT_TZ(m_planillas.FECHA_PLANILLA,'+00:00','$_TZ'),'%d/%m/%Y')  LIKE :Buscar OR
					m_planillas.ID_PLANILLA LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//SOLICITUT ROCKETMP
		elseif($tipo==96){
			$sWhere=" AND (
					m_solicitud.DESC_SOLICITUD LIKE :Buscar OR
					m_solicitud.OBS_SOLICITUD LIKE :Buscar OR

					m_ot.ID_OT LIKE :Buscar OR
					m_ot.DESC_OT LIKE :Buscar OR

					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR					

					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR

					adm_usuarios_send.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_send.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios_send.NOMBRE_U,' ',adm_usuarios_send.APELLIDO_U) LIKE :Buscar)";
		}
		//ENVIOS
		elseif($tipo==97){
			$sWhere=" AND (
					m_solicitud.DESC_SOLICITUD LIKE :Buscar OR
					m_solicitud.OBS_SOLICITUD LIKE :Buscar OR

					m_ot.ID_OT LIKE :Buscar OR
					m_ot.DESC_OT LIKE :Buscar OR

					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR

					t_emp_propio.NOMB_EMPLEADO LIKE :Buscar OR
					t_emp_propio.APEL_EMPLEADO LIKE :Buscar OR
					CONCAT(t_emp_propio.NOMB_EMPLEADO,' ',t_emp_propio.APEL_EMPLEADO)  LIKE :Buscar OR

					t_usuarios.NOMBRE_U LIKE :Buscar OR
					t_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(t_usuarios.NOMBRE_U,' ',t_usuarios.APELLIDO_U) LIKE :Buscar OR

					t_emp_proveedor.NOMB_EMPLEADO LIKE :Buscar OR
					t_emp_proveedor.APEL_EMPLEADO LIKE :Buscar OR
					CONCAT(t_emp_proveedor.NOMB_EMPLEADO,' ',t_emp_proveedor.APEL_EMPLEADO) LIKE :Buscar OR

					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR

					adm_usuarios_send.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_send.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios_send.NOMBRE_U,' ',adm_usuarios_send.APELLIDO_U) LIKE :Buscar)";
		}
		//TRATMIENTO APLICADO ROCKETMP
		elseif($tipo==98){
			$sWhere=" AND (
					adm_tratamientos_desc.NOMBRE_TRATA LIKE :Buscar OR
					adm_tratamientos_desc.DESC_TRATA LIKE :Buscar OR

					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_agrupaciones.NOMB_AGRUPACION LIKE :Buscar OR
					m_modelos.MODELO LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR					

					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//AUDITORIAS
		elseif($tipo==99){
			$sWhere=" AND (
					m_ot_audit.NOTA_OTAUDIT LIKE :Buscar OR
					DATE_FORMAT(CONVERT_TZ(m_ot_audit.FECHA_OTAUDIT,'+00:00','$_TZ'),'%d/%m/%Y') LIKE :Buscar OR

					fac_auditoria.DESC_AUDIT LIKE :Buscar OR			

					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//CLASE EMPRESAS
		elseif($tipo==100){
			$sWhere=" AND (
					adm_empresas_btipo_desc.NOMB_GRUPOPAL LIKE :Buscar OR
					adm_empresas_btipo_desc.DESC_GRUPOPAL LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//TIPO EMPRESAS
		elseif($tipo==101){
			$sWhere=" AND (
					adm_empresas_btipo_desc.NOMB_GRUPOPAL LIKE :Buscar OR
					adm_empresas_btipo_desc.DESC_GRUPOPAL LIKE :Buscar OR
					adm_empresas_tipo_desc.NOMB_TIPOE LIKE :Buscar OR
					adm_empresas_tipo_desc.DESC_TIPOE LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}		
		//RocketMP - Ingresos
		elseif($tipo==113){
			$sWhere=" AND (
					m_produccion.OBS_PRODUCCION LIKE :Buscar OR
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_concepto.DESC_CONCEPTO LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//RocketMP - Gastos
		elseif($tipo==114){
			$sWhere=" AND (
					m_gastos.OBS_GASTO LIKE :Buscar OR
					m_equipo.COD_EQ LIKE :Buscar OR
					m_equipo.DESC_EQ LIKE :Buscar OR
					m_concepto.DESC_CONCEPTO LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar)";
		}
		//CARACTERISTICAS CRM
		elseif($tipo==115){			
			$sWhere=" AND (m_caracteristicas.NOMB_CARACTER LIKE :Buscar)";
		}
		//CARACTERISTICAS PROY CRM
		elseif($tipo==116){			
			$sWhere=" AND (m_caract_proy.NOMB_CARACPROY LIKE :Buscar)";
		}
		elseif($tipo==117){	//PROGRAMACION DE PAGOS
			$sWhere=" AND (				
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR				
					DATE_FORMAT(m_separacion_pagos.FECHAP_PAGO,'%d/%m/%Y') LIKE :Buscar) ";	
		}	
		elseif($tipo==118){ //OTROS PAGOS CRM
			$sWhere=" AND (			
					b_entidades_valor.NOMB_ENTIDAD LIKE :Buscar OR		
					u_inmuebles.NOM_INMUEBLE LIKE :Buscar OR 
					m_proyectos_segmento.NOMB_SEGMENTO LIKE :Buscar OR
					m_proyectos.NOMB_PROYECTO LIKE :Buscar OR					
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR 				
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					u_companias.NOMB_COMPANIA LIKE :Buscar OR	
					CONCAT('-',m_separacion.ID_SEPARACION) LIKE :Buscar OR
					DATE_FORMAT(m_separacion_valores.FECHAE_SEPARACION,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==119){ //NOTAS CLIENTES CRM
			$sWhere=" AND (			
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					u_companias.NOMB_COMPANIA LIKE :Buscar OR						
					adm_usuarios_op.NOMBRE_U LIKE :Buscar OR
					adm_usuarios_op.APELLIDO_U LIKE :Buscar OR	
					CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar OR
					t_notas.TITULO LIKE :Buscar OR
					t_notas.NOTA LIKE :Buscar OR
					DATE_FORMAT(t_notas.FECHA_NOTA,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==120){ //NEGOCIOS CRM
			$sWhere=" AND (			
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					u_companias.NOMB_COMPANIA LIKE :Buscar OR	
					
					m_negocios.NOMB_NEGOCIO LIKE :Buscar OR
					m_negocios.DESC_NEGOCIO LIKE :Buscar OR
					DATE_FORMAT(m_negocios.FECHAI_NEGOCIO,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==121){ //EVENTOS CRM
			$sWhere=" AND (							
					m_eventos.NOMB_EVENTO LIKE :Buscar OR
					m_eventos.DESC_EVENTO LIKE :Buscar OR
					DATE_FORMAT(CONVERT_TZ(m_eventos.FECHAC_EVENTO,'+00:00','$_TZ'),'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==122){ //COMPAÑIA
			$sWhere=" AND (			
					u_companias.NOMB_COMPANIA LIKE :Buscar OR
					u_companias.DESC_COMPANIA LIKE :Buscar) ";
		}
		elseif($tipo==123){ //PORTAFOLIO
			$sWhere=" AND (			
					u_portafolio.NOMB_PORTAFOLIO LIKE :Buscar OR
					u_portafolio.DESC_PORTAFOLIO LIKE :Buscar) ";
		}
		elseif($tipo==124){ //NEGOCIOS CRM - ESTADO
			$sWhere=" AND (			
					DATE_FORMAT(m_negocios_estado.FECHA_ESTADO,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==125){ //PORTAFOLIO PRECIOS - ESTADO
			$sWhere=" AND (			
					DATE_FORMAT(u_portafolio_precios.FECHA_PRECIO,'%d/%m/%Y') LIKE :Buscar OR
					s_cresp.NOMB_RESP LIKE :Buscar OR
					fac_moneda.COD01_MONEDA LIKE :Buscar) ";
		}
		elseif($tipo==126){ //PORTAFOLIO PRECIOS - ESTADO
			$sWhere=" AND (			
					DATE_FORMAT(CONVERT_TZ(u_eventoprog.FECHAI_PROG,'+00:00','$_TZ'),'%d/%m/%Y %H:%i') LIKE :Buscar OR
					DATE_FORMAT(CONVERT_TZ(u_eventoprog.FECHAI_PROG,'+00:00','$_TZ'),'%d/%m/%Y') LIKE :Buscar OR
					u_eventoprog.TITULO_PROG LIKE :Buscar OR
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					u_companias.NOMB_COMPANIA LIKE :Buscar OR	
					u_eventoprog.LUGAR_PROG LIKE :Buscar) ";
		}
		elseif($tipo==127){ //INVOCE CRM
			$sWhere=" AND (			
					u_clientes.NOMB_CLIENTE LIKE :Buscar OR
					u_clientes.APELLIDO_CLIENTE LIKE :Buscar OR 
					CONCAT(u_clientes.NOMB_CLIENTE,' ',u_clientes.APELLIDO_CLIENTE) LIKE :Buscar OR 
					u_clientes.TEL_CLIENTE LIKE :Buscar OR
					u_clientes.TEL2_CLIENTE LIKE :Buscar OR
					u_clientes.TEL3_CLIENTE LIKE :Buscar OR
					u_clientes_datos.DOCUMENTO LIKE :Buscar OR
					u_clientes_datos.DIRECCION_DATOS LIKE :Buscar OR	
					u_companias.NOMB_COMPANIA LIKE :Buscar OR	
					
					u_facturas.NUMB_FACTURA LIKE :Buscar OR					
					DATE_FORMAT(u_facturas.FECHAE_FACTURA,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==128){ //AVANCES INVOCE
			$sWhere=" AND (									
					DATE_FORMAT(u_facturas_parcial.FECHA_PARCIAL,'%d/%m/%Y') LIKE :Buscar) ";
		}
		elseif($tipo==129){ //Cartilla Eventos
			$sWhere=" AND (									
					s_cartilla.NOMB_CARTILLA LIKE :Buscar) ";
		}
		elseif($tipo==130){ //Encuesta Eventos
			$sWhere=" AND (									
					s_encuestas.TITULO_ENCUESTA LIKE :Buscar) ";
		}
		elseif($tipo==131){ //Eventos Eventos
			$sWhere=" AND (									
					s_eventos.TITULO_EVENTO LIKE :Buscar OR
					s_eventos.UBICAC_EVENTO LIKE :Buscar) ";
		}
		elseif($tipo==132){ //Invitados y invitaciones eventos
			$sWhere=" AND (		
					s_invitados.EMAIL_INVITADO LIKE :Buscar OR		
					s_invitados.COMPANIA_INVITADO LIKE :Buscar OR							
					s_invitados.NOMBRES_INVITADO LIKE :Buscar OR
					s_invitados.TEL1_INVITADO LIKE :Buscar) ";
		}

		elseif($tipo==190){ //Proyectos
			$sWhere=" AND (		
					x_proyectos.TITULO_PROYECTO LIKE :Buscar OR		
					x_proyectos.DESC_PROYECTO LIKE :Buscar) ";
		}
		elseif($tipo==191){ //Tipos
			$sWhere=" AND (		
					x_tipoproy.NOMB_TIPOP LIKE :Buscar OR		
					x_tipoproy.DESC_TIPOP LIKE :Buscar) ";
		}
		elseif($tipo==192){ //Instituciones
			$sWhere=" AND (		
					x_instituciones.NOMB_INSTITU LIKE :Buscar OR		
					x_instituciones.DESC_INSTITU LIKE :Buscar) ";
		}
		elseif($tipo==193){ //Programas
			$sWhere=" AND (		
					x_instituciones_programa.TITULO_PROGRAMA LIKE :Buscar) ";
		}
		elseif($tipo==194){ //Eventos
			$sWhere=" AND (		
					x_eventos.TITULO_EVENTO LIKE :Buscar) ";
		}
		elseif($tipo==195){ //Colaborador
			$sWhere=" AND (		
					x_eventos.NOMB_COLABORADOR LIKE :Buscar) ";
		}
		//TUPYME
		elseif($tipo==196){ //Proyectos
			$sWhere=" AND (		
					x_proyectos_lang.TITULO_PROYECTO LIKE :Buscar OR
					x_proyectos_lang.STITULO_PROYECTO LIKE :Buscar OR	
					x_proyectos_lang.DESC_PROYECTO LIKE :Buscar) ";
		}
		elseif($tipo==197){ //Tipos
			$sWhere=" AND (		
					x_tipoproy_lang.NOMB_TIPOP LIKE :Buscar OR		
					x_tipoproy_lang.DESC_TIPOP LIKE :Buscar) ";
		}
		elseif($tipo==198){ //landing
			$sWhere=" AND (		
					x_landings.NOMB_LANDING LIKE :Buscar OR
					x_landings_lang.TEXT_HEADER LIKE :Buscar OR,
					x_landings_lang.TEXT_SERVICES LIKE :Buscar OR,
					x_landings_lang.TEXT_CONTACT) ";
		}
		elseif($tipo==199){ //CONTACTO
			$sWhere=" AND (	
					x_landings.NAME_CONTACT LIKE :Buscar OR
					x_landings.EMAIL_CONTATC LIKE :Buscar OR
					x_landings.MSG_CONTACT LIKE :Buscar) ";
		}
		elseif($tipo==200){ //blog
			$sWhere=" AND (		
					x_blog.TITLE_BLOG LIKE :Buscar OR					
					x_blog.TITLESEO_BLOG LIKE :Buscar OR
					x_blog.METADESC_BLOG LIKE :Buscar) ";
		}
		/*FALCON DE NUEVO*/
		elseif($tipo==201){ //Agregar caract
			$sWhere=" AND (z_caracter.NOMB_CARACTER LIKE :Buscar) ";
		}


		//MENSAJERO MOTO
		elseif($tipo==210){ 
			$sWhere=" AND (x_moto.PLACA_MOTO LIKE :Buscar OR
					adm_usuarios.NOMBRE_U LIKE :Buscar OR
					adm_usuarios.APELLIDO_U LIKE :Buscar OR 
					CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar) ";
		}
		//MENSAJERO ZONA
		elseif($tipo==211){ 
			$sWhere=" AND (x_zona.NOMB_ZONA LIKE :Buscar) ";
		}
		//MENSAJERO SOLICITUD
		elseif($tipo==212){
			$sWhere="AND (adm_usuarios.NOMBRE_U LIKE :Buscar OR 
			adm_usuarios.APELLIDO_U LIKE :Buscar OR
			CONCAT(adm_usuarios.NOMBRE_U,' ',adm_usuarios.APELLIDO_U) LIKE :Buscar OR

			x_usuario_data.TEL1_USUARIO LIKE :Buscar OR 
			x_nouser.NOMB_NOUSER LIKE :Buscar OR 
			x_nouser.TEL_NOUSER LIKE :Buscar) ";
		}		
		//TUPYME CLIENTES
		elseif($tipo==40061){
			$sWhere="AND (x_client.NOMB_CLIENT LIKE :Buscar) ";
		}
		//TUPYME TESTIMONIO
		elseif($tipo==40081){
			$sWhere="AND (x_testimonio.PERSON_TESTI LIKE :Buscar) ";
		}
		//INNOVA PUBLICACION
		elseif($tipo==3001){
			$sWhere="AND( 
					x_proyectos.NOMB_PROYECTO LIKE :Buscar
				)";
		}
		//INNOVA COMENTARIOS
		elseif($tipo==3002){
			$sWhere="AND( 
						x_proyectos_comment.COMMENT LIKE :Buscar
					OR 	adm_usuarios_op.NOMBRE_U LIKE :Buscar
					OR 	adm_usuarios_op.APELLIDO_U LIKE :Buscar
					OR CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar
				)";
		}
		//INNOVA SOLICITUD
		elseif($tipo==3011){
			$sWhere="AND( 
						x_solicitud.ID_SOLICITUD LIKE :Buscar
					OR 	adm_usuarios_op.NOMBRE_U LIKE :Buscar
					OR 	adm_usuarios_op.APELLIDO_U LIKE :Buscar
					OR CONCAT(adm_usuarios_op.NOMBRE_U,' ',adm_usuarios_op.APELLIDO_U) LIKE :Buscar
				)";
		}
		//INNOVA SOLICITUD READER
		elseif($tipo==3021){
			$sWhere="AND( 
						adm_usuarios_s.NOMBRE_U LIKE :Buscar
					OR 	adm_usuarios_s.APELLIDO_U LIKE :Buscar
					OR 	CONCAT(adm_usuarios_s.NOMBRE_U,' ',adm_usuarios_s.APELLIDO_U) LIKE :Buscar
					OR 	adm_usuarios_f.NOMBRE_U LIKE :Buscar
					OR 	adm_usuarios_f.APELLIDO_U LIKE :Buscar
					OR 	CONCAT(adm_usuarios_f.NOMBRE_U,' ',adm_usuarios_f.APELLIDO_U) LIKE :Buscar
				)";
		}
		
	}
	else $sWhere=' ';
	
	return($sWhere);
}
?>
<?php 
$tiempo_cookie=time()+(60*60*24*60);
$dominio_activo=$_SERVER['HTTP_HOST'];

$dominio="siie.co";

$dir_rel_base="/var/www/siie/public_html/";
$dir_rel[1]="/var/www/siie/public_html/implement";
$dir_rel[3]="/var/www/siie/temp";

$tzone_def="-05:00";
$minimo_pass=5;
$fmin=1;
$fmax=3000000;
$fmax_zip=20000000;
/*PAGINACION*/
$NMaxItems[1]=24;
$NMaxItems[2]=10;
$NMaxItems[3]=36;
$NMaxItems[4]=6;

/* NEW PAGE */
$NewMaxItems[1]=35;
$NewMaxItems[2]=10;

$NMaxPags[1]=10;
$NMaxPags[2]=10;
$NMaxPags[3]=30;
$NMaxPags[4]=10;

$_files_clase=array();
$_files_clase[0]=array('image/jpeg','image/png','image/bmp','image/gif','image/tif','image/x-icon','image/vnd.microsoft.icon','image/x-ico');
$_files_clase[1]=array('image/jpeg','image/png','image/bmp','image/gif','image/tif',
						'application/acrobat','application/x-pdf','applications/vnd.pdf',
						'application/pdf',
						'text/pdf','text/x-pdf','text/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-powerpoint',
						'application/vnd.openxmlformats-officedocument.presentationml.presentation','text/plain');
$_files_clase[2]=array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed','','text/csv');
$_files_clase[3]=array('image/x-icon','image/vnd.microsoft.icon','image/x-ico');
$_files_clase[4]=array('audio/basic'
					,	'auido/L24'
					,	'audio/mid'
					,	'audio/mpeg'
					,	'audio/mp4'
					,	'audio/x-aiff'
					,	'audio/x-mpegurl'
					,	'audio/vnd.rn-realaudio'
					,	'audio/ogg'
					,	'audio/vorbis'
					,	'audio/vnd.wav'
					,	'audio/x-wav'
					,	'audio/wav'
					,	'audio/x-ms-wma'
					,	'audio/x-ms-wax'
					,	'audio/x-ms-wmv'
					,	'video/x-ms-wma');


$_files_ext=array();
$_files_ext[0]=array('*.jpeg','*.png','*.bmp','*.gif','*.tif','*.ico');
$_files_ext[1]=array('*.pdf','*.doc','*.docx','*.xls','*.xlsx','*.ppt','*.pptx','*.txt');
$_files_ext[2]=array('*.zip');
$_files_ext[3]=array('*.ico');

/*TIPOS DE IMAGEN*/
$latitude_def="7.1016861";
$longitude_def="-73.1024662";
$zoom_def=9;
$_NombCiudadPpal="Bucaramanga - COL";

$_SNombCiudadPpal="Bucaramanga";
$_SNombDptoPpal="Santander";
$_SNombPaisPpal="Colombia";
$_SCodPaisPpal="COL";
$_IdCiudadPpal=2257;
/**/

//TIPO DE BOTON
	//1 NUEVO
	//2 VER
	//3 EDITAR	
	//4 ELIMINAR
	//5 OPERACIONES
	//6 ADDS
	//10 OTROS

$btn_actualizar['label']='txt-1434-0';
$btn_actualizar['title']='txt-1434-1';
$btn_actualizar['text']=true;
$btn_actualizar['icons']="fa-refresh";
$btn_actualizar['tpb']=10;
$btn_actualizar['transictp']=4;
$btn_actualizar['chk']=false;
$btn_actualizar['fl']=array('t'=>2);


$btn_habdes_activos['label']='txt-1022-0';
$btn_habdes_activos['title']='txt-1022-1';
$btn_habdes_activos['text']=true;
$btn_habdes_activos['icons']="fa-check-circle";
$btn_habdes_activos['tpb']=10;
$btn_habdes_activos['transictp']=4;
$btn_habdes_activos['chk']=false;
$btn_habdes_activos['fl']=array('t'=>2);

$btn_habdes_inactivos['label']='txt-1024-0';
$btn_habdes_inactivos['title']='txt-1024-1';
$btn_habdes_inactivos['text']=true;
$btn_habdes_inactivos['icons']="fa-trash-o";
$btn_habdes_inactivos['tpb']=10;
$btn_habdes_inactivos['transictp']=4;
$btn_habdes_inactivos['chk']=false;
$btn_habdes_inactivos['fl']=array('t'=>3);


$btn_nuevo['clase'][0]="botones_def";
$btn_nuevo['label']='txt-1009-0';
$btn_nuevo['title']='txt-1009-1';
$btn_nuevo['text']=true;
$btn_nuevo['icons']="fa-file-o";
$btn_nuevo['tpb']=1;
$btn_nuevo['transictp']=1;

$btn_info['clase'][0]="linkex";
$btn_info['label']='txt-1016-0';
$btn_info['title']='txt-1016-1';
$btn_info['text']=false;
$btn_info['icons']="fa-search-plus ";
$btn_info['tpb']=2;
$btn_info['transictp']=2;

$btn_editar['clase'][0]="botones_def";
$btn_editar['label']='txt-1017-0';
$btn_editar['title']='txt-1017-1';
$btn_editar['text']=false;
$btn_editar['icons']="fa-edit";
$btn_editar['tpb']=3;
$btn_editar['transictp']=1;

$btn_ver['clase'][0]="botones_def";
$btn_ver['label']='txt-1125-0';
$btn_ver['title']='txt-1125-1';
$btn_ver['text']=false;
$btn_ver['icons']="fa-file";
$btn_ver['tpb']=3;
$btn_ver['transictp']=3;

$btn_borrar['clase'][0]="botones_def";
$btn_borrar['label']='txt-1005-0';
$btn_borrar['title']='txt-1005-1';
$btn_borrar['text']=false;
$btn_borrar['icons']="fa-trash";
$btn_borrar['tpb']=4;
$btn_borrar['transictp']=1;

$btn_recuperar['clase'][0]="botones_def";
$btn_recuperar['label']='txt-1010-0';
$btn_recuperar['title']='txt-1010-1';
$btn_recuperar['text']=false;
$btn_recuperar['icons']="fa-undo";
$btn_recuperar['tpb']=4;
$btn_recuperar['transictp']=1;


/*AGG A EQUIO U OT*/
$btn_subirar['clase'][0]="botones_def";
$btn_subirar['label']='txt-1018-0';
$btn_subirar['title']='txt-1018-1';
$btn_subirar['text']=false;
$btn_subirar['icons']="ui-cloud-upload";
$btn_subirar['tpb']=6;
$btn_subirar['transictp']=1;

$btn_subirft['clase'][0]="botones_def";
$btn_subirft['label']='txt-1233-0';
$btn_subirft['title']='txt-1233-1';
$btn_subirft['text']=false;
$btn_subirft['icons']="fa-file-image-o";
$btn_subirft['tpb']=6;
$btn_subirft['transictp']=1;

$btn_caracteristica['clase'][0]="botones_def";
$btn_caracteristica['label']='txt-1129-0';
$btn_caracteristica['title']='txt-1129-1';
$btn_caracteristica['text']=true;
$btn_caracteristica['icons']="fa-plus";
$btn_caracteristica['tpb']=6;
$btn_caracteristica['transictp']=1;

/*OP EQUIPO*/
$btn_contador['clase'][0]="botones_def";
$btn_contador['label']='txt-320-0';
$btn_contador['title']='txt-320-1';
$btn_contador['text']=true;
$btn_contador['icons']="fa-plus";
$btn_contador['tpb']=5;
$btn_contador['transictp']=1;

$btn_consumo['clase'][0]="botones_def";
$btn_consumo['label']='txt-355-0';
$btn_consumo['title']='txt-355-1';
$btn_consumo['text']=true;
$btn_consumo['icons']="fa-plus";
$btn_consumo['tpb']=5;
$btn_consumo['transictp']=1;

$btn_estado['clase'][0]="botones_def";
$btn_estado['label']='txt-332-0';
$btn_estado['title']='txt-332-1';
$btn_estado['text']=true;
$btn_estado['icons']="fa-plus";
$btn_estado['tpb']=5;
$btn_estado['transictp']=1;


$btn_estado_neg['clase'][0]="botones_def";
$btn_estado_neg['label']='txt-9116-0';
$btn_estado_neg['title']='txt-9116-1';
$btn_estado_neg['text']=true;
$btn_estado_neg['icons']="fa-plus";
$btn_estado_neg['tpb']=5;
$btn_estado_neg['transictp']=1;



$btn_estado_aprovar['clase'][0]="botones_def";
$btn_estado_aprovar['label']='txt-9071-0';
$btn_estado_aprovar['title']='txt-9071-1';
$btn_estado_aprovar['text']=true;
$btn_estado_aprovar['icons']="fa-check-circle";
$btn_estado_aprovar['tpb']=5;
$btn_estado_aprovar['transictp']=1;

$btn_estado_cancelar['clase'][0]="botones_def";
$btn_estado_cancelar['label']='txt-9072-0';
$btn_estado_cancelar['title']='txt-9072-1';
$btn_estado_cancelar['text']=true;
$btn_estado_cancelar['icons']="fa-check-circle";
$btn_estado_cancelar['tpb']=5;
$btn_estado_cancelar['transictp']=1;


$btn_movimiento['clase'][0]="botones_def";
$btn_movimiento['label']='txt-357-0';
$btn_movimiento['title']='txt-357-1';
$btn_movimiento['text']=true;
$btn_movimiento['icons']="fa-plus";
$btn_movimiento['tpb']=5;
$btn_movimiento['transictp']=1;

$btn_ot['clase'][0]="botones_def";
$btn_ot['label']='txt-358-0';
$btn_ot['title']='txt-358-1';
$btn_ot['text']=true;
$btn_ot['icons']="fa-plus";
$btn_ot['tpb']=5;
$btn_ot['transictp']=1;

$btn_programa['clase'][0]="botones_def";
$btn_programa['label']='txt-359-0';
$btn_programa['title']='txt-359-1';
$btn_programa['text']=true;
$btn_programa['icons']="fa-plus";
$btn_programa['tpb']=6;
$btn_programa['transictp']=1;

$btn_tratamiento['clase'][0]="botones_def";
$btn_tratamiento['label']='txt-534-0';
$btn_tratamiento['title']='txt-534-1';
$btn_tratamiento['text']=true;
$btn_tratamiento['icons']="fa-plus";
$btn_tratamiento['tpb']=6;
$btn_tratamiento['transictp']=1;

$btn_otseguimiento['clase'][0]="botones_def";
$btn_otseguimiento['label']='txt-1047-0';
$btn_otseguimiento['title']='txt-1047-1';
$btn_otseguimiento['text']=true;
$btn_otseguimiento['icons']="fa-plus";
$btn_otseguimiento['tpb']=5;
$btn_otseguimiento['transictp']=1;

$btn_otcerrar['clase'][0]="botones_def";
$btn_otcerrar['label']='txt-1048-0';
$btn_otcerrar['title']='txt-1048-1';
$btn_otcerrar['text']=true;
$btn_otcerrar['icons']="fa-plus";
$btn_otcerrar['tpb']=5;
$btn_otcerrar['transictp']=1;

$btn_othistoria['clase'][0]="botones_def";
$btn_othistoria['label']='txt-481-0';
$btn_othistoria['title']='txt-481-1';
$btn_othistoria['text']=true;
$btn_othistoria['icons']="ui-icon-circle-arrow-s";
$btn_othistoria['tpb']=5;
$btn_othistoria['transictp']=1;

$btn_no_othistoria['clase'][0]="botones_def";
$btn_no_othistoria['label']='txt-482-0';
$btn_no_othistoria['title']='txt-482-1';
$btn_no_othistoria['text']=true;
$btn_no_othistoria['icons']="ui-icon-circle-arrow-s";
$btn_no_othistoria['tpb']=5;
$btn_no_othistoria['transictp']=1;

$btn_solmmto['clase'][0]="botones_def";
$btn_solmmto['label']='txt-563-0';
$btn_solmmto['title']='txt-563-1';
$btn_solmmto['text']=true;
$btn_solmmto['icons']="ui-icon-mail-closed";
$btn_solmmto['tpb']=5;
$btn_solmmto['transictp']=1;

$btn_solmmto_fw['clase'][0]="botones_def";
$btn_solmmto_fw['label']='txt-569-0';
$btn_solmmto_fw['title']='txt-569-1';
$btn_solmmto_fw['text']=true;
$btn_solmmto_fw['icons']="ui-icon-mail-closed";
$btn_solmmto_fw['tpb']=5;
$btn_solmmto_fw['transictp']=1;

$btn_solmmto_reply['clase'][0]="botones_def";
$btn_solmmto_reply['label']='txt-568-0';
$btn_solmmto_reply['title']='txt-568-1';
$btn_solmmto_reply['text']=true;
$btn_solmmto_reply['icons']="fa-check-circle";
$btn_solmmto_reply['tpb']=5;
$btn_solmmto_reply['transictp']=1;

$btn_solmmto_ot['clase'][0]="botones_def";
$btn_solmmto_ot['label']='txt-567-0';
$btn_solmmto_ot['title']='txt-567-1';
$btn_solmmto_ot['text']=true;
$btn_solmmto_ot['icons']="fa-check-circle";
$btn_solmmto_ot['tpb']=5;
$btn_solmmto_ot['transictp']=1;

$btn_tratamiento_aplic['clase'][0]="botones_def";
$btn_tratamiento_aplic['label']='txt-571-0';
$btn_tratamiento_aplic['title']='txt-571-1';
$btn_tratamiento_aplic['text']=true;
$btn_tratamiento_aplic['icons']="fa-plus";
$btn_tratamiento_aplic['tpb']=6;
$btn_tratamiento_aplic['transictp']=1;

$btn_ot_audit['clase'][0]="botones_def";
$btn_ot_audit['label']='txt-574-0';
$btn_ot_audit['title']='txt-574-1';
$btn_ot_audit['text']=true;
$btn_ot_audit['icons']="fa-plus";
$btn_ot_audit['tpb']=6;
$btn_ot_audit['transictp']=1;
/*PRESU*/
$btn_presupuesto['clase'][0]="botones_def";
$btn_presupuesto['label']='txt-1049-0';
$btn_presupuesto['title']='txt-1049-1';
$btn_presupuesto['text']=true;
$btn_presupuesto['icons']="fa-plus";
$btn_presupuesto['tpb']=5;
$btn_presupuesto['transictp']=1;

$btn_presuoficio['clase'][0]="botones_def";
$btn_presuoficio['label']='txt-1050-0';
$btn_presuoficio['title']='txt-1050-1';
$btn_presuoficio['text']=true;
$btn_presuoficio['icons']="fa-plus";
$btn_presuoficio['tpb']=5;
$btn_presuoficio['transictp']=1;

$btn_presuitem['clase'][0]="botones_def";
$btn_presuitem['label']='txt-1051-0';
$btn_presuitem['title']='txt-1051-1';
$btn_presuitem['text']=true;
$btn_presuitem['icons']="fa-plus";
$btn_presuitem['tpb']=5;
$btn_presuitem['transictp']=1;

$btn_ejecpot['clase'][0]="botones_def";
$btn_ejecpot['label']='txt-1052-0';
$btn_ejecpot['title']='txt-1052-1';
$btn_ejecpot['text']=true;
$btn_ejecpot['icons']="fa-check-circle";
$btn_ejecpot['tpb']=6;
$btn_ejecpot['transictp']=1;

$btn_edit_txt['clase'][0]="botones_def";
$btn_edit_txt['label']='txt-1216-0';
$btn_edit_txt['title']='txt-1216-1';
$btn_edit_txt['text']=true;
$btn_edit_txt['icons']="fa-edit";
$btn_edit_txt['tpb']=6;
$btn_edit_txt['transictp']=1;

$btn_edit_txt_cli['clase'][0]="botones_def";
$btn_edit_txt_cli['label']='txt-1295-0';
$btn_edit_txt_cli['title']='txt-1295-1';
$btn_edit_txt_cli['text']=true;
$btn_edit_txt_cli['icons']="fa-edit";
$btn_edit_txt_cli['tpb']=6;
$btn_edit_txt_cli['transictp']=1;

/**/
/*Gasto*/
$btn_gastogral['clase'][0]="botones_def";
$btn_gastogral['label']='txt-1044-0';
$btn_gastogral['title']='txt-1044-1';
$btn_gastogral['text']=true;
$btn_gastogral['icons']="fa-plus";
$btn_gastogral['tpb']=5;
$btn_gastogral['transictp']=1;

$btn_gastoempl['clase'][0]="botones_def";
$btn_gastoempl['label']='txt-1045-0';
$btn_gastoempl['title']='txt-1046-1';
$btn_gastoempl['text']=true;
$btn_gastoempl['icons']="fa-plus";
$btn_gastoempl['tpb']=5;
$btn_gastoempl['transictp']=1;

$btn_gastoinsumo['clase'][0]="botones_def";
$btn_gastoinsumo['label']='txt-1046-0';
$btn_gastoinsumo['title']='txt-1046-1';
$btn_gastoinsumo['text']=true;
$btn_gastoinsumo['icons']="fa-plus";
$btn_gastoinsumo['tpb']=5;
$btn_gastoinsumo['transictp']=1;
/**/
$btn_adjunto['clase'][0]="linkexadj";
$btn_adjunto['label']='txt-1030-0';
$btn_adjunto['title']='txt-1030-1';
$btn_adjunto['text']=false;
$btn_adjunto['ext']="1";
$btn_adjunto['icons']="ui-icon-extlink";
$btn_adjunto['tpb']=6;
$btn_adjunto['transictp']=3;

$btn_gastogral['clase'][0]="botones_def";
$btn_gastogral['label']='txt-1044-0';
$btn_gastogral['title']='txt-1044-1';
$btn_gastogral['text']=true;
$btn_gastogral['icons']="fa-plus";
$btn_gastogral['tpb']=5;
$btn_gastogral['transictp']=1;

/*INGRESOS Y GASTOS*/
$btn_ingreso['clase'][0]="botones_def";
$btn_ingreso['label']='txt-1247-0';
$btn_ingreso['title']='txt-1247-1';
$btn_ingreso['text']=true;
$btn_ingreso['icons']="fa-plus";
$btn_ingreso['tpb']=5;
$btn_ingreso['transictp']=1;

$btn_gasto['clase'][0]="botones_def";
$btn_gasto['label']='txt-1248-0';
$btn_gasto['title']='txt-1248-1';
$btn_gasto['text']=true;
$btn_gasto['icons']="fa-plus";
$btn_gasto['tpb']=5;
$btn_gasto['transictp']=1;

$btn_cliente['clase'][0]="botones_def";
$btn_cliente['label']='txt-9074-0';
$btn_cliente['title']='txt-9074-1';
$btn_cliente['text']=true;
$btn_cliente['icons']="ui-icon-contact";
$btn_cliente['tpb']=5;
$btn_cliente['transictp']=1;


/*OP ALMACÉN RM*/
$btn_agalmacen['clase'][0]="botones_def";
$btn_agalmacen['label']='txt-1079-0';
$btn_agalmacen['title']='txt-1079-1';
$btn_agalmacen['text']=true;
$btn_agalmacen['icons']="ui-icon-transferthick-e-w";
$btn_agalmacen['tpb']=5;
$btn_agalmacen['transictp']=1;

$btn_movalmacen['clase'][0]="botones_def";
$btn_movalmacen['label']='txt-1080-0';
$btn_movalmacen['title']='txt-1080-1';
$btn_movalmacen['text']=true;
$btn_movalmacen['icons']="ui-icon-transferthick-e-w";
$btn_movalmacen['tpb']=5;
$btn_movalmacen['transictp']=1;

/*AGREGAR A OTROS OBJETOS*/

$btn_repuesto['clase'][0]="botones_def";
$btn_repuesto['label']="Vincular&nbsp;Ítem";
$btn_repuesto['title']="Vincular ítem al objeto seleccionado";
$btn_repuesto['text']=true;
$btn_repuesto['icons']="ui-icon-suitcase";
$btn_repuesto['tpb']=6;
$btn_repuesto['transictp']=1;

$btn_empleado['clase'][0]="botones_def";
$btn_empleado['label']="Vincular&nbsp;Empleado";
$btn_empleado['title']="Vincular empleado a la empresa seleccionada";
$btn_empleado['text']=true;
$btn_empleado['icons']="fa-users";
$btn_empleado['tpb']=6;
$btn_empleado['transictp']=1;

$btn_vacccomp['clase'][0]="botones_def";
$btn_vacccomp['label']='txt-9062-0';
$btn_vacccomp['title']='txt-9062-1';
$btn_vacccomp['text']=true;
$btn_vacccomp['icons']="fa-check-circle";
$btn_vacccomp['tpb']=6;
$btn_vacccomp['transictp']=1;

$btn_vinclientes['clase'][0]="botones_def";
$btn_vinclientes['label']="Vincular&nbsp;Cliente";
$btn_vinclientes['title']="Vincular clientes";
$btn_vinclientes['text']=true;
$btn_vinclientes['icons']="fa-users";
$btn_vinclientes['tpb']=6;
$btn_vinclientes['transictp']=1;

$btn_tipodecliente['clase'][0]="botones_def";
$btn_tipodecliente['label']="Asignar&nbsp;Tipo";
$btn_tipodecliente['title']="Asignar a un tipo de cliente";
$btn_tipodecliente['text']=true;
$btn_tipodecliente['icons']="ui-icon-suitcase";
$btn_tipodecliente['tpb']=6;
$btn_tipodecliente['transictp']=1;

//MERCAYA
$btn_myestado['clase'][0]="botones_def";
$btn_myestado['label']='txt-1140-0';
$btn_myestado['title']='txt-1140-1';
$btn_myestado['text']=true;
$btn_myestado['icons']="fa-plus";
$btn_myestado['tpb']=5;
$btn_myestado['transictp']=1;

$btn_mhorario['clase'][0]="botones_def";
$btn_mhorario['label']="Nuevo&nbsp;Horario";
$btn_mhorario['title']="Crear un nuevo Horario";
$btn_mhorario['text']=false;
$btn_mhorario['icons']="ui-icon-clock";
$btn_mhorario['tpb']=6;
$btn_mhorario['transictp']=1;

$btn_sucursal['clase'][0]="botones_def";
$btn_sucursal['label']="Vincular&nbsp;Sucursal";
$btn_sucursal['title']="Vincular sucursal";
$btn_sucursal['text']=true;
$btn_sucursal['icons']="ui-icon-suitcase";
$btn_sucursal['tpb']=6;
$btn_sucursal['transictp']=1;

$btn_vsegmento['clase'][0]="botones_def";
$btn_vsegmento['label']='txt-9063-0';
$btn_vsegmento['title']='txt-9063-1';
$btn_vsegmento['text']=true;
$btn_vsegmento['icons']="fa-plus";
$btn_vsegmento['tpb']=6;
$btn_vsegmento['transictp']=1;

$btn_vinmueble['clase'][0]="botones_def";
$btn_vinmueble['label']='txt-9064-0';
$btn_vinmueble['title']='txt-9064-1';
$btn_vinmueble['text']=true;
$btn_vinmueble['icons']="fa-plus";
$btn_vinmueble['tpb']=6;
$btn_vinmueble['transictp']=1;

$btn_apago['clase'][0]="botones_def";
$btn_apago['label']='txt-9061-0';
$btn_apago['title']='txt-9061-1';
$btn_apago['text']=true;
$btn_apago['icons']="fa-check-circle";
$btn_apago['tpb']=6;
$btn_apago['transictp']=1;

$btn_apago_sub['clase'][0]="botones_def";
$btn_apago_sub['label']='txt-9060-0';
$btn_apago_sub['title']='txt-9060-0';
$btn_apago_sub['text']=true;
$btn_apago_sub['icons']="fa-check-circle";
$btn_apago_sub['tpb']=6;
$btn_apago_sub['transictp']=1;

$btn_ipago['clase'][0]="botones_def";
$btn_ipago['label']='txt-9058-0';
$btn_ipago['title']='txt-9058-1';
$btn_ipago['text']=true;
$btn_ipago['icons']="fa-check-circle";
$btn_ipago['tpb']=6;
$btn_ipago['transictp']=1;

$btn_pago_add['clase'][0]="botones_def";
$btn_pago_add['label']='txt-9073-0';
$btn_pago_add['title']='txt-9073-1';
$btn_pago_add['text']=true;
$btn_pago_add['icons']="fa-check-circle";
$btn_pago_add['tpb']=6;
$btn_pago_add['transictp']=1;


$btn_rdocumento['clase'][0]="botones_def";
$btn_rdocumento['label']='txt-9059-0';
$btn_rdocumento['title']='txt-9059-1';
$btn_rdocumento['text']=true;
$btn_rdocumento['icons']="fa-check-circle";
$btn_rdocumento['tpb']=6;
$btn_rdocumento['transictp']=1;

$btn_cseguimiento['clase'][0]="botones_def";
$btn_cseguimiento['label']='txt-9065-0';
$btn_cseguimiento['title']='txt-9065-1';
$btn_cseguimiento['text']=true;
$btn_cseguimiento['icons']="ui-icon-note";
$btn_cseguimiento['tpb']=5;
$btn_cseguimiento['transictp']=1;

$btn_ccotizacion['clase'][0]="botones_def";
$btn_ccotizacion['label']="Seguimiento";
$btn_ccotizacion['title']="Crear nueva cotización";
$btn_ccotizacion['text']=true;
$btn_ccotizacion['icons']="fa-plus";
$btn_ccotizacion['tpb']=5;
$btn_ccotizacion['transictp']=1;

$btn_reporte['clase'][0]="botones_def";
$btn_reporte['label']='txt-1043-0';
$btn_reporte['title']='txt-1043-1';
$btn_reporte['text']=true;
$btn_reporte['icons']="ui-icon-mail-closed";
$btn_reporte['tpb']=5;
$btn_reporte['transictp']=1;

$btn_enviar['clase'][0]="botones_def";
$btn_enviar['label']='txt-1186-0';
$btn_enviar['title']='txt-1186-0';
$btn_enviar['text']=true;
$btn_enviar['icons']="ui-icon-mail-closed";
$btn_enviar['tpb']=5;
$btn_enviar['transictp']=1;

$btn_lkevento['clase'][0]="botones_def";
$btn_lkevento['label']='txt-9115-0';
$btn_lkevento['title']='txt-9115-0';
$btn_lkevento['text']=true;
$btn_lkevento['icons']="fa-calendar";
$btn_lkevento['tpb']=5;
$btn_lkevento['transictp']=1;

$btn_negocios['clase'][0]="botones_def";
$btn_negocios['label']='txt-9051-0';
$btn_negocios['title']='txt-9051-1';
$btn_negocios['text']=true;
$btn_negocios['icons']="fa-plus";
$btn_negocios['tpb']=5;
$btn_negocios['transictp']=1;

$btn_factura['clase'][0]="botones_def";
$btn_factura['label']='txt-9130-0';
$btn_factura['title']='txt-9130-1';
$btn_factura['text']=true;
$btn_factura['icons']="fa-plus";
$btn_factura['tpb']=5;
$btn_factura['transictp']=1;

$btn_factabono['clase'][0]="botones_def";
$btn_factabono['label']='txt-9135-0';
$btn_factabono['title']='txt-9135-1';
$btn_factabono['text']=true;
$btn_factabono['icons']="fa-plus";
$btn_factabono['tpb']=5;
$btn_factabono['transictp']=1;

/*GRUPOS*/
$btn_ventana['clase'][0]="botones_def";
$btn_ventana['label']='txt-1296-0';
$btn_ventana['title']='txt-1296-1';
$btn_ventana['text']=false;
$btn_ventana['icons']="ui-icon-newwin";
$btn_ventana['tpb']=3;
$btn_ventana['transictp']=1;

$btn_vcresp['clase'][0]="botones_def";
$btn_vcresp['label']='txt-1299-0';
$btn_vcresp['title']='txt-1299-1';
$btn_vcresp['text']=false;
$btn_vcresp['icons']="ui-icon-link";
$btn_vcresp['tpb']=3;
$btn_vcresp['transictp']=1;

$btn_vciudad['clase'][0]="botones_def";
$btn_vciudad['label']='txt-1300-0';
$btn_vciudad['title']='txt-1300-1';
$btn_vciudad['text']=false;
$btn_vciudad['icons']="ui-icon-link";
$btn_vciudad['tpb']=3;
$btn_vciudad['transictp']=1;

$btn_crmseparar['clase'][0]="botones_def";
$btn_crmseparar['label']='txt-9019-0';
$btn_crmseparar['title']='txt-9019-1';
$btn_crmseparar['text']=false;
$btn_crmseparar['icons']="fa-plus";
$btn_crmseparar['tpb']=3;
$btn_crmseparar['transictp']=1;


$btn_vusuario['clase'][0]="botones_def";
$btn_vusuario['label']='txt-1137-0';
$btn_vusuario['title']='txt-1137-1';
$btn_vusuario['text']=false;
$btn_vusuario['icons']="fa-plus";
$btn_vusuario['tpb']=3;
$btn_vusuario['transictp']=1;

$btn_vprecio['clase'][0]="botones_def";
$btn_vprecio['label']='txt-1135-0';
$btn_vprecio['title']='txt-1135-1';
$btn_vprecio['text']=true;
$btn_vprecio['icons']="fa-check-circle";
$btn_vprecio['tpb']=5;
$btn_vprecio['transictp']=1;


$btn_mtag['clase'][0]="botones_def";
$btn_mtag['label']='txt-1138-0';
$btn_mtag['title']='txt-1138-1';
$btn_mtag['text']=true;
$btn_mtag['icons']="fa-tag";
$btn_mtag['tpb']=6;
$btn_mtag['transictp']=1;

$btn_mgrupo['clase'][0]="botones_def";
$btn_mgrupo['label']='txt-1139-0';
$btn_mgrupo['title']='txt-1139-1';
$btn_mgrupo['text']=true;
$btn_mgrupo['icons']="fa-suitcase";
$btn_mgrupo['tpb']=6;
$btn_mgrupo['transictp']=1;

$btn_munid['clase'][0]="botones_def";
$btn_munid['label']="Unidades";
$btn_munid['title']="Vincular a unidad";
$btn_munid['text']=true;
$btn_munid['icons']="fa-bookmark";
$btn_munid['tpb']=6;
$btn_munid['transictp']=1;

/**/
$btn_despacho['clase'][0]="botones_def";
$btn_despacho['label']="Despachar";
$btn_despacho['title']="Despachar pedido";
$btn_despacho['text']=true;
$btn_despacho['icons']="fa-check-circle";
$btn_despacho['tpb']=6;
$btn_despacho['transictp']=1;

$btn_despcancel['clase'][0]="botones_def";
$btn_despcancel['label']='txt-1154-0';
$btn_despcancel['title']='txt-1154-1';
$btn_despcancel['text']=true;
$btn_despcancel['icons']="fa-check-circle";
$btn_despcancel['tpb']=6;
$btn_despcancel['transictp']=1;

$btn_passbook['clase'][0]="botones_def";
$btn_passbook['label']='txt-1259-0';
$btn_passbook['title']='txt-1259-1';
$btn_passbook['text']=true;
$btn_passbook['icons']="fa-user";
$btn_passbook['tpb']=6;
$btn_passbook['transictp']=1;

$btn_evento['clase'][0]="botones_def";
$btn_evento['label']='txt-9111-0';
$btn_evento['title']='txt-9111-1';
$btn_evento['text']=true;
$btn_evento['icons']="fa-plus";
$btn_evento['tpb']=6;
$btn_evento['transictp']=1;

$btn_aprovmanual['clase'][0]="botones_def";
$btn_aprovmanual['label']='txt-1263-0';
$btn_aprovmanual['title']='txt-1263-1';
$btn_aprovmanual['text']=true;
$btn_aprovmanual['icons']="fa-check-circle";
$btn_aprovmanual['tpb']=6;
$btn_aprovmanual['transictp']=1;

/**/
//CIUDAD BONITA
$btn_vdisp['clase'][0]="botones_def";
$btn_vdisp['label']='txt-3003-0';
$btn_vdisp['title']='txt-3003-1';
$btn_vdisp['text']=true;
$btn_vdisp['icons']="fa-check-circle";
$btn_vdisp['tpb']=5;
$btn_vdisp['transictp']=1;

$btn_vcierre['clase'][0]="botones_def";
$btn_vcierre['label']='txt-3014-0';
$btn_vcierre['title']='txt-3014-1';
$btn_vcierre['text']=true;
$btn_vcierre['icons']="fa-check-circle";
$btn_vcierre['tpb']=5;
$btn_vcierre['transictp']=1;

$btn_vpromocion['clase'][0]="botones_def";
$btn_vpromocion['label']='txt-3007-0';
$btn_vpromocion['title']='txt-3007-1';
$btn_vpromocion['text']=true;
$btn_vpromocion['icons']="fa-check-circle";
$btn_vpromocion['tpb']=5;
$btn_vpromocion['transictp']=1;

$btn_vidiomas['clase'][0]="botones_def";
$btn_vidiomas['label']='txt-3004-0';
$btn_vidiomas['title']='txt-3004-1';
$btn_vidiomas['text']=true;
$btn_vidiomas['icons']="fa-check-circle";
$btn_vidiomas['tpb']=5;
$btn_vidiomas['transictp']=1;
//
$btn_aplicar['clase'][0]="";
$btn_aplicar['label']="Aplicar";
$btn_aplicar['title']="Actualizar contador";
$btn_aplicar['text']=false;
$btn_aplicar['icons']="fa-refresh";;

$btn_vicular_ven['clase'][0]="";
$btn_vicular_ven['label']="Vinvular";
$btn_vicular_ven['title']="Vincular Módulo";
$btn_vicular_ven['text']=false;
$btn_vicular_ven['icons']="fa-refresh";

/***ALESTRA***/
$btn_punto['clase'][0]="botones_def";
$btn_punto['label']='txt-11000-0';
$btn_punto['title']='txt-11000-1';
$btn_punto['text']=true;
$btn_punto['icons']="fa-suitcase";
$btn_punto['tpb']=6;
$btn_punto['transictp']=1;

$btn_addruta['clase'][0]="botones_def";
$btn_addruta['label']='txt-11012-0';
$btn_addruta['title']='txt-11012-1';
$btn_addruta['text']=true;
$btn_addruta['icons']="fa-plus";
$btn_addruta['tpb']=6;
$btn_addruta['transictp']=1;

$btn_additinerario['clase'][0]="botones_def";
$btn_additinerario['label']='txt-11013-0';
$btn_additinerario['title']='txt-11013-1';
$btn_additinerario['text']=true;
$btn_additinerario['icons']="fa-plus";
$btn_additinerario['tpb']=6;
$btn_additinerario['transictp']=1;
/**/
/*OT FILTER*/
$btn_obj_abierta['label']='txt-599-0';
$btn_obj_abierta['title']='txt-599-1';
$btn_obj_abierta['text']=true;
$btn_obj_abierta['icons']="fa-cogs";
$btn_obj_abierta['tpb']=10;
$btn_obj_abierta['transictp']=4;
$btn_obj_abierta['chk']=false;
$btn_obj_abierta['fl']=array('t'=>1);

$btn_obj_cerrado['label']='txt-598-0';
$btn_obj_cerrado['title']='txt-598-1';
$btn_obj_cerrado['text']=true;
$btn_obj_cerrado['icons']="fa-cogs";
$btn_obj_cerrado['tpb']=10;
$btn_obj_cerrado['transictp']=4;
$btn_obj_cerrado['chk']=false;
$btn_obj_cerrado['fl']=array('t'=>2);

/*EVENTOS*/

$btn_add_encuesta['label']='txt-7002-0';
$btn_add_encuesta['title']='txt-7002-1';
$btn_add_encuesta['text']=true;
$btn_add_encuesta['icons']="fa-plus";
$btn_add_encuesta['tpb']=5;
$btn_add_encuesta['transictp']=1;

$btn_add_invitacion['label']='txt-1336-0';
$btn_add_invitacion['title']='txt-1336-1';
$btn_add_invitacion['text']=true;
$btn_add_invitacion['icons']="fa-users";
$btn_add_invitacion['tpb']=5;
$btn_add_invitacion['transictp']=1;

$brn_cng_image['label']='txt-1000-0';
$brn_cng_image['title']='txt-1000-1';
$brn_cng_image['text']=true;
$brn_cng_image['icons']="fa-file-image-o";
$brn_cng_image['tpb']=5;
$brn_cng_image['transictp']=1;

$brn_nng_image['label']='txt-1001-0';
$brn_nng_image['title']='txt-1000-1';
$brn_nng_image['text']=true;
$brn_nng_image['icons']="fa-file-image-o";
$brn_nng_image['tpb']=5;
$brn_nng_image['transictp']=1;

$brn_colors['label']='txt-11002-0';
$brn_colors['title']='txt-11002-1';
$brn_colors['text']=true;
$brn_colors['icons']="fa-plus";
$brn_colors['tpb']=5;
$brn_colors['transictp']=1;


/*EVENTOS FIN*/

//CRM//
$btn_por_pagar['label']='txt-9081-0';
$btn_por_pagar['title']='txt-9081-1';
$btn_por_pagar['text']=true;
$btn_por_pagar['icons']="fa-plus";
$btn_por_pagar['tpb']=10;
$btn_por_pagar['transictp']=4;
$btn_por_pagar['chk']=false;
$btn_por_pagar['fl']=array('t'=>0);

$btn_pagados['label']='txt-9083-0';
$btn_pagados['title']='txt-9083-1';
$btn_pagados['text']=true;
$btn_pagados['icons']="fa-check-circle";
$btn_pagados['tpb']=10;
$btn_pagados['transictp']=4;
$btn_pagados['chk']=false;
$btn_pagados['fl']=array('t'=>1);

$btn_renovacion['label']='txt-9082-0';
$btn_renovacion['title']='txt-9082-1';
$btn_renovacion['text']=true;
$btn_renovacion['icons']="fa-calendar";
$btn_renovacion['tpb']=10;
$btn_renovacion['transictp']=4;
$btn_renovacion['chk']=false;
$btn_renovacion['fl']=array('t'=>2);

$btn_anular['label']='txt-9141-0';
$btn_anular['title']='txt-9141-1';
$btn_anular['text']=true;
$btn_anular['icons']="fa-check-circle";
$btn_anular['tpb']=5;
$btn_anular['transictp']=1;

$btn_des_anular['label']='txt-9143-0';
$btn_des_anular['title']='txt-9141-1';
$btn_des_anular['text']=true;
$btn_des_anular['icons']="fa-check-circle";
$btn_des_anular['tpb']=5;
$btn_des_anular['transictp']=1;

$colores[0]="25AADD";
$colores[1]="E9B230";
$colores[2]="85AB22";
$colores[3]="EDF8FC";
$colores[4]="FFFF00";
$colores[5]="ACE90B";

$maxRes =30;
$maxPag=30;

$Mes_Esp[1][1]="Enero";
$Mes_Esp[1][2]="Febrero";
$Mes_Esp[1][3]="Marzo";
$Mes_Esp[1][4]="Abril";
$Mes_Esp[1][5]="Mayo";
$Mes_Esp[1][6]="Junio";
$Mes_Esp[1][7]="Julio";
$Mes_Esp[1][8]="Agosto";
$Mes_Esp[1][9]="Septiembre";
$Mes_Esp[1][10]="Octubre";
$Mes_Esp[1][11]="Noviembre";
$Mes_Esp[1][12]="Diciembre";

$Mes_Esp[2][1]="January";
$Mes_Esp[2][2]="February";
$Mes_Esp[2][3]="March";
$Mes_Esp[2][4]="April";
$Mes_Esp[2][5]="May";
$Mes_Esp[2][6]="June";
$Mes_Esp[2][7]="July";
$Mes_Esp[2][8]="August";
$Mes_Esp[2][9]="September";
$Mes_Esp[2][10]="October";
$Mes_Esp[2][11]="November";
$Mes_Esp[2][12]="December";

$Mes_Esp[3][1]="Janeiro";
$Mes_Esp[3][2]="Fevereiro";
$Mes_Esp[3][3]="Março";
$Mes_Esp[3][4]="Abril";
$Mes_Esp[3][5]="Maio";
$Mes_Esp[3][6]="June";
$Mes_Esp[3][7]="Julho";
$Mes_Esp[3][8]="Agosto";
$Mes_Esp[3][9]="Setembro";
$Mes_Esp[3][10]="Outubro";
$Mes_Esp[3][11]="Novembro";
$Mes_Esp[3][12]="Dezembro";
$Dias=array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');

/**********************/
/***********************/
$btn_tutorn['clase'][0]="botones_def";
$btn_tutorn['label']='txt-11020-0';
$btn_tutorn['title']='txt-11020-1';
$btn_tutorn['text']=true;
$btn_tutorn['icons']="fa-plus";
$btn_tutorn['tpb']=5;
$btn_tutorn['transictp']=1;

$btn_estado_proyecto['clase'][0]="botones_def";
$btn_estado_proyecto['label']='txt-11022-0';
$btn_estado_proyecto['title']='txt-11022-1';
$btn_estado_proyecto['text']=true;
$btn_estado_proyecto['icons']="fa-plus";
$btn_estado_proyecto['tpb']=5;
$btn_estado_proyecto['transictp']=1;

$btn_estado_publica['clase'][0]="botones_def";
$btn_estado_publica['label']='txt-1371-0';
$btn_estado_publica['title']='txt-1371-1';
$btn_estado_publica['text']=true;
$btn_estado_publica['icons']="fa-plus";
$btn_estado_publica['tpb']=5;
$btn_estado_publica['transictp']=1;

$btn_estado_retira['clase'][0]="botones_def";
$btn_estado_retira['label']='txt-1373-0';
$btn_estado_retira['title']='txt-1373-1';
$btn_estado_retira['text']=true;
$btn_estado_retira['icons']="fa-plus";
$btn_estado_retira['tpb']=5;
$btn_estado_retira['transictp']=1;

$btn_nprograma['clase'][0]="botones_def";
$btn_nprograma['label']='txt-11018-0';
$btn_nprograma['title']='txt-11018-1';
$btn_nprograma['text']=true;
$btn_nprograma['icons']="fa-plus";
$btn_nprograma['tpb']=5;
$btn_nprograma['transictp']=1;

$btn_nproyecto['clase'][0]="botones_def";
$btn_nproyecto['label']='txt-11019-0';
$btn_nproyecto['title']='txt-11019-1';
$btn_nproyecto['text']=true;
$btn_nproyecto['icons']="fa-plus";
$btn_nproyecto['tpb']=5;
$btn_nproyecto['transictp']=1;


/**********************/
/*****DISPONIBLES*****/
$btn_disp_verif['clase'][0]="botones_def";
$btn_disp_verif['label']='txt-1403-0';
$btn_disp_verif['title']='txt-1403-1';
$btn_disp_verif['text']=true;
$btn_disp_verif['icons']="fa-check-circle";
$btn_disp_verif['tpb']=5;
$btn_disp_verif['transictp']=1;

$btn_disp_verif_empl['clase'][0]="botones_def";
$btn_disp_verif_empl['label']='txt-8508-0';
$btn_disp_verif_empl['title']='txt-8508-1';
$btn_disp_verif_empl['text']=true;
$btn_disp_verif_empl['icons']="fa-check-circle";
$btn_disp_verif_empl['tpb']=5;
$btn_disp_verif_empl['transictp']=1;


?>
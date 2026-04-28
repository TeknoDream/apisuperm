var StartPage=function($where,opciones){
	var pag=parseInt($where.attr('data-tpage'))
	if(pag==1||pag==10){
		CargaPage($where,pag);
	}
	else if(pag==2){		
		var opts=GetAttrs($where)
		,	$resumen=$where.find(".resuls")

		if($resumen.attr('data-tipo')=="1") 		var url='/basert'
		else if($resumen.attr('data-tipo')=="2") 	var url='/base'
		else if($resumen.attr('data-tipo')=="3") 	var url='/cabstractb'
		else if($resumen.attr('data-tipo')=="4") 	var url='/creportb'	

		opts._AJAX=1							
		CargaResumen($where,opts,{pagina:url,title:true});				
	}
}
var FilterResumen=function($esto){
    var $where=$esto.parents(".lists").first()
    ,   filter={}    
    ,   filterType=$esto.attr('data-filter')

    if(filterType=='page')         	filter.p=$esto.attr('data-value')
    else if(filterType=='search')   filter.busc=$esto.find('[name="busc"]').val()
    else if(filterType=='button'||filterType=='option'){
        var attrs = $esto[0].attributes;
        for(var i=0;i<attrs.length;i++){
            if(attrs[i].nodeName.substr(0,8)=="data-fl-"){
                var Propiedad=attrs[i].nodeName.substr(5);          
                filter[Propiedad]=attrs[i].value;
            }
        }
    }                              
    FilterList($where,filter);
}

var FilterList=function($where,filter){
    var opts={}
    ,   $resumen=$where.find(".resuls")
    ,   opts=GetAttrs($where)    
    
	opts=$.extend({},opts,filter)
	opts._AJAX=1;

    if($resumen.attr('data-tipo')=="1") 		var url='/basert'
    else if($resumen.attr('data-tipo')=="2") 	var url='/base'
    else if($resumen.attr('data-tipo')=="3") 	var url='/cabstractb'
	else if($resumen.attr('data-tipo')=="4") 	var url='/creportb'
	if(url!=undefined)
    	CargaResumen($where,opts,{pagina:url,title:$resumen.attr('data-tipo')!="4"});
    else
    	console.log('URL no definida')
}

var CargaResumen=function($where,Opciones,showOpt){		
	StartSpin(true);
	$.ajax({
		dataType: 'json',
		type:"GET",
		data:Opciones,
		url: showOpt.pagina,
		success: function(data){							
			
			var $Items=$where.find('.item')
			,	$Buscador=$where.find('.search')
			,	$Bars=$where.find('.bars')
			,	$pages=$where.find('.pages')
			,	$resumen=$where.find(".resuls")

			if(showOpt.title==true) TituloSubir($resumen);
			
			data.buscador=data.buscador==undefined?true:data.buscador

			if(data.tipo==undefined){
				$.when($Items.slideUp('fast')).done(function(){
					$resumen.empty();

					/*ITEMS*/			
					if(data.nItem!=undefined){							
						$.each(data.nItem, function(index, datos){	
							InsertBox(datos,$resumen);			
						})
					}				
							
					//Graficos
					if(data.grafico!=undefined)  InsertGrafic(data.grafico,$resumen);									

					if(data.nItem==undefined&&data.grafico==undefined)
						$resumen.html('<div class="msg-err">'+FindWord('txt-1040-0')+'</div>')	
							
				});
			}
			else{
				$resumen.empty();

				//Graficos
				if(data.grafico!=undefined)  InsertGrafic(data.grafico,$resumen);


				if((data.tipo=="tabla")||(data.tipo=="tablas")||(data.tipo=="nestable")){
					InsertIntoOpfmr(data,$resumen);
				}
				if(data.tipo=="map"){
					$resumen.height($resumen.width())					
					GetCoords(function(dataMap){
						for (var maps in GMaps){
				            if(GMaps[maps].id==data.id){
				            	GMaps.splice(maps, 1);
				            	break;
				            }
				        }
						var $mapsform=$('<form />').appendTo($resumen)
						,	$map=$('<div data-maps="true" class="mapa" data-id="'+data.id+'" />').appendTo($mapsform)
						,	$inmap=$('<div class="m03" data-name="map"/>').appendTo($map)
						,	$lat=$('<input type="hidden" data-name="lat" name="lat" value="'+dataMap.lat+'"/>').appendTo($map)
						,	$lat=$('<input type="hidden" data-name="lon" name="lon" value="'+dataMap.lon+'"/>').appendTo($map)	
						,	$lat=$('<input type="hidden" data-name="zoom" name="zoom" value="'+dataMap.zoom+'"/>').appendTo($map)
						
						,	Map=InitMaps($map,{styles:data.styles});

						if(data.emit!=undefined && typeof PutInMarks == 'function'){
							$map.attr('data-emit',data.emit)
							PutInMarks();
						}
						if(data.markers!=undefined && typeof PutMarkers == 'function'){
							PutMarkers(data.markers);
						}
						

						
					})
				}
			}

			/*Buscador*/									
			if(!data.buscador) 	$Buscador.remove();
			else				InsertSearchFields($Buscador);

			/*BARRA*/
			var hasBar=data.barra==undefined?false:(data.barra.length?true:false)				
			if(hasBar){
				$Bars.empty()
				InsertBar(data.barra,$Bars,data.parAd);
			}				
			else if($Bars.html()==''){
				$Bars.remove()		
				if($Buscador.length) $Buscador.addClass('alone')
			}

			/*PAGINACION*/
			Pages($pages,data.paginacion,data.parAd)	



			if(data.parAd!=undefined){
				$.each(data.parAd,function(ind,dt){
					$where.attr('data-'+ind,dt)
				})
			}
			StartSpin(false);
		},
		error: function (data, status, e){
			StartSpin(false);
			console.log("CR - No se pudo cargar la pagina: "+data.responseText);
		}
	})
}
var CargaPage=function($where,pag){	
	var opts=GetAttrs($where)		
	,	url=pag==1?'/cabstracth':'/creporth'
	opts._AJAX=1
	StartSpin(true);
	$.ajax({
		dataType: 'json',
		type:"GET",
		data:opts,
		url: url,
		success: function(data){
			var _ERR=''
			,	vtipo=pag==1?3:4
			if(data==undefined)	_ERR='txt-1040-0' //75
			if(data.NPERM)		_ERR='txt-MSJ505-0' //505
			if(data.ERR)		_ERR='txt-1040-0'

			if(_ERR!=''){
				$where.html('<div class="msg-err">'+FindWord(_ERR)+'</div>')
			}
			else{
				var $info=$('<div class="info-page dsinline" />').appendTo($where)
				,	$sinfo=$('<div class="sinfo" />').appendTo($info)
				,	$prints=$('<div class="prints dsinline" />').appendTo($where)
				InsertBox(data,$sinfo)

				if(data.cargaex!=undefined){
					$.each(data.cargaex, function(ixex, subex){
						var  SNTitle=FindAsist(subex.nombre)
						,	$SExWhere=$('<aside class="s-ext lists" data-cnf="'+subex.cnf+'" data-scnf="'+subex.scnf+'" data-id="'+subex.id+'"/>').appendTo($info)
						,	$TiExt=$('<h3 class="mtitle col_titles FontTitles" data-filter="button">'+SNTitle+'<i class="fa fa-caret-down"></i></h3>').appendTo($SExWhere)
						,	$wExtRes=$('<div class="w-results" />').appendTo($SExWhere)
						
						$('<section class="nav-bar col_bg02"><section class="search dsinline"></section></section>').appendTo($wExtRes)
						$('<section class="resuls col_bg02" data-tipo="2"></section>').appendTo($wExtRes)
						$('<section class="pages col_bg02"></section>').appendTo($wExtRes)
					})
				}

				$.each(data.menu, function(ixmen, menu){
					var STitle=FindAsist(menu.label)
					,	SubMenu=menu.submenu	
					,	IsDefault=menu.default==1
					,	DefSubMenu=menu.defsubmenu				
					,	$SWhere=$('<section class="s-list lists" data-tinfo="'+menu.tinfo+'" />').appendTo($prints)
					,	$STitle=$('<h3 class="mtitle col_titles FontTitles" data-filter="button">'+STitle+'<i class="fa fa-caret-down"></i></h3>').appendTo($SWhere)
					,	$wRes=$('<div class="w-results" />').appendTo($SWhere)
					,	$SNavBar=$('<section class="nav-bar col_bg02"><section class="search dsinline"></section></section>').appendTo($wRes)
					,	$SResult=$('<section class="resuls col_bg02" data-tipo="'+vtipo+'"></section>').appendTo($wRes)
					,	$SPages=$('<section class="pages col_bg02"></section>').appendTo($wRes)

					$.each(opts,function(op_ix,op_data){
						$SWhere.attr('data-'+op_ix,op_data)
					})
					if(menu.fl!=undefined){
						$.each(menu.fl,function(op_ix,op_data){
							$STitle.attr('data-fl-'+op_ix,op_data)
						})
					}
					if(menu.css!=undefined){
						$.each(menu.css,function(op_ix,op_data){
							$SWhere.css(op_ix,op_data)
						})
					}

					if(DefSubMenu!=undefined) $STitle.attr('data-fl-fil',DefSubMenu)
					
					//CREA SUB MENU SI EXISTE
					if(SubMenu!=undefined){
						var $SNav=$('<nav class="bars dsinline"></nav>').prependTo($SNavBar)
						,	$Box=$('<ul class="buttons" />').appendTo($SNav)
						$.each(SubMenu, function(ixsmen, submenu){
							var SSubTitlee=FindAsist(submenu.label)
							,	$LiBox=$('<li class="li-buttons" />').appendTo($Box)
							,	$button=$('<button class="bt_col1 button" data-filter="button" title="'+STitle+'" data-filter="button" data-fl-fil="'+submenu.fil+'" />').appendTo($LiBox)
							,	$label=$('<span class="blabel light">'+SSubTitlee+'</span>').appendTo($button)
						})
					}

					if(IsDefault||pag==10) $STitle.click()
				});			
			}		
			StartSpin(false);		
		},
		error: function (data, status, e){
			console.log("RS - No se pudo cargar la pagina: "+data.responseText);
			StartSpin(false);
		}		
	});
}
function InsertFields(data,$where){
	$.each(data,function(index,data){
		var $line=$('<div class="line-noti"/>').appendTo($where)
		,	$title=$('<div class="t-noti dsinline medium">'+FindAsist(data.title)+':</div>').appendTo($line)
		,	$data=$('<div class="d-noti dsinline">'+FindAsist(data.data)+'</div>').appendTo($line)
	})

}
/****************************/
/****************************/
/****************************/
/********* CADA CAJA ********/
/****************************/
/****************************/
/****************************/
function InsertBox(data,$where,options,css,clases){	
	var $MBox=$('<section class="item col_bg03" />')
	if(data.idsha!=undefined) $MBox.attr("data-id",data.idsha);
	if(data.id!=undefined) $MBox.attr("data-idsingle",data.id);

	var $titulos=$('<header class="titles" />')
	,	hasImg=false
	,	hasInfo=false
	,	hasFooter=false
	,	hasMBox=false
	,	hasMenu=false

	if(data.titulo.data!=undefined){
		var cod=(data.titulo.cod=='' || data.titulo.cod==undefined)?'':'/?'+data.titulo.cod
		,	$Link=$('<a href="'+data.titulo.pagina+cod+'" data-transictp="'+data.titulo.link+'" >'+FindAsist(data.titulo.data)+'</a>')
		,	$titulo=$('<h3 class="tit" />').append($Link).appendTo($titulos)
	}
	else	
		var $titulo=$('<h3 class="tit">'+FindAsist(data.titulo)+'</h3>').appendTo($titulos);

	if(data.subtitulo!=undefined){
		if(data.subtitulo.data!=undefined){
			var cod=(data.subtitulo.cod=='' || data.subtitulo.cod==undefined)?'':'/?'+data.subtitulo.cod
			,	$Link=$('<a href="'+data.subtitulo.pagina+cod+'" data-transictp="'+data.subtitulo.link+'" >'+FindAsist(data.subtitulo.data)+'</a>')
			,	$subtitulo=$('<h4 class="stit" />').append($Link).appendTo($titulos);
		}
		else 	
			var $subtitulo=$('<h4 class="stit">'+FindAsist(data.subtitulo)+'</h4>').appendTo($titulos);
	}


	if(data.imagen!=undefined){
		var img_src=imgUrl+data.imagen.t02
		,	$Imagen=$(	'<div class="wrapimg col_bg03" data-href="'+imgUrl+data.imagen.big+'" data-target="_blank" data-dinamic="true">'
					+		'<div class="_fix"></div>'
					+		'<div class="_zfix_01 stdImg" style="background-image:url('+img_src+')"></div>'
					+	'</div>')
		hasImg=true;	
	}
	if(data.absimage!=undefined){
		var	$Imagen=$(	'<div class="wrapimg col_bg03" data-href="'+data.absimage+'" data-target="_blank" data-dinamic="true">'
					+		'<div class="_fix"></div>'
					+		'<div class="_zfix_01 stdImg" style="background-image:url('+data.absimage+')"></div>'
					+	'</div>')
		hasImg=true;	
	}

	if(data.info!=undefined||data.imgap!=undefined){	
		var cInfo=data.info!=undefined?Object.keys(data.info).length:0
		,	cImgap=data.imgap!=undefined?Object.keys(data.imgap).length:0
		if(cInfo>0||cImgap>0){
			var $infoad=$('<div class="infoad" />')
			if(cInfo>0) 	InsertData(data.info,$infoad);
			if(cImgap>0) 	InsertImages(data.imgap,$infoad);
			hasInfo=true
		}
	}

	//STATUS
	if(data.estados!=undefined||data.contadores!=undefined){	
		var cEstados=data.estados!=undefined?Object.keys(data.estados).length:0
		,	cContadores=data.contadores!=undefined?Object.keys(data.contadores).length:0
		if(cEstados>0||cContadores>0){
			$estados=$('<div class="estados" />')
			if(cEstados>0)		InsertNotes(data.estados,$estados,2);
			if(cContadores>0) 	InsertNotes(data.contadores,$estados,2);
			hasMBox=true
		}
	}

	//BARS
	if(data.barra!=undefined||data.barrac!=undefined){	
		var cBarra=data.barra!=undefined?Object.keys(data.barra).length:0
		,	cBarrac=data.barrac!=undefined?Object.keys(data.barrac).length:0
		if(cBarra>0||cBarrac>0){
			var $tools=$('<nav class="ibar" />')
			,	$toogle=$(	'<button class="buttonlink bt_col1 button" data-option="toggle">'
	                	+		'<i class="fa fa-navicon"></i>'
	            		+	'</button>')
			,	$stools=$('<div class="stools col_bg01" />')
			
			$stools.append('<div class="chevron col_bg01"><i class="fa fa-chevron-up"></i></div>')

			if(cBarra>0)	InsertBar(data.barra,$stools);
			if(cBarrac>0) 	InsertBar(data.barrac,$stools);

			$tools.append($toogle,$stools)
			hasMenu=true
		}
	}
	if(data.pie!=undefined||data.resinfo!=undefined){	
		var $pie=$('<footer class="ipie" />')
		hasFooter=true;
		if(data.pie!=undefined) 			InsertData(data.pie,$pie);
		else if(data.resinfo!=undefined) 	InsertData(data.resinfo,$pie);

	}
	

	$MBox.append($titulos)
	if(hasImg)		$MBox.append($Imagen)
	if(hasInfo)		$MBox.append($infoad)
	if(hasMBox)		$MBox.append($estados)
	if(hasMenu)		$titulos.append($tools)
	if(hasFooter)	$MBox.append($pie)	

	if(hasImg||hasInfo||hasMBox){
		if(data.type==2) 	$titulos.addClass('col_bg01')
		else				$titulos.addClass('col_titles')
		if(hasFooter) $pie.addClass('col_titles')
	}
	else{
		$titulos.addClass('col_titles')
		$MBox.addClass('min')
		//if(hasFooter) $pie.addClass('col_bg01')
	}
	

	if(hasMBox&&hasInfo)	$infoad.addClass('est')
	if(!hasInfo)			$MBox.addClass('med')

	if(data.deshab==1)
		$MBox.addClass("grayscale");

	options=options==undefined?{}:options
	options.position=options.position==undefined?'append':options.position
	if(options.position=='append')
		$MBox.appendTo($where);
	else if(options.position=='prepend')
		$MBox.prependTo($where);

	return $MBox;
						
}

function InsertNotes(data,$where,tipo){				
	if(data!=undefined){							
		$.each(data, function(index, datos){
			InsertData(datos.contenido,$where);
		});
	}	
}
function InsertImages(data,$where,tipo){
	if(data!=undefined){
		var $Box=$('<div class="linea"></div>');
		$.each(data, function(index, datos){																
			if(datos.data!=''){
				datos.desc=FindAsist(datos.desc);

				if(typeof datos.url === 'object')
					var Link=imgUrl+datos.url.big
				else
					var Link=datos.url

				var $label=('<h4 class="label">'+datos.desc+':</h4>')
				,	$mCont='<div class="cont light"><a href="'+Link+'" target="_blank">'+FindWord('txt-1060-0')+'</a></div>';	

				$Box.append($label,$mCont);							
				InsertData(datos.info,$Box);
			}
		});
		$where.append($Box);
	}
}


var Pages=function($pages,paginacion){
	/*PAGINACION*/
	if(paginacion!=undefined){
		$pages.empty().css('display','block');												
		InsertData(paginacion.first,$pages);
		InsertData(paginacion.prev,$pages);								
		InsertData(paginacion.paginas,$pages);										
		InsertData(paginacion.next,$pages);
		InsertData(paginacion.last,$pages);								
	}
	else $pages.css('display','none');
}
/****************************/
/****************************/
/****************************/
/****************************/
/****************************/
function InsertData(data,$where){
	if(data!=undefined){
		$.each(data, function(index, datos){	
			if((datos.desc!=''&&datos.data!='')||datos.tipo==100){



				datos.desc=FindAsist(datos.desc)
				datos.data=FindAsist(datos.data) 

				//LINEAS
				if(datos.tipo==undefined){
					var $TxtInsert=$('<div class="linea" />')
					,	cod=(datos.cod=='' || datos.cod==undefined)?'':'/?'+datos.cod
					,	descript=String(datos.data)
					,	min=(descript.length>100)
					,	mTit='<h4 class="label">'+datos.desc+':</h4>'

					/*if (min){
						var descinit=descript.substr(0,100)
						descript='<span>'+
									'<span class="_stext">'+descinit+'</span>'+
									'<span class="_scomplete hide">'+datos.data+'</span>'+
									'<span data-more="true">...</span>'+
								'</span>';
					}*/					

					if(datos.link==undefined)	var mCont='<div class="cont light">'+descript+'</div>';
					else						var mCont='<div class="cont light"><a href="'+datos.pagina+cod+'" data-transictp="'+datos.link+'">'+descript+'</a></div>';	
					
					
					if(datos.fecha!=undefined){
						var $sPie=$('<div class="sline dsinline" />').append(mTit,mCont)
						,	$sDate=$('<span class="date  dsinline light">'+datos.fecha+'</span>');

						$TxtInsert.append($sPie,$sDate)
					}
					else
						$TxtInsert.append(mTit,mCont)
				}
				//CAJAS
				else if(datos.tipo==1||datos.tipo==2||datos.tipo==3||datos.tipo==4){					
					var $TxtInsert=$('<p class="linea-box col_bg02" />')
					,	$Titulo=$('<h4 class="label">'+datos.data+'</h4>').appendTo($TxtInsert)
					,	cod=(datos.cod=='' || datos.cod==undefined)?'':'/?'+datos.cod

					if(datos.tipo==1) $Titulo.addClass('col_titles');	
					if(datos.tipo==2) $Titulo.addClass('col_titles2');	
					if(datos.tipo==4) $Titulo.addClass('col_titles3');	
					
					if(datos.med!=undefined) {
						datos.med=FindAsist(datos.med) 
						$TxtInsert.append('<div class="med light col_bg03">'+datos.med+'</div>');
					}
					if(datos.link==undefined&&datos.desc!='')
						var mCont='<div class="cont light">'+datos.desc+'</div>';
					else if(datos.desc!='')
						var mCont='<div class="cont col_titles2"><a href="'+datos.pagina+cod+'" data-transictp="'+datos.link+'">'+datos.desc+'</a></div>';	
					$TxtInsert.append(mCont)
				}	
				//PAGINACION	
				else if(datos.tipo==5||datos.tipo==6){
					if(datos.tipo==5)
						var $TxtInsert=$('<a href="#" class="pag light" data-value="'+datos.pagina+'" data-filter="page">'+datos.name+'</a>');	
					else if(datos.tipo==6)
						var $TxtInsert=$('<span class="pag sel">'+datos.name+'</span>');			
				}
				//TABLAS
				else if(datos.tipo==20){
					var $TxtInsert=$('<div class="linea table" />')
					,	$preTable=$('<div class="ctable" />').appendTo($TxtInsert)
					,	mTit='<h4 class="label bold">'+datos.desc+'</h4>'
					,	$mCont=$('<div class="_stabla" />')
					,	lblbtn=datos.btnlbl==undefined?'txt-1060-0':datos.btnlbl
					,	$ButtonMas=$('<button class="button bt_col1 button" data-carga="tabla">'
								+		'<i class="fa fa-info-circle dsinline bicon"></i><span class="blabel">'+FindWord(lblbtn)+'</span>'
								+	'</button>');
					if(datos.attr!=undefined)						
						$mCont.attr(datos.attr)

					$TxtInsert.append($ButtonMas);
					$preTable.append(mTit,$mCont);
				}
				else if(datos.tipo==100){
					var $TxtInsert=$('<div class="linea sep" />')
					,	mTit='<h4 class="label">'+datos.desc+'</h4>'
					$TxtInsert.append(mTit)

				}



				$where.append($TxtInsert);
			}
		});
	}
}
function TituloSubir($where){
	var $title=$where.parent().siblings('.mtitle')
	if($title.length)
		$('html, body').animate({ scrollTop: $title.offset().top }, { duration: 'slow', easing: 'swing'});
}
var FindAsist=function(pal){
	var txtreturn=''
	if(pal!=null){
		if(typeof pal == 'object'){
			$.each(pal,function(index,data){
				if(String(data).substr(0,4)=='txt-')
					txtreturn=txtreturn+' '+FindWord(data)
				else if(data=='||')
					txtreturn=txtreturn+' <br />';
				else
					txtreturn=txtreturn+' '+data
			})
		}
		else{
			if(String(pal).substr(0,4)=='txt-')
					txtreturn=FindWord(pal)
				else
					txtreturn=pal
		}
	}
	if(typeof txtreturn == 'string')	txtreturn=txtreturn.trim()
	return txtreturn
}

function InsertSearchFields($where){
	if($where.html()==''){
		var $Buscador=$('<section class="search-field col_titles light" />').appendTo($where)
		,	$form=$('<form name="search-form" action="" method="get" data-filter="search"/>').appendTo($Buscador)
		,	$InputBusc=$('<input type="search col_bg03" name="busc"  class="input" value="" placeholder="'+FindWord('txt-1210-0')+'" />').appendTo($form)
		,	$BtnBusc=$('<button class="simple bt_col3 button"><i class="fa fa-search"></i></button>').appendTo($form)
	}		
}
function InsertBar(data,$where,parAd){
	if(data!=undefined){
		$.each(data, function(index, datos){
			var $Box=$('<ul class="buttons" />');
			if(datos.css!=undefined) $Box.css(datos.css)
			if(datos.class!=undefined) $Box.addClass(datos.class)
			$.each(datos.contenido, function(index, botones){	
				var $LiBox=$('<li class="li-buttons" />').appendTo($Box)
				botones.tipobtn.label=FindAsist(botones.tipobtn.label)
				botones.tipobtn.title=FindAsist(botones.tipobtn.title)  


								
				if(botones.tipobtn.tpb==10){
					var $button=$('<button class="bt_col2 button" data-filter="button" title="'+botones.tipobtn.title+'"/>').appendTo($LiBox);
					
					if(botones.tipobtn.icons!=undefined)
						var $icon=$('<i class="fa '+botones.tipobtn.icons+' dsinline bicon" />').appendTo($button)	
					var $label=$('<span class="blabel">'+botones.tipobtn.label+'</span>').appendTo($button)

					if(botones.tipobtn.fl!=undefined){
						var SelButton=true;
						$.each(botones.tipobtn.fl,function(ix,dt){
							var ParAdInx='fl-'+ix;
							if(parAd[ParAdInx]!=dt) SelButton=false;
							$button.attr('data-'+ParAdInx,dt)
						})
					}
					if(SelButton)	$button.addClass("_check");
				}
				else if(botones.tipobtn.tpb==11){
					var $contCuadro=$('<select class="input_bar" data-filter="select" />').appendTo($LiBox)
					,	opts=botones.tipobtn.options;
					$.each(opts, function(indexopt, optiondata){	
						optiondata.label=FindAsist(optiondata.label) 
						var	$opt=$('<option  data-filter="option" />').val(optiondata.value).text(optiondata.label);

						if(optiondata.fl!=undefined){
							$.each(optiondata.fl,function(ix,dt){
								var ParAdInx='fl-'+ix;
								$opt.attr('data-'+ParAdInx,dt)
							})
						}						
						if(optiondata.data!=undefined)	$.each(optiondata.data, function(index, datas){	$opt.attr("data-"+index,datas);});

						$contCuadro.append($opt);

					});
					if(botones.tipobtn.value!=undefined){
						$contCuadro.find('option').removeAttr("selected");
						$contCuadro.find('option[value="'+botones.tipobtn.value+'"]').attr("selected","selected");
					}
				}
				else{
					var cod=(botones.cod=='' || botones.cod==undefined)?'':'/?'+botones.cod
					,	$button=$('<button class="col_menu_t02 button" data-href="'+botones.pagina+cod+'" data-transictp="'+botones.tipobtn.transictp+'" title="'+botones.tipobtn.title+'"/>').appendTo($LiBox);
					if(botones.tipobtn.transictp==3)
						$button.attr({'data-dinamic':'true','data-target':'_blank'})

					if(botones.tipobtn.icons!=undefined)
						var $icon=$('<i class="fa '+botones.tipobtn.icons+' dsinline bicon" />').appendTo($button)
					var	$label=$('<span class="blabel">'+botones.tipobtn.label+'</span>').appendTo($button)
					
				}	
			});
			$where.append($Box);		
		});				
	}
}

function InsertGrafic(data,donde){
	if(data!=undefined){			
		
		$.each(data, function(index, graficos){	
			var tGraf=graficos.type==undefined?'std':graficos.type;
			
			if(tGraf=='std'){
				if(graficos.title!=undefined)
					$('<h3 class="stitulo medium">'+FindAsist(graficos.title)+'</h3>').appendTo(donde);
				if(graficos.jGraf.values!=undefined){
					
					var canvas=$('<div id="'+graficos.config.injectInto+'"></div>').appendTo(donde);
					


					if(graficos.config.orientation=="horizontal")
						canvas.css("height",((graficos.jGraf.values.length)*parseInt(graficos.cnf.height))+"px");
					else 
						canvas.css("height",parseInt(graficos.cnf.height)+"px");
					var info=$('<div id="l'+graficos.config.injectInto+'"></div>').appendTo(donde);		
					
					if(graficos.class.GCss!=undefined){
						$.each(graficos.class.GCss, function(index, tCss){	
							canvas.addClass(tCss);	
						});
					}
					if(graficos.class.LCss!=undefined){
						$.each(graficos.class.LCss, function(index, tCss){	
							info.addClass(tCss);	
						});
					}

								
					donde.append('<div class="fin"></div>');

					
					var ul=$('<ul class="lstGraf" id="l'+graficos.config.injectInto+'"></ul>').appendTo(info);	 
					InsertIntoOpfmr(graficos.infoley,info);	

					
					var barChart = new $jit.BarChart($.extend(true, graficos.config, ObjTip));			 
					barChart.loadJSON(graficos.jGraf);					
					var legend = barChart.getLegend(),listItems = [];
					for(var name in legend) {
					  listItems.push('<div class="_minbox" style=\'background-color:'+ legend[name] +'\'>&nbsp;</div>' + name);
					}
					ul.html('<li>' + listItems.join('</li><li>') + '</li>');	

					
				}
			}
			else if(tGraf=='timeline'){
				if(graficos.title!=undefined)
					$('<h3 class="stitulo medium">'+FindAsist(graficos.title)+'</h3>').appendTo(donde);
				var idNew='T_'+index;
				var ActDiv=$('<div id="'+idNew+'" />');
				

				ActDiv.appendTo(donde);
				var timeplot;

				var _TP_red = new Timeplot.Color('#B9121B')
	            ,	_TP_blue = new Timeplot.Color('#193441')
	            ,	_TP_green = new Timeplot.Color('#468966')
	            ,	_TP_lightGreen = new Timeplot.Color('#5C832F')
	            ,	_TP_gridColor  = new Timeplot.Color('#000000');

	            var timeGeometry = new Timeplot.DefaultTimeGeometry({
	                gridColor: _TP_gridColor,
	                axisLabelsPlacement: "bottom"
	            });

	            var geometry = new Timeplot.DefaultValueGeometry({
	                gridColor: _TP_gridColor,
	                gridType: "short",
	                axisLabelsPlacement: "left",
	                min: 0
	            });

	            var plotInfo = [];
	            var eventSourceXML = [];
	            var eventSourcePlain = [];
	            var dataSourcePlain = [];

	            $.each(graficos.file, function(index, files){   
	            	if(files.type=="xml"){	            
		            	eventSourceXML[index] = new Timeplot.DefaultEventSource();
		            	var _TPlor= Timeplot.createPlotInfo({
		                    id: "Events"+index,
		                    eventSource: eventSourceXML[index],
		                    timeGeometry: timeGeometry,
		                    lineColor: new Timeplot.Color(files.color)
		                })

		                plotInfo.push(_TPlor);
		            }
		            else if(files.type=="text"){				
		            	eventSourcePlain[index] = new Timeplot.DefaultEventSource();
		            	dataSourcePlain[index] = new Timeplot.ColumnSource(eventSourcePlain[index],1);
		            	var _TPlor= Timeplot.createPlotInfo({
		                    id: "Line"+index,
		                    dataSource: dataSourcePlain[index],
		                    timeGeometry: timeGeometry,
		                    valueGeometry: geometry,
		                    lineColor: new Timeplot.Color(files.color),
		                    dotColor: _TP_blue,
		                    showValues: true
		                })
		                plotInfo.push(_TPlor);
		            }
				});

				timeplot = Timeplot.create(document.getElementById(idNew), plotInfo);
	            $.each(graficos.file, function(index, files){   
	            	if(files.type=="xml") 
	            		timeplot.loadXML('/logrep/'+files.name, eventSourceXML[index]);
	            	else if(files.type=="text") 
	            		timeplot.loadText('/logrep/'+files.name, " ", eventSourcePlain[index]);
	            });
	
			}
			else{
				if(graficos.data!=undefined){
					

					if(graficos.data.labels!=undefined){
						$.each(graficos.data.labels,function(l_ix,l_data){
							graficos.data.labels[l_ix]=FindAsist(l_data);
						})
					}

					if(graficos.data!=undefined){
						$.each(graficos.data,function(l_ix,l_data){
							if(l_data.label!=undefined)	l_data.label=FindAsist(l_data.label);
						})
					}

					var $BigBox=$('<div class="bigbox" >').appendTo(donde)
					if(graficos.title!=undefined)
						$('<h3 class="stitulo medium">'+FindAsist(graficos.title)+'</h3>').appendTo($BigBox)
					if(graficos.subtitle!=undefined)
						$('<h4 class="ssubtitle">'+FindAsist(graficos.subtitle)+'</h4>').appendTo($BigBox)

					var $graf=$('<div />').appendTo($BigBox)	
					,	$info=$('<div />').appendTo($BigBox)
					$BigBox.append('<div class="fin" />')
					if(graficos.class.GCss!=undefined){
						$.each(graficos.class.GCss, function(index, tCss){	
							$graf.addClass(tCss);	
						});
					}
					if(graficos.class.LCss!=undefined){
						$.each(graficos.class.LCss, function(index, tCss){	
							$info.addClass(tCss);	
						});
					}
					if(graficos.class.BCss!=undefined){
						$.each(graficos.class.BCss, function(index, tCss){	
							$BigBox.addClass(tCss);	
						});
					}


					var $canvas=$('<canvas />').appendTo($graf)		
					,	ancho=$graf.width()
					,	alto=$graf.width()*(graficos.height==undefined?1:graficos.height);
					$canvas.attr({width:ancho,height:alto})
					if(graficos.id!=undefined){
						$canvas.attr('id',graficos.id)
						$info.attr('id',"l"+graficos.id)
					}												
					InsertIntoOpfmr(graficos.infoley,info);	
					var ctx = $canvas.get(0).getContext("2d");
					var myNewChart = new Chart(ctx)				
					$graf.css("text-align","center")	
					if(tGraf=='Doughnut')
						var ChartShow=myNewChart.Doughnut(graficos.data,graficos.options);
					else if(tGraf=='PolarArea')
						var ChartShow=myNewChart.PolarArea(graficos.data,graficos.options);
					else if(tGraf=='Pie')						
						var ChartShow=myNewChart.Pie(graficos.data,graficos.options);
					else if(tGraf=='timeline2')
						var ChartShow=myNewChart.Line(graficos.data,graficos.options);
					else if(tGraf=='bars')
						var ChartShow=myNewChart.Bar(graficos.data,graficos.options);
					else if(tGraf=='Radar')
						var ChartShow=myNewChart.Radar(graficos.data,graficos.options);

					$info.html(ChartShow.generateLegend());
				}
			}
			
		})												
	}

}
/*********************************************/
/************CREA LAS TABLAS *****************/
/*********************************************/
function InsertIntoOpfmr(data,$where){
	if(data!=undefined){
		var tipo=data.tipo==undefined?'tabla':data.tipo;
		if((tipo=='tablas')&&(data.several!=undefined)){
			$.each(data.several,function(index,tabla){
				InsertTablas(tabla,$where);
			})
		}
		else InsertTablas(data,$where);

	}
}
/***********************************************/
/***************SOLO TABLAS*********************/
/***********************************************/
/***********************************************/
function InsertTablas(data,$where){
	var tipo=data.tipo==undefined?'tabla':data.tipo;
	if(tipo=='tabla'){
		var $contenedor=$('<div />');
		$where.append($contenedor);			
	}
	else 	
		var $contenedor=$where;
	

	if((data.titulo!='')&&(data.titulo!=undefined)){
		var $MensajeSupe=$('<h4 class="h4 subfrm_titulo FontTitles">'+FindAsist(data.titulo)+'</h4>');	
		if(data.resalto!=''&&data.resalto!=undefined) 
			$('<span class="more">'+data.resalto+'</span>').appendTo($MensajeSupe);
		$contenedor.append($MensajeSupe);
	}
	$contenedor.addClass("cont_tabla").css("display",data.display);


	if(data.css!=undefined){
		$.each(data.css, function(index_css, datas_css){
			$contenedor.css(index_css,datas_css);
		});
	}


	if(data.nItem!=undefined){
		if(data.nItem.length>0&&data.titulo!=''){
			/*$MensajeSupe.bind("click",function(event){
				event.preventDefault()
				event.stopPropagation()
				$(this).slideUp('fast');
				$contenedor.slideDown('fast');
			});*/
		}
	}
	if(tipo=='tabla'){	
		var $table=$('<table />');		
		if(data.nItem!=undefined){			
			var $tHead=$('<thead class="col_titles"></thead>')
			var $tBody=$('<tbody class="col_bg01"></tbody>');	
			var $tFooter=$('<tfoot></tfoot>');
			
			InsertIntoOpfrmFila(data.titulos,$tHead);	
			InsertIntoOpfrmFila(data.nItem,$tBody);	
			InsertIntoOpfrmFila(data.footer,$tFooter);	
				
			$table.append($tHead).append($tBody).append($tFooter);
			$contenedor.append($table)
		}
		if(data.attr!=undefined){
			$.each(data.attr, function(index_attr, datos_attr){
				$table.attr(index_attr,FindAsist(datos_attr));
			});
		}
		if(data.data!=undefined){
			$.each(data.data, function(index_data, datos_data){
				$table.attr("data-"+index_data,FindAsist(datos_data));
			});
		}
		if(data.css_tabla!=undefined){
			$.each(data.css_tabla, function(index_css, datas_css){
				$table.css(index_css,datas_css);
			});
		}
	}
	else if(tipo=='nestable'){	
		var $biglist=$('<ol class="dd-list" data-olid="0"/>');		
		$contenedor.addClass("dd").append($biglist);		
		if(data.nItem!=undefined){	
			InsertIntoOpfrmFila(data.nItem,$biglist);				
		}

		if(data.attr!=undefined){
			$.each(data.attr, function(index_attr, datos_attr){
				$biglist.attr(index_attr,datos_attr);
			});
		}
		if(data.data!=undefined){
			$.each(data.data, function(index_data, datos_data){
				$biglist.attr("data-"+index_data,datos_data);
			});
		}
	}
}
/*********************************************/
/************INSERTAR TABLAS *****************/
/*********************************************/
function InsertIntoOpfrmFila(data,tBody,after,before){
	if(data!=undefined){
		$.each(data, function(index, datos){
			var typedata=datos.typedata==undefined?'tr':datos.typedata;
			
			if(typedata=='tr')				var $fila=$("<tr></tr>");
			else if(typedata=='ollist') 	var $fila=$('<div/>');
			
			if(datos.id!=undefined) 		$fila.attr("data-id",datos.id);
			if(datos.order!=undefined) 		$fila.attr("data-order",datos.order);
			if(datos.suborder!=undefined) 	$fila.attr("data-suborder",datos.suborder);

			$.each(datos.cont, function(index, filas){			
				var tsal=filas.link;

				if((filas.tipo=='hidden')){
					var $contCuadro=$('<input type="hidden" name="'+filas.name+'" value="'+filas.value+'"/>');
					if(filas.id!=undefined)  $contCuadro.attr("id",filas.id);
					$contCuadro.appendTo($fila);				
				}
				else{
					if(typedata=='tr'){
						if(tBody[0].tagName=="THEAD")
							var $td=$('<th class="light"></th>');							
						else							
							var $td=$('<td></td>');

						if(filas.width!=undefined){
							if(filas.unid==undefined) 	$td.css("width",filas.width+"%");
							else 						$td.css("width",filas.width+filas.unid);
						}
					}
					else 	
						var $td=$('<div />');

					
					
					if(filas.tipo=='checkbox'||filas.tipo=='radio'){
						var $contCuadro=$('<input type="'+filas.tipo+'" id="'+filas.id+'" name="'+filas.name+'" class="input" value="'+filas.value+'" /><label for="'+filas.id+'"></label>');
						if(filas.focus!=undefined) 	$contCuadro.click(function(){ if($(this).is(":checked")) $("#"+filas.focus).focus();});
						if(filas.checked) 			$contCuadro.attr("checked","checked");
						$td.css("text-align","center");	
					}	
								
					else if(filas.tipo=='button'){					

						var $contCuadro=$('<button class="button bt_col1" />');
						if(filas.name!=undefined)  	$contCuadro.attr("data-name",filas.name);	
						if(filas.value!=undefined)  $contCuadro.attr("data-value",filas.value);	
						if(filas.id!=undefined)  	$contCuadro.attr("id",filas.id);	
						
						var $icon=$('<i class="fa '+filas.icon+' dsinline bicon"></i>').appendTo($contCuadro);
			
						if(filas.data!=undefined)	var EnableBtn=filas.data.enable=='false'?false:true;
						else 						var EnableBtn=true;

						if(EnableBtn)	$contCuadro.css("cursor","pointer");

						else			$contCuadro.css("cursor","default");

						if(filas.label!=""&&filas.label!=undefined){
							filas.label=FindAsist(filas.label)  
							var $label=$('<span class="label blabel">'+FindAsist(filas.label)+'</span>').appendTo($contCuadro);
						}
						$td.css("text-align","center");

						if(filas.link!=undefined){
							var cod=(filas.cod=='' || filas.cod==undefined)?'':'/?'+filas.cod
							$contCuadro.attr("data-transictp",filas.link)
								.attr("data-href",filas.pagina+cod);							
						}
					}
					else if (filas.tipo=='icon'){
						var $contCuadro=$('<i class="fa '+filas.icon+'" />');
					}
					else if((filas.tipo=='text')||(filas.tipo=='date')||(filas.tipo=='std_date')||(filas.tipo=='time')||(filas.tipo=='datepicker')||(filas.tipo=='colorpicker')||(filas.tipo=='std_number')){
						var $contCuadro=$('<input type="text" name="'+filas.name+'" class="input" value="'+filas.value+'" />');
						if(filas.id!=undefined)  			$contCuadro.attr("id",filas.id);
						if(filas.placeholder!=undefined){
							filas.placeholder=FindAsist(filas.placeholder) 
							$contCuadro.attr("placeholder",filas.placeholder);	
						}			
						if(filas.tipo=='date') 				$contCuadro.datepicker({changeMonth: true,changeYear: true});
						else if(filas.tipo=='time') 		$contCuadro.simpletimepicker();
						else if(filas.tipo=='std_date') 	$contCuadro.attr("type","date");
						else if(filas.tipo=='std_datetime') $contCuadro.attr("type","datetime");
						else if(filas.tipo=='std_time') 	$contCuadro.attr("type","time");
						else if(filas.tipo=='std_tel') 		$contCuadro.attr("type","tel");
						else if(filas.tipo=='str_email') 	$contCuadro.attr("type","email");
						else if(filas.tipo=='std_number') 	$contCuadro.attr("type","number");
						
					}
					else if(filas.tipo=='stars'){
						var $contCuadro=$('<div class="_stars" />')
						,	min=filas.min
						,	max=filas.max
						,	value=filas.value
						,	$input=$('<input type="hidden" name="'+filas.name+'" value="'+value+'" />  ').appendTo($contCuadro)
						if(filas.id!=undefined)  	$contCuadro.attr("id",filas.id);
						if(filas.tooltip) 			$contCuadro.attr("title",filas.tooltip);
		
						for (i = min; i <= max; i++) {
							if(i==value) var Class='_FULL'
							else 			var Class='_EMPTY'
							$contCuadro.append('<i data-action="rating" data-value="'+i+'" class="fa fa-star '+Class+' dsinline" data-target="'+filas.name+'"></i>')
						}
					}
					else if(filas.tipo=='circle'){
						var $contCuadro=$('<div class="circle_01"/>');
						$contCuadro.css({margin:"auto",height:'23px',width:'23px','border-width':'1px','border-style':'solid'})
						if(filas.circle.css!=undefined){
							$.each(filas.circle.css, function(index, css){	
								$contCuadro.css(index,css);
							});	
						}
					}
					else if((filas.tipo=='textarea')){
						var $contCuadro=$('<textarea name="'+filas.name+'" class="input redondeado_01 borde_1">'+filas.value+'</textarea>');
						if(filas.id!=undefined)  			$contCuadro.attr("id",filas.id);
						if(filas.placeholder!=undefined) 	$contCuadro.attr("placeholder",filas.placeholder);				
						
					}
					else if(filas.tipo=='combobox'){
						var $contCuadro=$('<select name="'+filas.name+'" class="input" data-selected="'+filas.value+'" />');
						if(filas.id!=undefined)  			$contCuadro.attr("id",filas.id);
						if(filas.placeholder!=undefined) 	$contCuadro.attr("placeholder",filas.placeholder);
						
						var countopts=filas.options.length;
						$.each(filas.options, function(indexopt, optiondata){	
							optiondata.label=FindAsist(optiondata.label) 

							var $opt=$("<option />").val(optiondata.value).text(optiondata.label);
							$contCuadro.append($opt);
							if(optiondata.data!=undefined)	$.each(optiondata.data, function(index, datas){	$opt.attr("data-"+index,datas);});					
							if (!--countopts){
								if($contCuadro.attr("data-selected")!=undefined){
									$contCuadro.find('option').removeAttr("selected");
									$contCuadro.find('option[value="'+$contCuadro.attr("data-selected")+'"]').attr("selected","selected");
								}
							}
						});
					}
					else if((filas.tipo=='image')){
						var $contCuadro=$('<img class="imagen_src" src="'+filas.value+'" />');		
					}	
					else if((filas.tipo=='box')){
						var $contCuadro=$('<div />')
						,	options={tipo:'div'}
						,	css={};
						InsertBox(filas.content,$contCuadro,options,css,filas.class);
					}
					else if((filas.tipo=='table')){
						var $contCuadro=$('<div />');
						InsertTablas(filas.value,$contCuadro);						
					}		 
					else{
						filas.label=FindAsist(filas.label) 
						if(typeof(filas.label)=="object"&&filas.label!=null&&filas.label!=undefined)	
							var label=filas.label.join("<br />");
						else if(filas.label!=null)									
							var label=filas.label;
						else
							var label=""

						if(filas.link!=undefined){			
							var cod=(filas.cod=='' || filas.cod==undefined)?'':'/?'+filas.cod				
							,	$contCuadro=$('<a href="'+filas.pagina+cod+'" data-transictp="'+filas.link+'" >'+label+'</a>');
						}
						else 
							var $contCuadro=$('<span>'+label+'</span>')
						if(filas.id!=undefined) $contCuadro.attr("id",filas.id);
					}

					
					
					if(filas.title_show){
						filas.title=FindAsist(filas.title) 
						var $sTitle=$('<h5 class="h5">'+filas.title+'</h5>');
						$td.prepend($sTitle);
					}
					
					if(filas.class!=undefined){
						$.each(filas.class,function(index,data){
							$td.addClass(data);
						});
					}
					
					if(filas.css!=undefined)
						$td.css(filas.css);
					
					if(filas.underline) 		$contCuadro.css("text-decoration","underline").css("cursor","pointer");
					if(filas.locked)			$contCuadro.css("border-style","none").attr("readonly","readonly");
					if(filas.title!=undefined)	$contCuadro.attr("title",filas.title);
					
					if(filas.hide!=undefined){
						var $addHide=$('<input type="hidden" />');
						$.each(filas.hide, function(index, atrib){	
							$addHide.attr(index,atrib);
						});	
						$td.html($addHide);
					}
					if(filas.colspan!=undefined) $td.attr("colspan",filas.colspan); 	
					if(filas.rowspan!=undefined) $td.attr("rowspan",filas.rowspan); 	
					
					if(typedata=='tr')			
						$td.append($contCuadro).appendTo($fila);
					else if(typedata=='ollist'){
						if(filas.overlabel!=""&&filas.overlabel!=undefined){
							var $label=$('<label class="frm-label">'+FindAsist(filas.overlabel)+'</label>');
							$td.append($label);
						}
						$td.append($contCuadro);
						if(filas.sepcol!=undefined){
							var $col=$fila.find('.'+filas.sepcol).first()
							if($col.length==0)	$col=$('<div class="'+filas.sepcol+'" />')							
							$col.append($td).appendTo($fila);
						}
						else
							$td.appendTo($fila);
					}
					if(filas.functions!=undefined){
						$.each(filas.functions,function(index,_function){
							if(_function.name=='TimeUp'||_function.name=='TimeDown'){
								var _IntDate=new Date(_function.init+_function.tz)								
								if(_function.alarm!=undefined){
									var Sound=('<audio id="ID'+_function.id+'">'
		                                    +    '<source src="/sound/'+_function.alarm.type+'.ogg" type="audio/ogg">'
		                                    +    '<source src="/sound/'+_function.alarm.type+'.mp3" type="audio/mpeg">'
		                                    +'</audio>')
									,	ActiveAlarm=false
									$fila.append(Sound)
								}
							}
							if(_function.name=='TimeUp'){
								Intervals[_function.id]=setInterval(
														function(){ 
															var _Now=new Date()
															,	Diff=DateDiff(_Now,_IntDate)	
															,	milliseconds,seconds,minutes,hours,diff=Diff										
															
															diff=(diff-(seconds=diff%60))/60;
															diff=(diff-(minutes=diff%60))/60;
															hours=diff;//days=(diff-(hours=diff%24))/24;
															$td.text((hours<10?'0':'')+hours
																+':'+(minutes<10?'0':'')+minutes
																+':'+(seconds<10?'0':'')+seconds)
															if(_function.alarm!=undefined&&!ActiveAlarm){
																if(Diff>=_function.alarm.time){
																	ActiveAlarm=true
																	$fila.find('#ID'+_function.id)[0].play()
																}
									                        }
														},1000)
							}
							else if(_function.name=='TimeDown'){
								
								Intervals[_function.id]=setInterval(
														function(){ 
															var _Now=new Date()
															,	Diff=DateDiff(_IntDate,_Now)	
															,	milliseconds,seconds,minutes,hours,diff=Diff										
															
															diff=(diff-(seconds=diff%60))/60;
															diff=(diff-(minutes=diff%60))/60;
															hours=diff;//days=(diff-(hours=diff%24))/24;
															if(hours>=0){
																$td.text((hours<10?'0':'')+hours
																	+':'+(minutes<10?'0':'')+minutes
																	+':'+(seconds<10?'0':'')+seconds)
																if(_function.alarm!=undefined&&!ActiveAlarm){
																	if(Diff<=_function.alarm.time){
																		ActiveAlarm=true
																		$fila.find('#ID'+_function.id)[0].play()
																	}
										                        }
										                    }
										                    else
										                    	clearInterval(this);
														},1000)
							}
						});
					}
				}
				if(filas.data!=undefined){
					$.each(filas.data, function(index, datad){	
						$contCuadro.attr("data-"+index,datad);
					});	
				}	
				if(filas.attr!=undefined){
					$.each(filas.attr, function(index, datad){	
						$contCuadro.attr(index,FindAsist(datad));
					});
				}
				if(filas.tipo=='colorpicker'){
					var datapicket_control={
							control: filas.data.control || 'hue',
							defaultValue: filas.data.defaultValue || '',
							inline: filas.data.inline === 'true',
							letterCase: filas.data.letterCase || 'lowercase',
							opacity: filas.data.opacity,
							position:filas.data.position || 'bottom left',
							change: function(hex, opacity) {
								if( !hex ) return;
								if( opacity ) hex += ', ' + opacity;								
							},
							theme: 'bootstrap'}
					$contCuadro.minicolors(datapicket_control);	
				}	
			});	
			if(typedata=='tr'){
				if(after!=undefined) 	$fila.insertAfter(after);
				if(before!=undefined) 	$fila.insertBefore(before);
				else{
					if(datos.order!=undefined){
						var order_t=datos.order_t==undefined?1:datos.order_t
						//
						var $Insertar=tBody.find("tr").filter(function (index) {						
							return (parseInt($(this).attr("data-order"))>parseInt(datos.order));
						}).last();
						
						if(datos.suborder==0){
							if($Insertar.length==0) 	$fila.prependTo(tBody);
							else{
								if(order_t==1)		$fila.insertAfter($Insertar);
								else if(order_t==2)	$fila.insertBefore($Insertar);
							}
						}
						else{
							var PreId=tBody.find('tr[data-id="'+datos.id+'"]');
							var PostInsert=PreId.filter(function (index) {						
								return (parseInt(datos.suborder)>parseInt($(this).attr("data-suborder")));
							}).last();
							if(PostInsert.length!=0) 	$fila.insertAfter(PostInsert);
						}
					}
					else tBody.append($fila);
				}
			}
			else{
				var ddsquare=datos.ddsquare!=false?true:false;
				$fila.append('<div class="fin" />')

				var $olChild=$('<ol class="dd-list" data-olid="'+datos.me+'"/>')
				,	$ol_li=$('<li class="dd-item" data-id="'+datos.me+'" />')

				if(ddsquare){
					$fila.addClass("dd3-content").addClass("borde_1");
					$ol_li.append('<div class="dd-handle dd3-handle"></div>').append($fila).append($olChild);
				}
				else{
					$fila.addClass("dd-content");
					$ol_li.append($fila).append($olChild);
				}
				tBody.append($ol_li);

			}
			if(datos.css!=undefined)
				$fila.css(datos.css);
			if(datos.data!=undefined){
				$.each(datos.data, function(index, data){	
					$fila.attr("data-"+index,data);
				});	
			}
		});		
		OlOrden(data,tBody);		
	}
}
function OlOrden(data,tBody){
	var Cont=data.length
	,	BigP=tBody.parent();
	if((Cont==0)&&(BigP.attr('data-table')=="nestable")){
		var options={};
		if(BigP.attr('data-maxDepth')!=undefined) options.maxDepth=BigP.attr('data-maxDepth');
		BigP.nestable(options);
	}
	$.each(data, function(index, datos){
		var typedata=datos.typedata==undefined?'tr':datos.typedata;
		if(typedata=='ollist'){
			var ol_li_old=tBody.find('li[data-id="'+datos.me+'"]')
			,	ol_li=ol_li_old.clone();
			ol_li_old.remove();
			var olParent=tBody.find('[data-olid="'+datos.parent+'"]');
			if(olParent.length==0)	tBody.append(ol_li);
			else 					olParent.append(ol_li);

		}
		if((!--Cont)&&(BigP.attr('data-table')=="nestable")){
			var options={};
			if(BigP.attr('data-maxDepth')!=undefined) options.maxDepth=BigP.attr('data-maxDepth');
			BigP.nestable(options);
		}
	});
}

var OpenForm=function(obj,callBack){
    var href=obj.value
    ,   $bwrap=$(".bwrap")    
    ,   args=obj.parameters
    ,   $hideform=$("#over_header")
    ,   $overall=$hideform.next('.loader_all')
    ,   $cforms=$("#x_forms .cforms")


    args._AJAX=1;             
    
    StartSpin(true);
    $.ajax({ 
        url: href, 
        data:args,
        success: function(data){   
            StartSpin(false);

            var $data=$(data)
            if($data.length==1) var $forms=$data;
            else 				var $forms=$data.siblings('form')       

            $bwrap.addClass('__fixw');
            $cforms.append($forms)
            
            FillBlanks($cforms,function(){  
                $hideform.fadeIn(150,function(){
                    $overall.fadeIn(150,function() { 
                        mdMask($forms); 
                        Espacios(); 
                        ActCSSJs($data);
                        if(typeof onloadCaptcha == 'function') onloadCaptcha();
                    })
                }); 
            }); 
        }
    });     
} 
var UploadForm=function($form,event,callBack){

   var verificado=ValForm($form)
    ,   tipofrm=$form.attr('enctype')

    if(!verificado){
        event.stopPropagation();
        event.preventDefault();
    }
    else if(tipofrm!='multipart/form-data'&&verificado){
        event.stopPropagation();
        event.preventDefault();

        var $message=$form.find(".message")
        ,   method=$form.attr('method')
        ,   action=$form.attr('action')
        ,   inline=$form.attr('data-inline')
        ,   formser=$form.serializeObject() 
        ,   config={dir:action,type:method}
        ,   $x=$form.find('[data-close="form"]')
        ,	$hasImg=$form.find('[type="file"]').first()
        ,	$hasSerialize=$form.find('[data-serialize="true"]')
        ,	$hasLocalStorage=$form.find("[data-localstorage]")
 
        //REVISAR
        $hasSerialize.each(function(index,data){
			RecNestable($(this).children('ol.dd-list')); 
			OrderNestable($(this).children('ol.dd-list')); 
		});
		$hasLocalStorage.each(function(data,index){
            var f_localstorage=$(this).attr('data-localstorage')
            ,   SCart_STR=localStorage.getItem(f_localstorage)
            ,   Name=$(this).attr('name')
            if(SCart_STR!=null){
                var SCart_OBJ=JSON.parse(SCart_STR)
                formser[Name]=SCart_OBJ;
            }
        }) 

        config.Files=$form.find('[type="file"]')
        if(inline!=undefined) 	formser.inline=inline;
 
        StartSpin(true);
        form_en(0);
        if($message.is(":visible")) $message.slideUp(80)
   
        jSONInfo(config,formser,function(data){          
            StartSpin(false);
            console.log(data.hide);
            if(data!=null){

            	/* Re Carga */

            	if(data.auto.cnf!=undefined&&data.status.close){
            		var _cnf=data.auto.cnf
            		if( Object.prototype.toString.call(_cnf) === '[object Array]' )
                		for(var cnf in _cnf){
                			if($('[data-cnf="'+_cnf[cnf]+'"]').length){
                				var $List=$('[data-cnf="'+_cnf[cnf]+'"]');
				            	$List.each(function(){
				            		FilterList($(this),{});
				            	})  
                			}
            			}
            		else{
            			var $List=$('[data-cnf="'+_cnf+'"]');
		            	$List.each(function(){
		            		FilterList($(this),{});
		            	})    	
		            }
	            }

            	/* CAPTCHA */
                if(data.status.captcha){
                    var $Captcha=$form.find('[data-captcha]')
                    ,   captcha_id=$Captcha.attr('data-captchaid')
                    ,   $ButtonSend=$form.find('[data-emergente="send"]')
                    grecaptcha.reset(captcha_id)
                    $ButtonSend.prop('disabled',true).attr('data-disabled','force')
                }
            		
                if(data.mensaje.length!=0)          var msg_tp=1;
                else if(data.message_esp.length!=0) var msg_tp=2;

                if(data.status.reload)  $x.attr('data-reload','true');
       

                if(msg_tp==1){
                    $message.empty();
                    $.each(data.mensaje,function(index,msg){
                        $('<div class="text msgb">'+FindAsist(msg)+'</div>').appendTo($message)
                    })
                    if(data.status.error)   $message.addClass('colf04')
                    else                    $message.addClass('colf07')
                }
            	/********************/
                /*** Nuew Window ***/
                /********************/
                if(data.status.go_new!=undefined){
	                var go_new=data.status.go_new
	                if(go_new.length){
	                	$.each(go_new,function(ix,dt){
	                		var win = window.open(dt.url, dt.type);
	  						//win.focus();
	                	})
	                }
	            }


                /********************/
                /***LOCAL STORAGE***/
                /********************/
                $.each(data.localstorage,function(index,data){                    
                    var localstring=localStorage.getItem(index)
                    ,   localobject=[]
                    if(localstring!=null) localobject=JSON.parse(localstring)
                    localobject.push(data)
                    localStorage.setItem(index,JSON.stringify(localobject))
                })
                if(data.status.words)   localStorage.removeItem('versions'); 
                $.each(data.remove,function(inx,remove){
                    localStorage.removeItem(remove); 
                })

                if(msg_tp==1&&$message.length){
                    var delayt=(data.status.close)?1500:3000;

                    ShowFormMSG($form,true,function(){                     	
                    	setTimeout(function(){
                    		ShowFormMSG($form,false,function(){
                    			if(!data.status.reload) $('[data-key]').attr('data-key',data.status.key)
                        		if(data.status.close)   CloseForm($form,data.status.reload); 
                        		if(typeof callBack == 'function') callBack(data); 
                        		form_en(1)
                    		})
                    	},delayt)
                    })
                }
                else if(msg_tp==1&&!$message.length){
                    if(!data.status.reload) $('[data-key]').attr('data-key',data.status.key)
                    if(data.status.close)   CloseForm($form,data.status.reload);
                    if(typeof callBack == 'function') callBack(data); 
                }

            }     
            else{
                form_en(0);
                CloseForm($form,true);
            }           
        });
    }
}
var ShowFormMSG=function($form,show,callBack){
	var $message=$form.find('.message')
	,	$all=$message.siblings()

	if(show){
		$all.addClass('blur',180)
		$message.css({'opacity':0}).slideDown(180,function(){
			$(this).animate({'opacity':1},180,function(){
				if(typeof callBack == 'function') callBack(); 
			})
		})
	}
	else{
		$all.removeClass('blur',180)
		$message.animate({'opacity':0},180,function(){
			$(this).slideUp(180,function(){
				if(typeof callBack == 'function') callBack(); 
			})
		})
	}
}
var CloseForm=function($forms,reload){
    reload=reload==undefined?false:reload;
    
    var $loader_all=$forms.parents('[data-cform="true"]')
    ,   $over_header=$loader_all.prev()
    ,   $bwrap=$(".bwrap")
    ,   Inline=$forms.attr('data-direct')

    if(Inline=="1"){
        $forms.slideUp(100,function(){
            if(reload)  window.location.reload()
        })
    }
    else{
    	if($loader_all.length&&$over_header.length){
	        $loader_all.fadeOut(100,function(){
	            $over_header.fadeOut(100,function(){
	                if(!reload){
	                    if($forms.attr('data-delete')!='true') $forms.remove();            
	                    $bwrap.removeClass('__fixw');
	                    Espacios();
	                }
	                else window.location.reload()
	            })  
	        });
		}
		else{
			if(reload) window.location.reload()
		}

    }
}




function BindForms(){
	/**************************************************/
	/**************************************************/
	/**************************************************/
	$("body").delegate('[data-close="form"]',"click",function(event){
        event.stopPropagation();
        event.preventDefault();
        var $form=$(this).parents('form')
        ,   reload=$(this).attr('data-reload')=='true'
        CloseForm($form,reload);
    });

    $("body").delegate('form:not([name="search-form"],[target="_blank"],[id^=jUploadForm])','submit',function(event){
        var $form=$(this)
        UploadForm($form,event)
    });

    $("body").delegate('.canvas','mouseup',function(event){
    	var $esto=$(this)
    	,	$pin=$esto.find('.pin')
    	,	height=$esto.height()//attr('data-height')
		,	width=$esto.width()//attr('data-width')
    	$pin.css({top:event.offsetY-7.5,left:event.offsetX-7.5})
    	$pin.find('[name="y"]').val(((event.offsetY-7.5)/height)*100)
    	$pin.find('[name="x"]').val(((event.offsetX-7.5)/width)*100)
    });
    $("body").delegate('.pin','mouseup',function(event){
    	event.stopPropagation();
        event.preventDefault();
    });


	$("body").delegate(':file','change',function(){
		var $esto=$(this)
		,	file = this.files[0]
		,	$form=$esto.parents("form")
		,	error=false
		,	IdMsg=''
		,	cTipoF=$esto.attr("data-type")
		,	_tFile=cTipoF==undefined?0:parseInt(cTipoF);		
		
		var $msgBar=$esto.next('.alert');	
		if(!$msgBar.length)	$msgBar=$('<div class="alert colf04 hide"></div>').insertAfter($esto);	

		var tam_file=[1,20000000]
		,	name = file.name
		, 	size = file.size
		, 	type = file.type;			
		
		
		if((size>tam_file[1])||(size<tam_file[0])){
			error=true;
			IdMsg='txt-MSJ2-0';
		}

		if($.inArray(type,_exts[0])>=0){						
			if(window.FileReader){
				var reader = new FileReader()
				,	$fieldset=$esto.parents('.fieldset')
				,	$stdImg=$fieldset.find('.wrapimg .stdImg')
				reader.onloadend = function(e){
					$stdImg.css("background-image",'url('+e.target.result+')');
				};
				reader.readAsDataURL(file);
			}
		}

		if(error){
			$msgBar.text(FindWord(IdMsg)).slideDown('fast');
			$esto.val('');
		}
		else $msgBar.empty().slideUp('fast');
	});	
	$("body").delegate('[data-autocomplete="true"]',"focus",function(e){
		var $esto=$(this);


		$esto.autocomplete({
			source: function( request, response ) {	

				var Total=!($esto.attr('data-total')=='false')
				,	form=$esto.parents("form").first()
				,	formser=form.serializeObject()
				,	AtrData=GetAttrs($esto)
				,	sendform = Total?$.extend({}, formser, AtrData):AtrData

				sendform.term=request.term
				var	lastXhr = $.getJSON( "/autocomplete",sendform, 
						function( data, status, xhr ) {
							response( data );
						}).fail(function( jqxhr, textStatus, error ) {
							var err = textStatus + ', ' + jqxhr.responseText;
							console.log( "AUTO: " + err);
							}
						);
			},
			close:	function(event, ui)	{	$esto.removeClass("searching")},
			search: function(event, ui) { 	$esto.addClass("searching")},
			select: function(event, ui) {				
				if(typeof(ui)==="object"){						
					if($esto.attr('data-table')!=undefined){		
						event.stopPropagation();
						event.preventDefault();								
						var $Donde=$("#"+$esto.attr("data-table"));
						  
						if($Donde.attr("data-table")=="nestable")	InsertIntoOpfrmFila(ui,$Donde);
						else{							
							if($Donde.length!=0)	InsertIntoOpfrmFila(ui,$Donde.find('tbody'));
						}
						$esto.val("");
					}
					else if($esto.attr('data-box')!=undefined){
						event.stopPropagation();
						event.preventDefault();	
						var $rta=$('[data-rtaauto="'+$esto.attr('data-box')+'"]')
							$rval=$('[name="'+$esto.attr('data-idpass')+'"]')
						$rval.val(ui.item.id);
						$rta.addClass("sel");
						$esto.val("");  
						AsignarJSON(ui.item,$rta);						
					}
					else AsignarJSON(ui.item);
				}
			}
		});
	}).delegate('[data-autocomplete="true"]',"blur",function(e){
		$(this).removeClass("searching")
	});

	$("body").delegate('[data-autocomplete="lector"]',"keypress",function(event){
		console.log(event.which);
		
		if(event.which==13){
			event.stopPropagation();
            event.preventDefault();

			var $esto=$(this)
			,	AtrData=GetAttrs($esto)
			,	request={'lector':$(this).val()}
			,	sendform = $.extend({}, AtrData,request)
			
			$.getJSON( "/autocomplete",sendform,function(data,status,xhr ) {
				if(data.length){
					AsignarJSON(data[0]);
					$esto.val('')
				}
				

			});
		}
	})


	$("body").delegate('[data-autocomplete="button"], [data-autocomplete="option"]',"click",function(e){
		if($(this).attr('data-autocomplete')!='option') e.preventDefault();
		var esto=$(this)
		,	Total=!(esto.attr('data-total')=='false')
		,	form=esto.parents("form").first()
		,	formser=form.serializeObject()
		,	AtrData=GetAttrs(esto)
		,	sendform = Total?$.extend({}, formser, AtrData):AtrData
		lastXhr = $.getJSON( "/autocomplete",sendform, 
		function( data, status, xhr ) {
			if(typeof(data)==="object"){
				if(data.tables!=undefined){
					$.each(data.tables,function(index,data){
						var Donde=$("#"+index).find("tbody");
						Donde=Donde.length==0?$("#"+index):Donde;
						if(Donde.length!=0){
							if(data.clear)	Donde.empty()
							InsertIntoOpfrmFila(data.data,Donde);
						}
					})
				}
				else{
					if(esto.attr('data-table')!=undefined){ 
						var Donde=$("#"+esto.attr("data-table")).find("tbody");
						Donde=Donde.length==0?$("#"+esto.attr("data-table")):Donde;
						if(Donde.length!=0){
							if(esto.attr("data-clear")=="true")	Donde.empty()
							InsertIntoOpfrmFila(data,Donde);
						}
					}
					else if($(this).attr('data-box')!=undefined){
						var rta=$('[data-rtaauto="'+$(this).attr('data-box')+'"]');
						$('[name="'+$(this).attr('data-idpass')+'"]').val(data.item.id);
						rta.switchClass("NoSEL","col_bg02");
						AsignarJSON(data.item,rta);						
					}
					else AsignarJSON(data.item);
				}
			}
		})
		.fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ', ' + jqxhr.responseText;
			console.log( "AUTO: " + err);
		});
	});

	$("body").delegate('[data-carga="button"]',"click",function(e){
		e.preventDefault();
		var esto=$(this);
		var AtrData=GetAttrs(esto);
		var form=esto.parents("form").first();
		var Donde=$("#"+esto.attr("data-tableid"));

		$.each(AtrData,function(index,data){
			if(index!="carga") Donde.attr("data-"+index,data);
		})

		if(Donde.attr("data-delete")!=undefined){
			var $DelButtons=Donde.find('[data-name^='+Donde.attr("data-delete")+']');
			var count=$DelButtons.length;
			if($DelButtons.length==0) DataCarga(Donde,form);
			else{
				$DelButtons.each(function(index,data){


					var ContType=Donde.attr("data-table");
					ContType=ContType==undefined?'tabla':ContType;

					if(ContType=='tabla') 			var fila=$(this).parents("tr").first();
					else if(ContType=='nestable')	var fila=$(this).parents('li').first();

					if($(this).attr("data-delverif")==undefined) var Estados=$(this).attr('data-delvalue');
					else{
						var Estado=fila.find('[name^='+$(this).attr("data-delverif")+']');
						var Estados=Estado.val();
					}

					if(Estados==20) fila.remove();
					else{
						var hItem=fila.find('[name='+$(this).attr("data-delhab")+'_'+$(this).attr("data-value")+']');
						var HabFlag=hItem.length?hItem.val():($(this).hasClass("colf04")?1:0);
						if(HabFlag==0) DeleteTr($(this));

					}
					if(!--count) DataCarga(Donde,form);
				});
			}
		}
		else 	DataCarga(Donde,form);
		
	});


	$("body").delegate('[data-sel="true"]','click',function(){
		var $target=$('[data-id="'+$(this).attr("data-target")+'"]');
		if($(this).attr("checked")=='checked')	$target.find(":checkbox").attr("checked", 'checked');
		else 									$target.find(":checkbox").removeAttr('checked');
	});
	//INSERTA EN TABLA
	$("body").delegate('[data-accgroup="modify"]','click',function(e){
		e.preventDefault();
		var esto=$(this);
		var Contain=$('[data-boxgroup="'+esto.attr("data-group")+'"]');
		if (ValForm(Contain.parents('.contform'))){			
			var Table=$('#'+Contain.attr("data-table"));			
			ModifyFrmTr(Contain,function(formset,isNew){				
				var Data=[];
				Data[0]={cont:formset};
				if(!isNew){
					var FlagEl=Table.find('[data-flag="1"]');
					var Antes=FlagEl.prev('tr').first();
					var Despues=FlagEl.next('tr').first()	
					
					if(Despues.length) 	InsertIntoOpfrmFila(Data,Table.find("tbody"),undefined,Despues);
					else 				InsertIntoOpfrmFila(Data,Table.find("tbody"),Antes);
					FlagEl.remove();
					Table.find("._over").remove();
					Table.find("tr").not(FlagEl).animate({opacity:1},'slow');
				}
				else InsertIntoOpfrmFila(Data,Table.find("tbody"));
				HideFrmTr(esto);
			});
			
			
			
		}
	});
	$("body").delegate('[data-accgroup="cancel"]','click',function(e){
		e.preventDefault();
		var esto=$(this);		
		HideFrmTr(esto);		
	});
	$("body").delegate('[data-accgroup="new"]','click',function(e){
		e.preventDefault();
		var esto=$(this);
		if(esto.css("opacity")==1){
			var Contain=$('[data-boxgroup="'+esto.attr("data-group")+'"]');	
			var Table=$('#'+Contain.attr("data-table"));
			var OverPass=$('<div class="_over" />');
			Table.css("overflow","hidden").css("position","relative").prepend(OverPass);
			Table.find("tr").animate({opacity:0.1},'slow');	
			
			ShowFrmTr(esto)
		}
		
	});
	//
	$("body").delegate('[data-autocombo="true"]',"change",function(){ 
		var esto=$(this);
		var DivCarga=$('[data-id="'+esto.attr("data-content")+'"]');
		DivCarga.each(function(index,value){
			if(esto.attr("data-total")=="true"){
				var form=$(this).parents("form").first();
				var formser=form.serializeObject();
				var AtrData=GetAttrs(DivCarga);
				var sendform = $.extend({}, formser,AtrData);
			}
			else{
				$(this).attr("data-"+esto.attr("name"),esto.val());
				var sendform = GetAttrs($(this));
			}			
			BuscarOtros($(this),$(this),sendform);  

			var AutoDatas=$('[data-autodata="'+esto.attr("name")+'"]');
			if(AutoDatas.length!=0){
				AutoDatas.each(function(index,value){
					$(this).attr("data-"+esto.attr("name"),esto.val());
				});
			}
		})
	});

	//LLENAR COMBO AUTOMATICAMENTE
	$("body").delegate('[data-fillcombo="true"]',"change",function(){ 
		var esto=$(this);
		var ComboCarga=$('[data-id="'+esto.attr("data-combo")+'"]');
		var form=$(this).parents("form").first();
		var formser={};//form.serializeObject();
		var AtrData=GetAttrs(esto);
		var sendform = $.extend({}, formser,AtrData);		
		var config={dir:'/autocomplete'};
		sendform[esto.attr('name')]=esto.val()
		DinamicCombo(config,sendform,ComboCarga,function(){	
			ComboCarga.change();
		});
	});

	$("body").delegate('[data-autosearch="true"]',"change",function(){
		var esto=$(this);
		var config={dir:'/autocomplete/?',type:"GET"};
		var formser=esto.parents('form').serializeObject();
		var AtrData=GetAttrs(esto);
		var sendform = $.extend({}, formser, AtrData);
		jSONInfo(config,sendform,
			function(data){
				AsignarJSON(data,undefined,function(){
					var func=esto.attr("data-func");
					if(func!=undefined) eval(func);
				});
				
			});
	});
	$("body").delegate('[data-autotitle="true"]',"change",function(){
		var esto=$(this);
		var Tag=esto[0].tagName;
		if(Tag=="INPUT") var msgtxt=esto;
		else if(Tag=="SELECT")	var msgtxt=esto.find('option[value="'+$(this).val()+'"]');
		var msg=$('[data-title="'+$(this).attr("data-id")+'"]');
		if((msgtxt.length)&&(msg.length)){
			msgtxto=msgtxt.attr("data-title");
			msgcomp=msgtxt.attr("data-titlecomp");
			msg.unbind('click').html("");
			if((msgtxto!="")&&(msgtxto!=undefined)){								
				msg.css("cursor","pointer").html(msgtxto)
				if(msgcomp!="") msg.append('<div class="pq">'+msgcomp+'</div>');
				
				msg.slideDown('fast')				
					.bind("click",function(){
					$(this).slideUp('fast',function(){ $(this).html(""); });	
				});
			}
		}
	});
	$("body").delegate('[data-accion="localizar"]',"click",function(e){
		e.preventDefault();					
		UbicMapa()
	});
	$("body").delegate('[data-combo="next"]',"change",function(e){
		e.preventDefault();					
		$(this).next().focus();
	});

	$("body").delegate('[data-more="true"]',"click",function(e){
		$_stext=$(this).siblings("._stext")
		$_scomplete=$(this).siblings("._scomplete")
		$_stext.fadeOut(50);
		$_scomplete.slideDown(50);
		$(this).fadeOut(50);
	});

	$("body").delegate('[data-expand=true]','click',function () {
		if($(this).val()==1){
			$(this).parent().next().slideDown(200,function(){
				if($(this).find('[data-maps="true"]').length) map_refresh();
			});	 
		}
		else{
			$(this).parent().next().slideUp(200); 
			$(this).parent().next().find('[type=file]').attr({value:''}); 
		}
	});	
	$("body").delegate('[data-expid]','click',function () {		
		if($(this).prop("checked")){
			var expand=$('[data-id="'+$(this).attr("data-expid")+'"]');
			if(expand.css("display")=="none") 
					expand.slideDown(200,function(){
						map_refresh();
					});	
					
			$('[data-grupo="'+$(this).attr("data-group")+'"]').not(expand).slideUp(200); 		
		}
	});	
		
	$("body").delegate("._cerrar","click",function(e){
		e.preventDefault();
		CancelarFrm({loadcont:$(this).attr("data-loadcont")});
	});
	$("body").delegate('[data-automap="true"]',"change",function(e){
		UbicMapa();
	});
	$("body").delegate('[data-name^=Save]',"click",function(event){ 
		event.preventDefault();	
		event.stopPropagation();			
		var esto=$(this);
		var Fila=esto.parents('tr').first();
		if(Fila.hasClass("colf05")){
			Fila.removeClass("colf05",80);

			var opciones=GetAttrs(esto);
			Fila.find('input,textarea,select').each(function(index,value){
				opciones[$(this).attr("data-name")] = $(this).val();	
			})	
			var config={dir:'/toperation/',type:"POST"};
			jSONInfo(config,opciones,
				function(data){
					if(!data.stop){		
						esto.find(".icon").switchClass("_x_01 _x_02","_x_03",1);
						esto.css("cursor","default")
							.attr("data-enable","false");
						Fila.removeClass("colf05",80);
						if(data.parAd.md!=undefined)
							esto.attr('data-md',data.parAd.md);
						if(data.parAd.md_del!=undefined)
							Fila.find('[data-deltable="true"]').attr('data-md',data.parAd.md_del);
						if(data.parAd.idlbl!=undefined)
							Fila.find('[data-idlbl]').html(data.parAd.idlbl);
					}
					else console.log(data.msg);
						
				});
		}
	})
	$("body").delegate('[data-deltable="true"]',"click",function(e){ 
		e.preventDefault();				
		var esto=$(this)
		,	Fila=esto.parents('tr').first()
		,	Tabla=Fila.parents('table').first()
		if(!Fila.hasClass("colf09")){
			Fila.addClass("colf09",50)
			form_en(0);
			var OFilas=Fila.siblings();
			$.when(OFilas.slideUp(50)).then(function(){
					

				var $Msg=$('<div class="message col_bg03 hide" />')
				,	$Delete=$('<button class="button bt_col1">'+FindWord(esto.attr("data-label"))+'</button>')
				,	$Cancel=$('<button class="button bt_col2">'+FindWord(esto.attr("data-cancel"))+'</button>')
				,	$BtnConten=$('<div class="buttons" />').append($Delete).append($Cancel)

				$Msg.html('<h4 class="tit-msg">'+FindWord(esto.attr("data-title"))+'</h4>'+
						'<div class="cnt-msg">'+FindWord(esto.attr("data-msg"))+' '+FindWord(esto.attr("data-confirm"))+'</div>');
				$BtnConten.appendTo($Msg);

				Tabla.after($Msg);
				$Msg.slideDown(50);

				$Cancel.bind('click',function(event){
					event.stopPropagation()
					event.preventDefault()
					e.stopPropagation();
					Cancel($Msg,Fila,OFilas);
				})

				$Delete.bind('click',function(event){
					event.stopPropagation()
					event.preventDefault()
					var opciones=GetAttrs(esto);
					if(opciones.md!=undefined){
						var config={dir:'/tdelete/?',type:"POST"};
						jSONInfo(config,opciones);
					}
					Fila.slideUp(50,function(){ $(this).remove()});
					Cancel($Msg,Fila,OFilas);
				});


				var Cancel=function($Msg,Fila,OFilas){
					$Msg.slideUp(50,function(){ $(this).remove()});
					OFilas.slideDown(50);
					Fila.removeClass("colf09",50)
					form_en(1);
				}
			});
		}
	})
		
	/***********BOTONES FORMULARIOS**************/
	//DELETE DE FORMS
	$("body").delegate('[data-delform="true"]','click',function(e){
		e.preventDefault();		
		DeleteTr($(this));
	});
	//EDIT DE FORMS
	$("body").delegate('[data-editform="true"]','click',function(e){
		e.preventDefault();
		var editverif=$(this).attr("data-editverif");
		EditTr($(this),editverif);
	});
	//NEW 'NU'
	$("body").delegate('select','change',function(e){
		var Form=$(this).parents("form");
		var fNextName=$(this).attr("name")
		var fNext=Form.find('[data-newfields="'+fNextName+'"]');
		if(fNext.length){
			if($(this).val()=="NU") 	fNext.first().slideDown('fast');			
			else						fNext.first().slideUp('fast');
		}
	});
	//TABLAS
	$("body").delegate('[data-edcontrol="true"] [class^=input]','change',function(event){
		event.stopPropagation()
		event.preventDefault()
		var $esto=$(this)
		EdChange($esto);		
	});	
	$("body").delegate('[data-table="nestable"]','change', function(event) {
		event.stopPropagation()
		event.preventDefault()
		var $esto=$(this)
		EdChangeNestable($esto)
			
	});

	$("body").delegate('[data-action="rating"]','mouseenter',function(event){
        var $este=$(this)
        ,   value=parseInt($este.attr("data-value"))
        ,   $stars=$este.siblings()

        $este.switchClass('stB','_HOVER',0)
        $stars.each(function(index,data){
            if(parseInt($(this).attr("data-value"))<=value)
                $(this).switchClass('stB','_HOVER',0)
            else
                $(this).switchClass('_HOVER','stB',0)
        })
    });
    $("body").delegate('[data-action="rating"]','mouseleave',function(event){
        var $este=$(this).parent().find('[data-selected="true"]')
        ,   value=parseInt($este.attr("data-value"))
        ,   $stars=$(this).parent().find('[data-action="rating"]')
        $stars.each(function(index,data){
            if(parseInt($(this).attr("data-value"))<=value)
              	$(this).switchClass('stB','_HOVER',0)
            else
                $(this).switchClass('_HOVER','stB',0)
        })
    });
    $("body").delegate('[data-action="rating"]','click',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $este=$(this)
        ,   $stars=$este.siblings()
        ,	target=$este.attr('data-target')        
        $('[name="'+target+'"]').val($este.attr('data-value'))

        $stars.removeAttr('data-selected')
        $este.attr('data-selected','true')

    });
    ///***////***///***///
	///***////***///***///
	///***////***///***///
	$("body").delegate('[data-change="true"]',"change",function(e){		
		ShowSave($(this));
	});
	$("body").delegate('input[data-change="true"],textarea[data-change="true"]',"keypress",$.debounce( 250, function(){
		ShowSave($(this));
	}));
	var ShowSave=function($trigger){
		var esto=$trigger;
		var Fila=esto.parents('tr').first();
		var BtnSave=Fila.find('[data-name^=Save]').first(); 
		if(!Fila.hasClass("colf05")){
			Fila.addClass("colf05",80);
			BtnSave.attr("data-enable","true");
			BtnSave.find(".icon").switchClass("_x_03 _x_02","_x_01",1);
			BtnSave.css("cursor","pointer");						
		}
	}
}


var EdChange=function($esto){
	var $pTabla=$esto.parents('[data-edcontrol="true"]').first()
	,	ContType=$pTabla[0].tagName
	,	EdID=$pTabla.attr("data-edid")
	,	EdCn=$pTabla.attr("data-cnid")
	
		
	if(ContType=='TABLE') 	var $fila=$esto.parents("tr").first();
	else if(ContType=='OL')	var $fila=$esto.parents('li').first();

	var ControlED=$fila.find('[name^='+EdID+']').not('[value="10"]')
	ControlED.val(1);

	if(EdCn!=undefined) EdChange($pTabla)
}
var EdChangeNestable=function($esto){
	var $pTabla=$esto.children('ol.dd-list').first()
	,	EdID=$pTabla.attr("data-edid")
	,	EdCn=$pTabla.attr("data-cnid")
	,	$fila=$esto.find('li')

 	RecNestable($pTabla); 
 	OrderNestable($pTabla); 

	$fila.each(function(index,data){
		var $ControlED=$(this).find('[name^='+EdID+']').not('[value="10"]');
		$ControlED.val(1);
	})	
	if(EdCn!=undefined) EdChange($esto.parents('[data-edcontrol="true"]').first())
}
function EditTr(esto,id){
    ShowFrmTr(esto,function(){
        var ContType=esto.parents('[data-carga="true"]').attr("data-table");
        ContType=ContType==undefined?'tabla':ContType;

        if(ContType=='tabla')           var fila=esto.parents("tr");
        else if(ContType=='nestable')   var fila=esto.parents('li');

        var tabla=fila.parents("div").first();
        var OverPass=$('<div class="_over" />');
        tabla.css("overflow","hidden").css("position","relative").prepend(OverPass);
        tabla.find("tr").not(fila).animate({opacity:0.1},'slow');
        
        $('[data-idgroup="true"]').val(fila.find('[name^='+id+']').val());  
        fila.attr("data-flag","1");
        var Input=fila.find(":input");
        var count=Input.length;
        Input.each(function(index, element) {           
            var name = $(this).attr("name")==undefined?'':$(this).attr("name")
            var strName=name.indexOf('[');
            if(strName>-1)  name=name.substring(0,strName);                      
            var boxinp= $('[data-prefix="'+name+'"]');
            if(boxinp.length){
                var value=$(this).val();
                var Tag=boxinp[0].tagName;
                if(Tag=="INPUT"){
                    if((boxinp.attr("type")=="radio")||(boxinp.attr("type")=="checkbox")){
                        boxinp.attr("checked",value=="true");
                    }
                    else boxinp.val(value);
                }
                else if(Tag=="SELECT"){
                    boxinp.find('option').removeAttr("selected");
                    boxinp.attr("data-selected",value);
                    boxinp.find('option[value="'+value+'"]').attr("selected","selected");                                       
                }
                            
                if((boxinp.attr('data-defacc')!=undefined)&&(boxinp.is(":visible"))){
                    if(boxinp.attr('data-defacc')=="click"){
                        if((boxinp.attr("type")=="radio")||(boxinp.attr("type")=="checkbox")){
                            if(boxinp.prop("checked")) boxinp.click();
                        }
                        else  boxinp.click();
                    }
                    else if(boxinp.attr('data-defacc')=="change") boxinp.change();
                }
            }
        }); 
    });
    
}
function DeleteTr(esto){    
    var BtnEnable=esto.attr("data-enable")=="false"?false:true;
    if(BtnEnable){
        var ContType=esto.parents('[data-carga="true"]').attr("data-table");
        ContType=ContType==undefined?'tabla':ContType;

        if(ContType=='tabla')           var fila=esto.parents("tr").first();
        else if(ContType=='nestable')   var fila=esto.parents('li').first();

        if(esto.attr("data-delverif")==undefined) var Estados=esto.attr('data-delvalue');
        else{
            var Estado=fila.find('[name^='+esto.attr("data-delverif")+']');
            var Estados=Estado.val();
        }
        

        if(Estados==20) fila.hide('slow',function(){ $(this).remove() });
        else{
            var icon=esto.children(".icon");
            var label=esto.children(".label");
            var Altern=esto.attr("data-altern");    
            var hItem=fila.find('[name="'+esto.attr("data-delhab")+'['+esto.attr("data-value")+']"]');
            var HabFlag=hItem.length?hItem.val():(esto.hasClass("colf04")?1:0);
            if(HabFlag==0){         
                fila.find("[class^=input]") .css("border-style","none")
                                    .css("text-decoration","line-through")
                                    .attr("readonly","readonly");
                fila.find('[data-boton="true"]').not(esto).attr("data-enable","false");

                                    
                icon.removeClass("ui-icon-circle-close").addClass("ui-icon-circle-check");                              
                hItem.val(1);
                esto.switchClass("colf01","colf04",1);
                esto.attr("data-altern",label.html())
                label.html(FindAsist(Altern));     
                Estado.val(10);     
            }
            else{           
                fila.find("[class^=input]") .css("border-style","solid")
                                    .css("text-decoration","none")
                                    .removeAttr("readonly","readonly");
                fila.find('[data-boton="true"]').not(esto).attr("data-enable","true");
                icon.removeClass("ui-icon-circle-check").addClass("ui-icon-circle-close");  
                hItem.val(0);       
                esto.switchClass("colf04","colf01",1);      
                esto.attr("data-altern",label.html())
                label.html(FindAsist(Altern)); 
                Estado.val(1);     
            }
            
        }
    }   
}
function HideFrmTr(esto,callBack){  
    var Contain=$('[data-boxgroup="'+esto.attr("data-group")+'"]'); 
    var Table=$('#'+Contain.attr("data-table"));
    /**/
    var FlagEl=Table.find('[data-flag="1"]').removeAttr("data-flag");
    Contain.find(":input").each(function(index, element) {
        if($(this).attr('data-defval')!=undefined){
            if(($(this).attr("type")=="radio")||($(this).attr("type")=="checkbox")){    
                if($(this).attr('data-defval')=="true") $(this).prop("checked",true);
                else                                    $(this).prop("checked",false);
        
            }
            else $(this).val($(this).attr('data-defval'));
        }
        if($(this).attr('data-delselect')!=undefined) $(this).find("option").remove();
        if($(this).attr('data-defacc')!=undefined){
            if($(this).attr('data-defacc')=="click"){
                if(($(this).attr("type")=="radio")||($(this).attr("type")=="checkbox")){
                    if($(this).prop("checked")) $(this).click();
                }
                else $(this).click();
            }
            else if($(this).attr('data-defacc')=="change") $(this).change();
        }               
    });
    
    Contain.prev().find('[data-accgroup="new"]').animate({opacity:1},'fast');
    
    var ReqFields=Contain.find('._req');
    ReqFields.each(function(index, element) {
        var Field=$(this).next().first();           
        var msgBar=Field.next('.alert');        
        msgBar.text("").slideUp('fast');
        Field.removeClass("recordar borde_8",'slow');
                    
    }); 

    Table.find("._over").remove();
    Table.find("tr").animate({opacity:1},'slow');
    
    Contain.find(".contform").slideUp('fast',function(){    
        Contain.animate({width:"3%"},
                {
                    duration: 'fast',
                    step: function( now, fx ){
                        Table.css("width",(100-now)+"%");
                    }
                    ,complete: function(){  
                        $(this).css("display","none");
                        Table.css("width","100%");  
                        if(typeof callBack == 'function') callBack();
                        
                        /*if($("#_tWrap").length) $('#pagInsert').animate({scrollTop: Table.offset().top}, 'slow'); 
                        else $('html, body').animate({scrollTop: Table.offset().top}, 'slow');*/

                        var ParentW=$("#_tWrap").length?$('#pagInsert'):$('html, body');
                        var ChildW=Table;
                        ParentW.animate({ scrollTop: ParentW.scrollTop() + ChildW.offset().top - ParentW.offset().top }, { duration: 'slow', easing: 'swing'});

                    }
        });
    });
}
function ShowFrmTr(esto,callBack){  
    
    var Contain=$('[data-boxgroup="'+esto.attr("data-group")+'"]'); 
    var Table=$('#'+Contain.attr("data-table")).css("display","inline-block");
    Table.css("overflow","hidden");
    Contain.css("width","0").css("display","inlie-block")
        .animate({width:"40%"},
                {
                    duration: 'fast',
                    step: function( now, fx ){
                        Table.css("width",(99-now)+"%");
                    }
                    ,complete: function(){  
                        $(this).find(".contform").slideDown('fast',function(){
                            if(typeof callBack == 'function') callBack();
                        });
                            
                    }
                }
        );
    Contain.prev().find('[data-accgroup="new"]').animate({opacity:0.1},'fast'); 
}
function ModifyFrmTr(Contain,callBack){
    var dt = new Date(), i=0,formset={};
    var Table=$('#'+Contain.attr("data-table"));
    var IdGrupo=Contain.find('[data-idgroup="true"]');
    var isNew=(IdGrupo.val()=="");
    var NewId=IdGrupo.val()==""?dt.getTime():IdGrupo.val();
            
    var Inputs=Contain.find(":input");
    count=Inputs.length;
    Inputs.each(function(index, element) {              
        if( (($(this).attr('data-type')=='label')&&($(this).is(":visible")))||
        (($(this).attr('data-type')=='forze')&&($(this).parent().css("display")!="none"))){
            i++;
            formset[i]={}                       
            var Tag=$(this)[0].tagName; 
            if(Tag=="INPUT"){
                if(($(this).attr("type")=="radio")||($(this).attr("type")=="checkbox"))      var Valor=$(this).attr("checked")=='checked';
                else var Valor=$(this).val();
            
            }
            else if(Tag=="SELECT"){
                if($(this).val()=="NU")  var Valor=$('[data-id="'+$(this).attr("data-newcase")+'"]').val();
                else var Valor=$(this).find("option:selected").html();
            }
            else                    var Valor=$(this).text();                           
                                
            if(($(this).attr("data-tipo")=="money")||($(this).attr("data-tipo")=="number")){
                var num=$('<span />').html(Valor).formatNumber()
                if($(this).attr("data-tipo")=="money")  formset[i].label="$"+num.text();
                else                                    formset[i].label=num.text();                    
            }
            else formset[i].label=Valor;
            
            if($(this).attr('data-hide')=='true'){                                          
                formset[i]['hide']={};
                formset[i]['hide']["id"]=$(this).attr('data-prefix')+"_"+NewId;
                formset[i]['hide']["value"]=$(this).val();
                formset[i]['hide']["name"]=$(this).attr('data-prefix')+"["+NewId+"]";
            }
            
        }
        else if($(this).attr('data-type')=='button'){
            i++;
            formset[i]={}
            formset[i]['label']=$(this).attr('data-label');
            formset[i]['tipo']=$(this).attr('data-type');
            formset[i]["value"]=NewId;
            formset[i]["icon"]=$(this).attr('data-icon');
            formset[i]["id"]=$(this).attr('data-prefix')+"_"+NewId;                 
            formset[i]["name"]=$(this).attr('data-prefix')+"["+NewId+"]";
            formset[i]["data"]={};              
            var attrs = $(this)[0].attributes;          
            for(var k=0;k<attrs.length;k++){
                if(attrs[k].nodeName.substr(0,4)=="data"){                          
                    var Propiedad=attrs[k].nodeName.substr(5);  
                    formset[i]["data"][Propiedad]={};
                    formset[i]["data"][Propiedad]=attrs[k].nodeValue;
                }
            }
        }
        else if($(this).attr('data-type')=='hidden'){
            i++;
            formset[i]={}
            formset[i]['tipo']=$(this).attr('data-type');
            if($(this).attr('data-putid')=="true")
                formset[i]["value"]=NewId;
            else if($(this).attr('data-edflag')=="true")
                formset[i]["value"]=$(this).val()=='20'?'20':'1';
            else{
                if(($(this).attr("type")=="radio")||($(this).attr("type")=="checkbox")){
                     var Valor=$(this).attr("checked")=='checked'?'true':'false';
                }
                else var Valor=$(this).val();   
                formset[i]["value"]=Valor;
            }
            formset[i]["icon"]=$(this).attr('data-icon');
            formset[i]["id"]=$(this).attr('data-prefix')+"_"+NewId;                 
            formset[i]["name"]=$(this).attr('data-prefix')+"["+NewId+"]";
        }
        if (!--count){                          
            if(typeof callBack == 'function') callBack(formset,isNew);
        }
        
    });
}
function RecNestable(ol){
	var hidParent=ol.find('[data-parent="true"]');		
	hidParent.each(function(index_parent, data_parent) {	
		var idObj=$(this).parents("ol").first().parents("li").first()
		var id=idObj.length==0?0:idObj.attr("data-id");					
		$(this).val(id);						
	});	
}
function OrderNestable(ol){
	var Order=0;
	ol.find('li.dd-item').each(function(index, data) {
		var OrdParent=$(this).find('[data-order="true"]').first();					
		OrdParent.val(Order);
		Order++;
	});
}

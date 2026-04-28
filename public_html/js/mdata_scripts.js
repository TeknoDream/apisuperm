var Trans=function(obj,callBack){
    var href=obj.value
    ,   $swrap=$("#wrap")    
    ,   $ParentW=$('html, body')
    ,   args=obj.parameters
    ,   IsSearch=args.term!=undefined
    ,   $spage=$('[data-spage="'+href+'"]')
    
    args._AJAX=1;
    if($('[data-action="cmodulos"]').is(':visible'))
        NavOpenClose(false);

    if($spage.length){
        $ParentW.animate({ scrollTop: $ParentW.offset().top}, { duration: 'slow', easing: 'swing'});
        var $spages=$spage.siblings(':visible')  
        $.when($spages.fadeOut('fast')).then(function(){
            $spage.fadeIn('fast')
        })
    }
    else if(obj.type=='change'){             

        $ParentW.animate({ scrollTop: $ParentW.offset().top}, { duration: 'slow', easing: 'swing'});
        StartSpin(true);
        $.ajax({ 
            url: href, 
            data:args,
            success: function(data){          
                var $data=$(data)          
                $swrap.empty();

                ActCSSJs($data);                                  
                $swrap.animate({opacity:0},80,function(){
                    $swrap.html($data);
                    FillBlanks($data,function(){          
                        $swrap.animate({opacity:1},150,function() {
                            Espacios();
                            mdMask(); 
                            StartSpin(false);                                                         
                        })
                    }); 
                });                       
                
            }
        });
    }   
} 

var MarkWindow=function(){
    var href=$.address.value()
    ,   $SelLim=$('.smodules [data-href="'+href+'"].lim')
    ,   $AllLim=$('.smodules .lim').siblings()
    $AllLim.removeClass('_selected')
    $SelLim.addClass('_selected')
}


var ActCSSJs=function($data){
    var $css=$data.siblings('style')
    ,   $js=$data.siblings('script')
    ,   $jsInsert=$('#_JS_ADD')
    ,   $cssInsert=$('#_CSS_ADD')

 
    $jsInsert.remove()
    var $NjsInsert=$('<script id="_JS_ADD" type="text/javascript" />')
    $cssInsert.remove()
    var $NcssInsert=$('<style id="_CSS_ADD" type="text/css" />')


    $('head').append($NjsInsert,$NcssInsert)
    $NjsInsert.replaceWith($js)
    $NcssInsert.replaceWith($css)  
}

var StartSpin=function(present){
    var $UnderLoader=$("#under_loader")
    ,   $Loader=$UnderLoader.nextAll('.loader_all').first();

    if(present){      
        $Loader.fadeIn(90)
        $UnderLoader.fadeIn(90,function(){            
            Espacios();   
        });
        
    }
    else{
        $Loader.fadeOut(90)
        $UnderLoader.fadeOut(90,function(){
            Espacios();
            $footer.css("display","block");
        });
    }
}
/****************************/
/****************************/
/****************************/
/****************************/
var FillBlanks=function($place,callBack){
    var $search
    if($place==undefined)   $search=$('[data-txtid]');
    else                    $search=$place.find('[data-txtid]');

    var count=$search.length;
    if(count==0 && typeof callBack == 'function') callBack();
    else{
        var words_json=localStorage.getItem('x_textos')
        ,  words= JSON.parse(words_json);

        $search.each(function(index, element){
            var $esto=$(this)
            ,   _IndTxt=$esto.attr("data-txtid")
            ,   word=words[_IndTxt]            
            ,   Attr=$esto.attr("data-attr")
            ,   AddPaste=$esto.attr("data-addtxt")
            
            if(AddPaste!=undefined){ 
                if(Attr!=undefined){
                    word=$esto.attr(Attr)+AddPaste+word
                }                   
                else
                    word=$esto.text()+AddPaste+word
            }
            

            if(Attr!=undefined)                                   
                $esto.attr($(this).attr("data-attr"),word)
            else 
                $esto.html(word)

            $(this).removeAttr('data-txtid').removeAttr('data-txtsid')

            if(!--count)
                if(typeof callBack == 'function') callBack();
        })
    }

    //LOCAL STORAGE
    $('[data-localStorage="true"]').each(function(index,element){
        var data=FindWord($(this).attr("name"))
        data=data==undefined?'':data;
        $(this).val(data);
    });
}
var FindWord=function(key){
     var words_json=localStorage.getItem('x_textos')
     ,  words= JSON.parse(words_json);
     return(words[key]);
}
/****************************/
/****************************/
/****************************/
/****************************/
function Espacios(){
    var AlturaBody=$("body").outerHeight(); 
    var AlturaWindow=$(window).outerHeight();
    var $Footer=$("#_tWrap > footer").first();
    if($Footer.css("position")=="fixed") AlturaBody+=$Footer.outerHeight();
    
    if(AlturaBody>AlturaWindow)
        $Footer.css("position","static")
    else{
        $Footer.css("position","fixed");
        $Footer.css("bottom","0px");
    }
}
/****************************/
/****************************/
/****************************/
/****************************/
var jSONInfo=function(config,option,callBack){
    option._AJAX=1;

    var ConfigAjax={
                url:config.dir
            ,   type:config.type
            ,   data: option
            ,   dataType:"json"
            ,   success: function(data) {           
                    if(typeof callBack == 'function') callBack(data);
                    return data;
                },
                error: function (data, status, e){
                    console.log("jSONInfo - No se pudo cargar la pagina: "+data.responseText);
                }
            }
    ,    $Files=config.Files
    if($Files!=undefined&&$Files.length!=0){   
        ConfigAjax.fileElementId=$Files;
        if($Files.length>1){
            option.control_img=[]
            $Files.each(function(index,data){
                option.control_img.push($(this).attr('name'));
            })
            
        }
        else                
            option.control_img=$Files.attr('name');
        $.ajaxFileUpload(ConfigAjax);
    }
    else{
         $.ajax(ConfigAjax);
    } 
}

function ValForm($form){
    var $FirstBad=$()
    ,   _msgerr=FindWord('txt-1015-0')
    ,   _msgerr_mail=FindWord('txt-MSJ10-0')
    ,   $Fields=$form.find('[data-required="true"]:visible, [data-required="true"][data-force="true"]')
    ,   $GFIelds=$form.find('[data-greq]')
    $Fields.each(function(index, element){
        var $esto=$(this)
        ,   Tag=$esto[0].tagName
        ,   Tipo=$esto.attr('type')
        ,   Value=''

        if($esto.attr("multiple")!=undefined)
            Value=$esto.find("option:selected").length
        else if(Tag=='DIV')
            Value=$esto.attr("data-val")
        else
            Value=$esto.val()

        var MailVerif=Tipo=='email'?ValidaMail(Value):true;
            
        var $msgBar=$esto.next('.alert');
        if(!$msgBar.length){
            if($esto.attr('data-tipo')=='tagger')
                $msgBar=$('<div class="alert colf04" style="display:none" />').insertAfter($esto.next());
            else
                $msgBar=$('<div class="alert colf04" style="display:none" />').insertAfter($esto);
        }

        if((Tag=="INPUT" && Value=="")||(!MailVerif)||
            (Tag=="SELECT" && (Value==0||Value==''||Value==null))||
            (Tag=="DIV" && Value=="")){

            if(!MailVerif)  $msgBar.text(_msgerr_mail)
            else            $msgBar.text(_msgerr)

            $msgBar.slideDown('fast')
                    .delay(4000)
                    .slideUp('fast');

            if(!$FirstBad.length) $FirstBad=$esto;
        }
        else{
            $msgBar.slideUp('fast',function(){ $(this).empty()});
            $esto.removeClass("borde-colf04",'fast');
        }   
    });
    if(!$FirstBad.length){
        $GFIelds.each(function(index, element){
            var $FieldInside=$(this).find('[name^="'+$(this).attr('data-greq')+'"]')
            ,   FCound=$FieldInside.length
            ,   ECount=0
            ,   _msgerr=FindWord($(this).attr('data-errmsg'))
            var $msgBar=$(this).next('.alert');
            if(!$msgBar.length) $msgBar=$('<div class="alert colf04" style="display:none" />').insertAfter($(this));

            $FieldInside.each(function(SIndex, SElement){
                if($(this).val()=='') ECount++;
            });  
            if(ECount==FCound){
                $msgBar.text(_msgerr)

                $msgBar.slideDown('fast')
                        .delay(3000)
                        .slideUp('fast',function(){ $(this).remove()});

                if(!$FirstBad.length) $FirstBad=$FieldInside.first();
            }
        });
    }

    if($FirstBad.length) $FirstBad.focus();
    return !$FirstBad.length;
};
function DinamicCombo(config,option,$combo,callBack){
    $combo.find("option").remove();  
    $.ajax({        
        url:config.dir,
        type: "GET",
        data:option,
        dataType: 'json',
        success: function (data,status){
            if(data!=undefined){

                var count = data.length
                ,   combo=data.combo==undefined?data:data.combo;
                $.each(combo, function(index, optdata){   
                    var $opt=$("<option />").val(optdata.value).text(FindAsist(optdata.label));
                    if(optdata.selected)    $opt.attr("selected","selected");

                    $combo.append($opt);
                    if(optdata.data!=undefined){
                        $.each(optdata.data, function(index, datas){
                            $opt.attr("data-"+index,datas);
                        });
                    }

                    if (!--count){
                        if($combo.attr("data-selected")!=undefined){
                            $combo.find('option').removeAttr("selected");
                            $combo.find('option[value="'+$combo.attr("data-selected")+'"]').attr("selected","selected");
                        }
                        if(typeof callBack == 'function') callBack(data);
                    }
                });
            }
            else if(typeof callBack == 'function') callBack(data);
                    
        },
        error: function (data, status, e){
            console.log("DC - No se pudo cargar la pagina: "+data.responseText);
        }
    });
}
function ValidaMail(mail){
    var er = /^[0-9a-z_\-\.]+@([a-z0-9\-]+\.?)*[a-z0-9]+\.([a-z]{2,4}|travel)$/i;
    return er.test(mail);
}

function DateDiff(date1, date2) {
    var datediff = date1.getTime() - date2.getTime();
    return (parseInt(datediff/1000)); // (24*60*60*1000)   
}

function GetAttrs(DivCarga){
    if(DivCarga[0]!=undefined){
        var attrs = DivCarga[0].attributes;
        var Opciones={};            
        for(var i=0;i<attrs.length;i++){
            if(attrs[i].nodeName.substr(0,4)=="data"){
                var Propiedad=attrs[i].nodeName.substr(5);          
                Opciones[Propiedad]=attrs[i].value;
            }
        }
        return Opciones;
    }
}

function isElementVisible(elementToBeChecked){
    if(elementToBeChecked.length){
        var TopView = $(window).scrollTop();
        var BotView = TopView + $(window).height();

        var TopElement = $(elementToBeChecked).offset().top;
        var BotElement = TopElement + $(elementToBeChecked).height();

        return ((TopElement >= TopView)&&(TopElement <= BotView)||(BotElement >= TopView)&&(BotElement <= BotView))
    }
    else return false;
}
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
/*********************/
/*****COOKIES********/
/*********************/
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {createCookie(name,"",-1);}

function form_en(tipo){
    var $InputType=$('input, textarea, select')
    ,   $Buttons=$('button:not([data-disabled="force"])')
    if(tipo==0){
        $InputType.prop("readonly", true);
        $Buttons.prop("disabled", true);
    }
    else{
        $InputType.prop("readonly", false);
        $Buttons.prop("disabled", false);
    }  
}


var mdMask=function($where){
    if($where==undefined) $where=$('body')


    var $Tabs=$where.find('[data-tipo="tab"]')
    ,   $Tagger=$where.find('[data-tipo="tagger"]')
    ,   $Taggerpluss=$where.find('[data-tipo="taggerpluss"]')
    ,   $DateTime=$where.find('[data-tipo="date"],[data-tipo="datetime"]')
    ,   $DateFormat=$where.find('[data-tipo="dateformat"]')
    ,   $md_carga=$where.find('.md_carga')
    ,   $Stars=$where.find('[data-tipo="stars"]')
    $DateTime.each(function(index, element) {
        if(($(this).attr("readonly")==undefined)&&($(this).attr("data-propdate")==undefined)){
            $(this).attr("data-propdate",'true').datepicker("destroy");

            if($(this).attr("data-tipo")=="date")           var opciones={changeMonth: true,changeYear: true}
            else if($(this).attr("data-tipo")=="datetime")  var opciones={changeMonth: true,changeYear: true,timeFormat: "HH:mm"}
            
            if($(this).data("maxdate")!=undefined)
                opciones.maxDate=new Date(parseInt($(this).data("maxdate")));
            
            if($(this).data("mindate")!=undefined)
                opciones.minDate=new Date(parseInt($(this).data("mindate")));
            
            if($(this).data("minobj")!=undefined){
                opciones.onClose=function( selectedDate ) {         
                    var $fTemp=$('[data-id="'+$(this).data("minobj")+'"]'); 
                    $fTemp.datepicker( "option", "minDate", selectedDate );
                    //$fTemp.datepicker( "show" );
                }               
            }
            if($(this).data("maxobj")!=undefined){
                opciones.onClose=function( selectedDate ) {
                    var $fTemp=$('[data-id="'+$(this).data("maxobj")+'"]'); 
                    $fTemp.datepicker( "option", "maxDate", selectedDate );
                    //$fTemp.datepicker( "show" );
                }
            }
            if($(this).attr("data-tipo")=="date")           $(this).datepicker(opciones);    
            else if($(this).attr("data-tipo")=="datetime")  $(this).datetimepicker(opciones); 
        }
    }); 


    $where.find('[data-tipo=colorpicker]').each(function(index, element) {
        var datapicket_control={
            control: $(this).attr('data-control') || 'hue',
            defaultValue: $(this).attr('data-defaultValue') || '',
            inline: $(this).attr('data-inline')  === 'true',
            letterCase: $(this).attr('data-letterCase') || 'lowercase',
            opacity: $(this).attr('data-opacity') ,
            position:$(this).attr('data-position') || 'bottom left',
            
            change: function(hex, opacity) {
                if( !hex ) return;
                if( opacity ) hex += ', ' + opacity;                                
            },
            theme: 'bootstrap'}
        $(this).minicolors(datapicket_control); 
    }); 

    $where.find('[data-tipo=time]').timepicker({
        hourGrid: 4,
        minuteGrid: 10,
        timeFormat: "HH:mm"
    }); 
    $where.find('textarea').not('[data-tipo=richtext]').each(function(index, element) {
        $(this).autosize();
    });
    $where.find('[data-tipo=richtext]').each(function(index, element) {
        $(this).ckeditor();//.jqte();       
    });

    $Stars.each(function(index, element) {
        var $esto=$(this)
        ,   min=$esto.attr('data-min')
        ,   max=$esto.attr('data-max')
        ,   value=$esto.attr('data-value')
        ,   name=$esto.attr('data-name')
        ,   $input=$('<input type="hidden" name="'+name+'" value="'+value+'" />  ').appendTo($esto)
        $esto.addClass('_stars')
        for (i = min; i <= max; i++) {
            if(i==value) var Class='_FULL'
            else            var Class='_EMPTY'
            $esto.append('<i data-action="rating" data-value="'+i+'" class="fa fa-star '+Class+' dsinline" data-target="'+name+'"></i>')
        }       
    });
    $Tagger.each(function(index, element) {
        $(this).tagger({
          baseURL: ''
            ,   noSuggestText:FindWord($(this).attr("data-noSuggestText"))
            ,   emptyListText:FindWord($(this).attr("data-emptyListText"))
            ,   placeholder:FindWord($(this).attr("data-placeholder"))
            ,   fieldWidth:null
        }); 
    })  

    $md_carga.each(function(index,element){
        StartPage($(this)) 
    })
     
    $Taggerpluss.each(function(index, element) {
        var $esto=$(this)
        ,   allowEdit=$esto.attr("data-allowEdit")==undefined?false:$esto.attr("data-allowEdit")=='true'
        ,   allowDelete=$esto.attr("data-allowDelete")==undefined?false:$esto.attr("data-allowDelete")=='true'
        ,   allowAdd=$esto.attr("data-allowAdd")==undefined?false:$esto.attr("data-allowAdd")=='true'
        ,   allowClass=$esto.attr("data-allowClass")==undefined?'':$esto.attr("data-allowClass")
        ,   localJSON=[]
        ,   name=$esto.attr("data-name")        
        ,   $Tag=$esto.find('.tag')
        ,   $ArrayAll=$esto.find('[name^='+name+']')
        ,   $ArrayTags=$esto.find('[name^='+name+']:not(.tag)')
        ,   source=$esto.attr("data-source")==undefined?'':$esto.attr("data-source")

        var AutocOptions={                        
                allowEdit:allowEdit
            ,   allowDelete:allowDelete
            ,   allowAdd:allowAdd
            ,   animSpeed:80 
            ,   additionalListClass:allowClass           
        };

        if(source==''){
            FillBlanks($ArrayAll,function(){
                $ArrayTags.each(function(inx,dta){
                    var index = $(this).prop('name').match(/\[(.*?)\]/)[1];
                    localJSON.push({ "id": index, "label": $(this).val(), "value": $(this).val()})
                    $(this).remove();
                }) 

                 

                if(localJSON.length!=0) AutocOptions.autocompleteOptions={source: localJSON }
                else                    AutocOptions.autocompleteOptions={source: [{id:0,label:'-',value:'-'}]}
                $Tag.tagedit(AutocOptions);
            })
        }
        else{
            AutocOptions.autocompleteURL=source
            $Tag.tagedit(AutocOptions);
        }
            
    })
    $DateFormat.datetextentry({ field_order: 'DMY' });
    $Tabs.tabs();    


    var $carga=$where.find('[data-carga="true"]')
    $carga.each(function(index, element) {
        DataCarga($(this),$(this).parents('form'));         
    });

    var $Maps=$where.find('[data-maps="true"]')
    $Maps.each(function(index, element) {   
        InitMaps($(this))   
    });  

    var $AutoList=$where.find('[data-tipo="auto-list" ]')
    $AutoList.each(function(index, element) {   
        FilterList($(this),{})   
    }); 

    
}
function AsignarJSON(data,rta,callBack){
    if(data.cont!=undefined){
        $.each(data.cont,function(index,value){
            var boxinpT=$('[data-name="'+index+'"]');
            
            if(boxinpT.length){
                $.each(boxinpT,function(cbox,boxEt){
                    var Safe=true;
                    var boxinp=$(this);
                    if(rta!=undefined){
                        var Parent=boxinp.parent();
                        if(Parent.attr("data-rtaauto")==undefined) Safe=true
                        else Safe=(Parent.attr("data-rtaauto")==rta.attr("data-rtaauto"))?true:false;
                    }
                    if(Safe){                       
                        var Tag=boxinp[0].tagName;
                        if(Tag=="INPUT"){
                            var prevVal=boxinp.val();
                            boxinp.val(FindAsist(value));
                            if(boxinp.attr("type")=="hidden") boxinp.change();
                        }
                        else if(Tag=="SELECT"){
                            var prevVal=boxinp.val();
                            boxinp.find('option').removeAttr("selected");
                            boxinp.find('option[value="'+value+'"]').attr("selected","selected");                                       
                            if(prevVal!=value) boxinp.change();
                            
                        }
                        else{
                            if(boxinp.attr("data-expanddep")=="true"){
                                if((value=="")||(!value))   boxinp.slideUp('fast')
                                else                        boxinp.slideDown('fast')
                            }
                            else boxinp.html(FindAsist(value));
                        }
                        
                    }

                    BounceColor(boxinp);
                });
            }
            if(rta!=undefined)
                if(index==rta.attr("data-req")) rta.attr("data-val",value);
        });
    }
    if(typeof callBack == 'function') callBack(data);
}
var BounceColor=function(element,duration){
    duration=duration==undefined?300:duration;
    var background=element.css("background-color"),
        border=element.css("border"),
        color=element.css("color");
    
    element.animate({
        color: "#363636",
        backgroundColor: "#F5F6CE"
    },duration,
        function(){
            element.animate({
                color: color,
                backgroundColor: background
            },duration*2);
    });
}
function DataCarga($esto,$form){
    var formser=$form.serializeObject()
    ,   AtrData=GetAttrs($esto)
    ,   sendform = $.extend({}, formser,AtrData)
    BuscarOtros($esto,$esto,sendform);    
}
function BuscarOtros(trig,donde,opcion,callBack){
    /***/
    var position=trig.css("position"),overflow=donde.css("overflow"),height='auto'
    ,   loader=$('<div class="_miniloader" />');
    
    trig.css("position","relative") 
        .css("overflow","hidden")   
        .append(loader);
    loader.fadeIn('fast');

    opcion._AJAX=1
    /***/                           
    donde.animate({
        opacity:0.7,
        height:"56px"
    },'fast',function(){
        $.ajax({        
            url:'/sinfo',
            type: "GET",
            data:opcion,
            dataType: 'json',
            success: function (data,status ){
                //SOLO ACTUALIZA
                if(donde.attr("data-refresh")!="true"){
                    donde.empty();
                    InsertIntoOpfmr(data,donde);
                }
                else if(donde.attr("data-refresh")=="true"){
                    var tBody=donde.find("tbody")
                    if(tBody.length)    InsertIntoOpfrmFila(data.nItem,tBody);  
                }
                //ASIGNA CLIC 
                if(data.nItem!=undefined)
                    if(data.nItem.length!=0)
                        $("."+data.id).click(function(){ $("#"+data.id).slideToggle(200); });   
                
                /***/
                loader.fadeOut('fast',function(){ 
                    $(this).remove();
                    trig.css("position",position)
                        .css("overflow",overflow);  
                    
                    var curHeight = donde.height();
                    donde.css('height', 'auto')
                    var autoHeight = donde.height();

                    donde.height(curHeight)
                        .animate({
                        opacity:1,
                        height:autoHeight
                    },'fast',function(){
                        $(this).css("height",height);
                    });     
                }); 
                /***/
            },
            error: function (data, status, e){
                console.log("BOtr - No se pudo cargar la pagina: "+data.responseText);
            }
        }).done(function(){
            if(typeof callBack == 'function') callBack();
        });

    })
}
/*********************/
/****** NAV BARS******/
/*********************/
var NavOpenClose=function(open){
    var $esto=$('[data-option="navicon"]')
    ,   action=$esto.attr('data-action')  
    ,   $next=$('[data-id="'+action+'"]')
    ,   $wrap=$('#wrap')
    if(!open){                
        $next.hide(100,function(){                 
            $esto.removeAttr("data-bounce");
            $wrap.css("position","relative")
            Espacios();                                 
        })
    }
    else{   
        $next.show(100,function(){
            $esto.removeAttr("data-bounce");
            $wrap.css("position","fixed")
            Espacios();
        });                  
    }   
}
var NavNofOpenClose=function(open){
    var $esto=$('[data-option="navnotify"]')
    ,   action=$esto.attr('data-action')        
    ,   $next=$('[data-id="'+action+'"]')
    ,   $wrap=$('#wrap')

    if($esto.attr("data-bounce")==undefined){
        $esto.attr("data-bounce",'true');

        if(!open){  
            $esto.removeClass('_selected')              
            $next.hide(100,function(){                   
                $wrap.removeClass('notif',100,function(){
                    $esto.removeAttr("data-bounce");
                    //$wrap.css("position","relative")
                    Espacios();
                });                    
            })
        }
        else{   
            $esto.addClass('_selected')    
            $wrap.addClass('notif',100,function(){                   
                $next.show(100,function(){
                    $esto.removeAttr("data-bounce");
                    //$wrap.css("position","fixed")
                    Espacios();
                });                    
            })          
           
        }           
    }
}
/*********************/
/*********************/
/*********************/
var GetCoords=function(callBack){
    var returns={status:0}
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position){
            returns={lat:position.coords.latitude,lon:position.coords.longitude,zoom:16,status:1}; 
                if(typeof callBack == 'function') callBack(returns);
            }, function(){
            returns={lat:3.4372200965881,lon: -76.522499084473,status:1,zoom:5}; 
                if(typeof callBack == 'function') callBack(returns);
            })
    }
    else
        if(typeof callBack == 'function') callBack(returns);
}


/*********************/
/*********************/
/*********************/
function getScript(url,success){
    var script = document.createElement('script');
    script.src = url;
    var head = document.getElementsByTagName('head')[0], done=false;
    script.onload = script.onreadystatechange = function(){
        if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
            done=true;
            if(typeof success == 'function') success();
            script.onload = script.onreadystatechange = null;
            head.removeChild(script);
        }
    };
    head.appendChild(script);
}
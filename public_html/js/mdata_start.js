// JavaScript Document/
tiempo=4500
cargas=new Object()
localStorage = window.localStorage
Inicio=false;
SIIE_VERSION=14;
$footer=$('footer.footer')
Intervals={}
_FORMS=     '[data-href^="/edit"],[href^="/edit"]'
        +   ',[data-href^="/setconfig"],[href^="/setconfig"]'
        +   ',[data-href^="/delconfig"],[href^="/delconfig"]'
        +   ',[data-href^="/delete"],[href^="/delete"]'
        +   ',[data-href^="/operation"],[href^="/operation"]'
        +   ',[data-href^="/sreport"],[href^="/sreport"]'
$.address.wrap(0).state('/')
$.address.init(function(event) {
    $('a:not([href^="http://"],[data-href^="/logout"],[href^="/logout"],'+_FORMS+',[target="_blank"],[class^=ui-],[href^=javascript],[data-transictp="10"],[data-transictp="3"])').address();    
    //console.log(event);
}).bind('change', function(event) {
    //console.log(event);
    if(Inicio) Trans(event);
    Inicio=true;
    MarkWindow()
});
_WEBNOTIF=false;
_NOTIF={}
_CargaSockets=false;
socket=undefined;
$(document).on("ready",function(){    
    /***************************************/
    /***************************************/
    /***************************************/
    /***************************************/
    var GenVersion=localStorage.getItem('siie')
    GenVersion=GenVersion==null?0:GenVersion
    if(SIIE_VERSION>GenVersion){
        localStorage.clear();
        localStorage.setItem('siie',SIIE_VERSION)
    }

    StartSpin(true);  

    LoadingAll(function(data){
        FillBlanks($('body'),function(){
            $('#wrap').fadeIn(100,function(){
                BindAll();
                BindForms();
                StartSpin(false);  
                mdMask();
            });                
        });            
    });   

    $(window).resize($.debounce( 100, function(){    
        Espacios();                
    }));

    if ("Notification" in window){
        if(!Notification.permission !== 'denied'){
            Notification.requestPermission(function (permission) {
                if(!('permission' in Notification))
                    Notification.permission = permission;
                _WEBNOTIF=permission === "granted"
            });
        }
    }
})
var onloadCaptcha = function() {
    var $Catpcha=$('[data-captcha="true"]')
      $Catpcha.each(function(){
        
        var $esto=$(this)
        ,   IdContainer=$esto.attr('id')
        ,   $ButtonSend=$esto.parents('form').find('[data-emergente="send"]')
        ,   parameters={'sitekey'   :   G_SITEKEY
                    ,   'callback'  :   function(response){       
                                            $ButtonSend.prop('disabled',false).attr('data-disabled','none')
                                        }
                                }
        $esto.empty()
        var captcha_id=grecaptcha.render(IdContainer,parameters)
        $esto.attr('data-captchaid',captcha_id)
        });
};
var LoadingAll=function(CallBack){ 
    //CARGA LOS TEXTOS
    var versions=JSON.parse(localStorage.getItem('versions'))
    ,	config={dir:'/autocomplete/',type:"GET"}
	,	send={tp:10001,versions:versions};

	jSONInfo(config,send,function(data){
        var tablas=data.tables;
        $.each(tablas,function(idword,word){
            localStorage.setItem(idword,JSON.stringify(word));
        })
        localStorage.setItem('versions',JSON.stringify(data.versions_new));
        if(typeof CallBack == 'function') CallBack(data);
    });     
}
function BindAll(){
    $('body').on('click','[data-accion="mover"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $Origen=$('[data-form="'+$(this).attr("data-ori")+'"]');
        var $Destino=$('[data-form="'+$(this).attr("data-dest")+'"]');
        $Origen.fadeOut('fast',function(){
            $Destino.fadeIn('fast');
        });
        $.address.value($(this).attr("href")).update();
    });

    /*LINKS MD*/
    $('body').on('click',_FORMS,function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   href=$esto.attr("href")==undefined?$esto.attr("data-href"):$esto.attr("href")
        ,   args={value:href=href,parameters:{}} 
        OpenForm(args);
    });

    $('body').on('click','[href^="/logout"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var config={dir:'/logout/',type:"GET"}
        ,   send={}

        $.ajax({
            url:'/logout',          
            success: function(data) { 
                window.location.reload()
            }
        });
    });


    $('body').on('click','[data-dinamic="true"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var href=$(this).attr("data-href") 
        ,   target=$(this).attr("data-target") 
        if(target=='_blank')    window.open(href);
        else                    $.address.value(href)
    });
    $('body').on('click','[data-href^="/informe"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var href=$(this).attr("data-href")     
        window.location.href=href;
    });

    $('body').on('click tap','[data-option="toggle"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   action=$esto.attr('data-action')
        ,   $nexst=$('[data-option="toggle"]:not([data-action])').not($esto).next(':visible').first()
        
        if(action==undefined)
            var $next=$esto.next().first()
        else
            var $next=$('[data-id="'+action+'"]')

        if($esto.attr("data-bounce")==undefined){
            $esto.attr("data-bounce",'true');
            
            $nexst.slideUp(100)

            if(!$next.hasClass('under-loader'))
                $next.slideToggle(100,function(){
                    $esto.removeAttr("data-bounce");
                    Espacios();
                })
            else
                $next.slideToggle(100,function(){
                    $esto.removeAttr("data-bounce");
                    Espacios();
                }).next().fadeToggle(100)
        }
    });

    /* NAV ICON*/
    $('body').on('click','[data-option="navicon"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   action=$esto.attr('data-action')
        ,   $next=$('[data-id="'+action+'"]')
        ,   OpenNav=!$next.is(':visible')
        if($esto.attr("data-bounce")==undefined){
            $esto.attr("data-bounce",'true');
            NavOpenClose(OpenNav);                   
        }
    });
    $('body').on('click','[data-id="cmodulos"]',function(event){
       //NavOpenClose(false)
    });
    /* NAV NOTIFY */
     $('body').on('click','[data-option="navnotify"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   action=$esto.attr('data-action')        
        ,   $next=$('[data-id="'+action+'"]')

        NavNofOpenClose(!$next.is(':visible'))
    });

    $('body, document').bind('click tap',function(event){
         if((/Android/i.test(navigator.userAgent)||(/iPhone|iPod/i.test(navigator.userAgent))&&$('[data-id="notify"]').is(':visible')))
            NavNofOpenClose(false)
    });

    /*FILTERS*/
    $('body').on('submit','[data-filter="search"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        FilterResumen($esto)
    });
    $('body').on('click','[data-filter="page"],[data-filter="button"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        FilterResumen($esto)
    });
    $('body').on('change','[data-filter="select"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this).find('option:selected')
        FilterResumen($esto)
    });


    $('body').on('click','.s-list .mtitle, .s-ext .mtitle',function(event){
        var $esto=$(this)
        ,   $nexts=$esto.next('.w-results')
        $esto.removeAttr('data-filter')
        if($nexts.is(':visible')){
            $nexts.slideUp(120,function(){
                $esto.find('i').switchClass('fa-caret-up','fa-caret-down')
            })
            
        }
        else{
            $nexts.slideDown(120,function(){
                $esto.find('i').switchClass('fa-caret-down','fa-caret-up')
            })
        }
    });
    $('body').on('click','[data-idtab]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   $others=$esto.siblings()
        ,   tabId=$esto.attr('data-idtab')
        ,   $parent=$esto.parents('.md_carga')
        ,   $tabSel=$parent.find('[data-tab="'+tabId+'"]')
        ,   $tabOther=$parent.find('[data-tab]').not($tabSel)

        $esto.addClass('_selected',150)
        $others.removeClass('_selected',150)
        $tabSel.fadeIn(150)
        $tabOther.fadeOut(150)
    });

    /**************************************************/
    /*********************CLICKS**********************/ 
    /**************************************************/
    $('body').on('click','[data-transictp="10"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   $Box=$('[data-box="'+$esto.attr("data-boxid")+'"]')
        ,   $TDBos=$Box.parent()
        $TDBos.slideToggle('fast',function(){ Espacios() });
    }); 
    $('body').on('click','[data-carga="tabla"]',function(event){
        event.stopPropagation();
        event.preventDefault();
        var $esto=$(this)
        ,   $ctable=$esto.prev('.ctable')
        ,   $cont=$ctable.find('._stabla')
        if($cont.attr('data-carga')!='true'){
            var AtrData=GetAttrs($cont);
            $cont.attr("data-carga","true");
            $esto.slideUp('fast');
            $ctable.slideDown('fast');   
            BuscarOtros($cont,$cont,AtrData);
        }

    }); 

}


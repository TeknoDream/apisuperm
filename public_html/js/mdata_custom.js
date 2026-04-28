_VALIDATE_SKTS=false;
$(document).on("ready",function(){    
    /***************************************/
    /***************************************/
    /***************************************/
    /***************************************/
    if(_notifier.url!=''&&_notifier.sockets!=''&&$('aside.notify').length){
        getScript(_notifier.url,function(){
            _CargaSockets=true
            var $body=$('body')
            if(_PROYECTO==20){
                $('#wrap').addClass('notif',200)         
                $body.append('<audio id="P-1">'
                                +    '<source src="/sound/p-1.ogg" type="audio/ogg">'
                                +    '<source src="/sound/p-1.mp3" type="audio/mpeg">'
                                +'</audio>'
                                +'<audio id="P-3">'
                                +    '<source src="/sound/p-3.ogg" type="audio/ogg">'
                                +    '<source src="/sound/p-3.mp3" type="audio/mpeg">'
                                +'</audio>')          
                socket = io.connect(_notifier.sockets);      
                socket.emit('itsme',dnode,function(datas){
                    PrintNotify(datas);
                    _VALIDATE_SKTS=true;      
                    PutInMarks();             

                })
                socket.on('reconnect', function () {
                    _CargaSockets=true;
                    $('aside.notify').empty();
                    socket.emit('itsme',dnode,function(datas){
                        PrintNotify(datas);
                    })
                })
                socket.on('new_data',function(datas,fn){
                    PrintNotify(datas);
                });
                socket.on('inmap',function(datas,fn){
                    var $InMap=$('[data-id="users"]')
                    if(!$InMap.length)  socket.emit('noGetLats')
                    else{
                        for (var maps in GMaps){
                            if(GMaps[maps].id=='users') break;
                        }     
                        var Map=GMaps[maps]
                        ,   id=datas[0].id
                        ,   loc=datas[0].ubic
                        if(datas[0].show==0){                    
                            if(Map.TMap.markers[id]!=undefined){
                                Map.TMap.markers[id].setMap(null)
                                delete Map.TMap.markers[id];
                            }
                            if(Map.TMap.info[id]!=undefined){
                                Map.TMap.info[id].setMap(null)
                                delete Map.TMap.info[id];
                            }
                        }
                        else{
                            if(datas[0].validate==1){
                                var name=datas[0].user.name==''&&datas[0].user.lastname==''?datas[0].user.email:datas[0].user.name+' '+datas[0].user.lastname
                                ,   content='<span>'
                                        +       'user: '+name+'<br />'
                                        +       'url: '+datas[0].ubic.url
                                        +   '</span>'
                                ,   icon='/img/pin-red.png'
                            }
                            else{
                                var content='<span>'
                                        +       'no validate: '+datas[0].id+'<br />'
                                        +       'url: '+datas[0].ubic.url
                                        +   '</span>'
                                ,   icon='/img/pin-gray.png'
                            }
                            if(Map.TMap.markers[id]==undefined)                            
                                CreateMarker(Map.TMap,loc,id,content,{icon:icon})
                            else{
                                var latlng = new google.maps.LatLng(loc.lat,loc.lon);
                                Map.TMap.markers[id].setPosition(latlng)
                                Map.TMap.markers[id].setIcon(icon)
                                Map.TMap.info[id].setContent(content)
                            }
                        }
                    }
                });

                socket.on('status',function(datas,fn){
                    var $body=$('body')
                    ,   _STATUS=datas.status
                    ,   _PEND=_STATUS==1
                    ,   IntID='906_'+datas.idCart
                    if(_PEND){
                        var $Sound=$('#DingSound')
                        if($Sound.length==0)
                            var $Sound=$('<audio id="DingSound">'
                                        +    '<source src="/sound/ding.ogg" type="audio/ogg">'
                                        +    '<source src="/sound/ding.mp3" type="audio/mpeg">'
                                        +'</audio>').appendTo($body)
                        
                        $Sound[0].play()
                    }
                    else
                        clearInterval(Intervals[IntID]);

                    var versions=JSON.parse(localStorage.getItem('versions'))
                    ,   config={dir:'/autocomplete/',type:"GET"}
                    ,   send={tp:9061,idCart:datas.idCart};
                    jSONInfo(config,send,function(data){
                        var $tblPedidos=$('#tblPedidos')
                        if($tblPedidos.length){
                            var $tBody=$tblPedidos.find('tbody').first()
                            ,   id=data[0].id
                            ,   $prevTr=$tBody.find('[data-id="'+id+'"] > td')
                            if($prevTr.length)
                                $.when($prevTr.slideUp(250)).then(function(){
                                    $prevTr.remove()
                                    InsertIntoOpfrmFila(data,$tBody);
                                })
                            else
                                InsertIntoOpfrmFila(data,$tBody);
                        }
                        if(data[0].alert!=undefined&&_WEBNOTIF){
                            var _push=data[0].alert
                            ,   _tag=_push.options.tag  
                            if(_push.status){                      
                                _push.options.body=FindAsist(_push.options.body)
                                _push.options.icon='http:'+imgUrl+_push.options.icon['t03']
                                var title = FindAsist(_push.title)
                                ,   options = _push.options
                                ,   Notif = new Notification(title,options);
                                _NOTIF[_tag]=Notif                               
                                if(_push.click!=undefined){
                                    Notif.addEventListener('click',function (){ 
                                      $.address.value(_push.click);
                                      Notif.close()
                                    })
                                }
                                
                            }
                            else{
                                 if(_NOTIF[_tag]!=undefined)     
                                    _NOTIF[_tag].close()
                            }
                           
                        }
                   
                    });  
                      
                });
                socket.on('error', function(){
                    socket.socket.reconnect();
                    _CargaSockets=false;
                });
                
            }
        })
    }
})

var PutInMarks=function(){
    var $InMap=$('[data-id="users"]')
    ,   emit=$InMap.attr('data-emit')
    if(_VALIDATE_SKTS&&emit!=undefined){
        for (var maps in GMaps){
            if(GMaps[maps].id=='users') break;
        }
        var Map=GMaps[maps]
        for (var maker in  Map.TMap.markers){
            Map.TMap.markers[maker].setMap(null)
            Map.TMap.info[maker].setMap(null)
        }
        socket.emit(emit,function(datarec){
            $.each(datarec,function(index,dataEach){
                var id=dataEach.id
                ,   loc=dataEach.ubic
                if(dataEach.validate==1){
                    var name=dataEach.user.name==''&&dataEach.user.lastname==''?dataEach.user.email:dataEach.user.name+' '+dataEach.user.lastname
                    ,   content='<span>'
                            +       'user: '+name+'<br />'
                            +       'url: '+dataEach.ubic.url
                            +   '</span>'
                    ,   icon='/img/pin-red.png'
                }
                else{
                    var content='<span>'
                            +       'no validate: '+dataEach.id+'<br />'
                            +       'url: '+dataEach.ubic.url
                            +   '</span>'
                    ,   icon='/img/pin-gray.png'
                }
                CreateMarker(Map.TMap,loc,id,content,{icon:icon})
            })
        })
    }
}

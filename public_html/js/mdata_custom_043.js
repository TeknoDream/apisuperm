_VALIDATE_SKTS=false;
var flightPath=[]
var OtroPin=[]
var AlreadySetMap=false
$(document).on("ready",function(){    
    /***************************************/
    /***************************************/
    /***************************************/
    /***************************************/
    if(_notifier.url!=''&&_notifier.sockets!=''&&$('aside.notify').length){
        getScript(_notifier.url,function(){
            _CargaSockets=true
            var $body=$('body')
            
            //$('#wrap').addClass('notif',200)         
            $body.append('<audio id="P-1">'
                        +    '<source src="/sound/whistle.mp3" type="audio/mpeg">'
                        +'</audio>')          
            socket = io.connect(_notifier.sockets);      
            socket.emit('itsme',dnode,function(datas){                
                _VALIDATE_SKTS=true;  
                console.log(datas);      

            })
            socket.on('reconnect', function () {
                _CargaSockets=true;
                $('aside.notify').empty();
                socket.emit('itsme',dnode,function(datas){
                })
            })
            socket.on('new_data',function(datas,fn){
            });                

            socket.on('newalert',function(datas,fn){
                var $noti=$('[data-id="notify"]')
                $(  '<div class="notif" style="padding:0.5em 0; font-size:1.3em; border-top:1px solid #FFF; cursor:pointer" data-id="AL'+datas.moto+'" >'
                    +   '<strong>Alarma de moto</strong><br />'
                    +   '<span>Nombre: '+datas.names+'</span><br />'
                    +'</div>').prependTo($noti)  

                alert('Alerta Generada por: '+datas.names);
                var $Sound=$('#BeepSound')
                if($Sound.length==0)
                    var $Sound=$('<audio id="BeepSound">'
                                +    '<source src="/sound/censor-beep-01.mp3" type="audio/mpeg">'
                                +'</audio>').appendTo($body)                
                $Sound[0].play()

            });
            socket.on('error', function(){
                socket.socket.reconnect();
                _CargaSockets=false;
            });  

            
            //Recibe nueva solicitud
            socket.on('newsol',function(datas,fn){
                console.log(datas)

                var $body=$('body')
                ,   _STATUS=datas.status_sol
                ,   _PEND=_STATUS==0
                ,   IntID='6002_'+datas.sol
                

                if(datas.sound==1){
                    var $Sound1=$('#DingSound')
                    if($Sound1.length==0)
                        var $Sound1=$('<audio id="DingSound">'
                                    +    '<source src="/sound/woop.ogg" type="audio/ogg">'
                                    +    '<source src="/sound/woop.mp3" type="audio/mpeg">'
                                    +'</audio>').appendTo($body)
                    
                    $Sound1[0].play()
                }
                else if(datas.sound==2){
                    var $Sound2=$('#P2')
                    if($Sound2.length==0)
                        var $Sound2=$('<audio id="P2">'
                                    +    '<source src="/sound/p-2.ogg" type="audio/ogg">'
                                    +    '<source src="/sound/p-2.mp3" type="audio/mpeg">'
                                    +'</audio>').appendTo($body)
                    
                    $Sound2[0].play()
                }
                if(!_PEND)  clearInterval(Intervals[IntID]);

                var config={dir:'/autocomplete/',type:"GET"}
                ,   send={tp:60021,idSol:datas.sol};
                jSONInfo(config,send,function(data){
                    var $tblPedidos=$('#tblPedidos')
                    if($tblPedidos.length){
                        var $tBody=$tblPedidos.find('tbody').first()
                        ,   id=data[0].id
                        ,   $prevTr=$tBody.find('[data-id="'+id+'"] > td')
                        if($prevTr.length)
                            $.when($prevTr.slideUp(100)).then(function(){
                                $prevTr.parents('tr').remove()
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
                             if(_NOTIF[_tag]!=undefined)   _NOTIF[_tag].close()
                        }                       
                    }
               
                });  

                var $InMap=$('[data-id="users"]')                
                if($InMap.length)  PutMarkers(datas);
            });

            //Recibe dato de nueva ubicación
            socket.on('inmap',function(datas,fn){
                //console.log(datas);
                var $InMap=$('[data-id="users"]')                
                if($InMap.length){
                    PutMarkers(datas);
                }


                var $noti=$('[data-id="notify"]')
                ,   $bloq=$('[data-id="'+datas.moto+'"]')
                if($bloq.length)    $bloq.unbind().remove()
                $bloq=$(  '<div class="notif" style="padding:0.5em 0; font-size:1.3em; border-top:1px solid #FFF; cursor:pointer" data-id="'+datas.moto+'" >'
                        +   '<strong>Movimiento de moto</strong><br />'
                        +   '<span>Nombre: '+datas.names+'</span><br />'
                        +   '<span>Placa: '+datas.placa+'</span><br />'
                        +   '<span>Precisión: '+datas.accuracy+'</span><br />'
                        +   '<span>Versión: '+datas.vcode+'</span><br />'
                        +   '<span>Hora: '+datas.status_date+'</span>'
                        +'</div>').prependTo($noti)             

                $bloq.bind('click',function(){
                    var Map=GetMap()
                    ,   latlng = new google.maps.LatLng(datas.lat,datas.lng);
                    Map.TMap.map.panTo(latlng)
                    Map.TMap.map.setZoom(15)
                })
            }); 
        });
    }
    
})

var GetMap=function(){
    var Map=null
    for (var maps in GMaps){
        if(GMaps[maps].id=='users'){
            Map=GMaps[maps]
            break;
        }
    }    
    return Map;
}

var PutMarkers=function(datas){
    var Map=GetMap();
    if(datas instanceof Array){
        for(data in datas){
            PutSingleMarker(datas[data],Map)
        }
    }
    else{
        PutSingleMarker(datas,Map)
    }
    SetClickMap();
}

var PutMoto=function(datas,Map){
    var id='moto-'+datas.moto
    if(datas.show==0){    
        SetDelMark(id)
    }
    else{
        var id='moto-'+datas.moto
        ,   loc={lat:datas.lat,lon:datas.lng}
        ,   content='<div>'
                +       '<div class="wrapimg">'
                +           '<div class="_fix"></div>'
                +           '<div class="_zfix_01 stdImg" style="background-image:url('+(datas.display.prefix+datas.display.t01)+')"></div>'
                +       '</div>'
                +       '<p><strong>'+FindAsist('txt-132-1')+':</strong><br /><span>'+datas.names+'</span></p>'
                +       '<p><strong>'+FindAsist('txt-123-0')+':</strong><br /><span>'+datas.placa+'</span></p>'
                +       '<p><strong>'+FindAsist('txt-1140-0')+':</strong><br /><span>'+FindAsist('txt-'+(164+parseInt(datas.status_moto))+'-1')+'</span></p>'
                +       (datas.status_date!=''&&datas.status_date!=null?
                            '<p><strong>'+FindAsist('txt-170-0')+':</strong><br /><span>'+datas.status_date+'</span></p>'
                        :   '<p><strong>'+FindAsist('txt-170-0')+':</strong><br /><span>'+FindAsist('txt-172-1')+'</span></p>')
                +       '<p><strong>'+FindAsist('txt-156-0')+':</strong><br /><span>'+FindAsist('txt-'+(155+parseInt(datas.type_moto))+'-1')+'</span></p>'
                +       (parseInt(datas.idsol)!=0&&datas.idsol!=null?
                            '<p><strong>'+FindAsist('txt-171-0')+':</strong><br /><span>'+FindAsist('txt-171-1')+'-'+datas.idsol+'</span></p>'
                        :   '<p><strong>'+FindAsist('txt-171-0')+':</strong><br /><span>'+FindAsist('txt-172-1')+'</span></p>')
                +   '</div>'
        if(datas.status_moto==1||datas.status_moto==2||datas.status_moto==3)
            var icon='/img/moto_'+datas.status_moto+'.png'
        else
            var icon='/img/moto.png'
        
        if(Map.TMap.markers[id]==undefined)                            
            CreateMarker(Map.TMap,loc,id,content,{icon:icon})
        else{
            var latlng = new google.maps.LatLng(loc.lat,loc.lon)
            ,   Marker=Map.TMap.markers[id]
            
            Marker.setPosition(latlng)
            Marker.setIcon(icon) 
            Marker.setMap(Map.TMap.map)
            Map.TMap.info[id].setContent(content)
        }
    }
}

var PutSolicitud=function(datas,Map){
    var id='sol-'+datas.sol
    ,   showInMap=datas.status_sol==0||datas.status_sol==1||datas.status_sol==4

    if(datas.show==0||!showInMap){    
        SetDelMark(id)
    }
    else{
        var config={dir:'/autocomplete/',type:"GET"}
        ,   send={tp:60022,idSol:datas.sol};  

        if(datas.status_sol==0||datas.status_sol==1)
            var loc={lat:datas.lat,lon:datas.lng}
        else
            var loc={lat:datas.lat2,lon:datas.lng2}

        jSONInfo(config,send,function(dtInfo){
            var steps=dtInfo.steps
            ,   content='<div>'
                    +       '<p><strong>'+FindAsist('txt-171-1')+':</strong><br /><span>'+dtInfo.id+'</span></p>'
                    +       '<p><strong>'+FindAsist('txt-148-0')+':</strong><br /><span>'+FindAsist('txt-'+(148+parseInt(dtInfo.status))+'-1')+'</span></p>'
                    +       (dtInfo.status_date!=''&&dtInfo.status_date==null?
                                '<p><strong>'+FindAsist('txt-170-0')+':</strong><br /><span>'+dtInfo.date+'</span></p>'
                            :   '<p><strong>'+FindAsist('txt-170-0')+':</strong><br /><span>'+FindAsist('txt-172-1')+'</span></p>')
                    +       (parseInt(dtInfo.moto_id)==0||dtInfo.moto_id==null?
                                    '<p><strong>'+FindAsist('txt-137-0')+':</strong><br /><span>'+FindAsist('txt-173-1')+'</span></p>'
                                :   '<p><strong>'+FindAsist('txt-137-0')+':</strong><br /><span>'+dtInfo.moto_placa+'</span></p>'
                                +   '<p><strong>'+FindAsist('txt-132-1')+':</strong><br /><span>'+dtInfo.monto_name+'</span></p>')

                    +       (dtInfo.edit!=''?
                                    '<p style="padding: 0.3em 0;text-align:center;"><button class="col_menu_t02 button" data-href="'+dtInfo.edit+'" data-transictp="1">'+FindAsist('txt-193-0')+'</button></p>'
                                :   '')
                    +       (dtInfo.cancel!=''?
                                    '<p style="padding: 0.3em 0;text-align:center;"><button class="col_menu_t02 button" data-href="'+dtInfo.cancel+'" data-transictp="1">'+FindAsist('txt-179-0')+'</button></p>'
                                :   '')
                    +       (dtInfo.asignar!=''?
                                    '<p style="padding: 0.3em 0;text-align:center;"><button class="col_menu_t02 button" data-href="'+dtInfo.asignar+'" data-transictp="1">'+FindAsist('txt-163-0')+'</button></p>'
                                :   '')

                    +       (dtInfo.asignar!=''?
                                    '<p style="padding: 0.3em 0;text-align:center;"><button class="col_menu_t02 button" data-href="'+dtInfo.reasignar+'" data-transictp="1">'+FindAsist('txt-164-0')+'</button></p>'
                                :   '')
                    +   '</div>'
            if(dtInfo.status==0)            //No tiene motorizado o tiene tiempo 0 o distancia 0
                var icon='/img/pin-red.png'
            else if(dtInfo.status==1)       //Confirmado por el usaurio
                var icon='/img/pin-yellow.png'
            else if(dtInfo.status==4)       //Cuando se recoge y va para la entrega
                var icon='/img/pin-purple.png'       
            else 
                var icon='/img/pin-gray.png'

           

            if(Map.TMap.markers[id]==undefined){               
                var Marker=CreateMarker(Map.TMap,loc,id,content,{icon:icon})
                if(dtInfo.moto_id=="0"||dtInfo.cost_verif=="0")    Marker.setAnimation(google.maps.Animation.BOUNCE);
            }
            else{
                var latlng = new google.maps.LatLng(loc.lat,loc.lon)
                ,   Marker=Map.TMap.markers[id]

                Marker.setIcon(icon)
                Marker.setPosition(latlng)                
                Marker.setMap(Map.TMap.map)                
                Map.TMap.info[id].setContent(content)

                if(dtInfo.moto_id=="0"||dtInfo.cost_verif=="0")    
                    Marker.setAnimation(google.maps.Animation.BOUNCE);
                else    
                    Marker.setAnimation(null);
            }

            google.maps.event.addListener(Marker, 'mouseup', function(){
                SetClearClick()
                var MyInfo=Map.TMap.info[id]
                MyInfo.open(Map.TMap.map,Marker);

                flightPath[id] = new google.maps.Polyline({
                        path:steps
                    ,   geodesic: true
                    ,   strokeColor: '#CC1228'
                    ,   strokeOpacity: 0.7
                    ,   strokeWeight: 3
                });                    
                flightPath[id].setMap(Map.TMap.map);

                var icon='/img/pin-green.png'
                if(dtInfo.status==0||dtInfo.status==1)
                    var loc={lat:dtInfo.end.loc.lat,lon:dtInfo.end.loc.lng}
                else
                    var loc={lat:dtInfo.ini.loc.lat,lon:dtInfo.ini.loc.lng}

                OtroPin[id]=marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(loc.lat, loc.lon)
                                    ,   map: Map.TMap.map
                                    ,   icon:icon})
                
                if(dtInfo.moto_id!=0){
                    var MotoIdSel='moto-'+dtInfo.moto_id
                    Map.TMap.markers[MotoIdSel].setAnimation(google.maps.Animation.BOUNCE);
                }
                
            });
                        
        });
        
    }
}



var PutSingleMarker=function(datas,Map){
    if(datas.type=='moto'){
        PutMoto(datas,Map)
    }
    else{
        PutSolicitud(datas,Map)
    }
}

var SetDelMark=function(id){
    var Map=GetMap()
    if(Map.TMap.markers[id]!=undefined){
        Map.TMap.markers[id].setMap(null)
        delete Map.TMap.markers[id];
    }
    if(Map.TMap.info[id]!=undefined){
        Map.TMap.info[id].setMap(null)
        delete Map.TMap.info[id];
    }

    if(id.substring(0,4)=='sol-'){
        if(flightPath[id]!=undefined)   flightPath[id].setMap(null)
        if(OtroPin[id]!=undefined)      OtroPin[id].setMap(null)
    }
}

var SetClearClick=function(){
    var Map=GetMap()
    for (var fp in flightPath){
        flightPath[fp].setMap(null)
    }
    for (var op in OtroPin){
        OtroPin[op].setMap(null)
    }
    for(var id in Map.TMap.info){
        Map.TMap.info[id].close();
    }
    for(var id in Map.TMap.markers){
        if(id.substring(0,5)=='moto-')  Map.TMap.markers[id].setAnimation(null);
    }
}

var SetClickMap=function(){
    if(!AlreadySetMap&&GetMap()!=null){
        var Map=GetMap()
        ,   MiMapa=Map.TMap.map
        MiMapa.addListener('click', function() {            
            SetClearClick();            
        });
        AlreadySetMap=true;
    }
}
var rotate=function(rotate) {
    var Map=GetMap()
    ,   map=Map.TMap.map
    map.setHeading(90)
    map.setZoom(18);
}



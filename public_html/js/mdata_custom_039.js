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
            
            //$('#wrap').addClass('notif',200)         
            $body.append('<audio id="P-1">'
                        +    '<source src="/sound/p-2.mp3" type="audio/mpeg">'
                        +'</audio>')          
            socket = io.connect(_notifier.sockets);      
            
            socket.emit('itsme',dnode,function(datas){                
                _VALIDATE_SKTS=true;  
                var $InMap=$('[data-id="users"]')                
                if($InMap.length)   PutInMarks();
                console.log(datas);      

            })
            socket.on('reconnect', function () {
                _CargaSockets=true;
                $('aside.notify').empty();
                socket.emit('itsme',dnode,function(datas){
                })
            })                     

            
            socket.on('error', function(){
                socket.socket.reconnect();
                _CargaSockets=false;
            });  

            
            //Recibe nueva solicitud
            socket.on('newcompra',function(datas,fn){
                console.log(datas)

                var $body=$('body')
                ,   _SOUND=datas.sound
                ,   IntID='504_'+datas.idPedido
                if(_SOUND){
                    var $Sound=$('#DingSound')
                    if($Sound.length==0)
                        var $Sound=$('<audio id="DingSound">'
                                    +    '<source src="/sound/p-2.ogg" type="audio/ogg">'
                                    +    '<source src="/sound/p-2.mp3" type="audio/mpeg">'
                                    +'</audio>').appendTo($body)
                    
                    $Sound[0].play()
                }
                else
                    clearInterval(Intervals[IntID]);

                var config={dir:'/autocomplete/',type:"GET"}
                ,   send={tp:5040,idPed:datas.idPedido};
                jSONInfo(config,send,function(data){
                    var $tblPedidos=$('#tblPedidos')
                    if($tblPedidos.length){
                        var $tBody=$tblPedidos.find('tbody').first()
                        ,   id=data[0].id
                        ,   $prevTr=$tBody.find('[data-id="'+id+'"] > td')
                        if($prevTr.length)
                            $.when($prevTr.slideUp(250)).then(function(){
                                $prevTr.parents('tr').remove()
                                InsertIntoOpfrmFila(data,$tBody);
                            })
                        else
                            InsertIntoOpfrmFila(data,$tBody);
                    }
                    /*if(data[0].alert!=undefined&&_WEBNOTIF){
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
                    } */              
                });            
            });            
        });
    }    
})

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
            
            $('#wrap').addClass('notif',200)         
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

            
            socket.on('error', function(){
                socket.socket.reconnect();
                _CargaSockets=false;
            });            
        })
    }
})
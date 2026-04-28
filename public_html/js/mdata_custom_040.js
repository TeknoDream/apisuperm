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
            socket.on('newrenta',function(datas,fn){
                console.log(datas)

                var $body=$('body')
                ,   $Mod508=$('#MOD508')
                ,   $Mod552=$('#MOD552')
                ,   $Mod553=$('#MOD553')
                ,   tp=$Mod552.length?5520:($Mod553.length?5530:5080)
                ,   _STATUS=datas.status
                ,   _PEND=_STATUS==2||_STATUS==3||_STATUS==12   
                ,   idItem=datas.idItem            
                ,   idItemLast=datas.idItemLast==undefined?'':datas.idItemLast


                if(_PEND){
                    var $Sound=$('#DingSound')
                    if($Sound.length==0)
                        var $Sound=$('<audio id="DingSound">'
                                    +    '<source src="/sound/p-2.ogg" type="audio/ogg">'
                                    +    '<source src="/sound/p-2.mp3" type="audio/mpeg">'
                                    +'</audio>').appendTo($body)
                    
                    $Sound[0].play()
                }

                var config={dir:'/autocomplete/',type:"GET"}
                ,   send={tp:tp,idItem:idItem,idItemLast:idItemLast};
                jSONInfo(config,send,function(data){
                    var $tblPedidos=$()
                    if($Mod508.length)
                        $tblPedidos=$Mod508
                    else if($Mod552.length)
                        $tblPedidos=$Mod552
                    else if($Mod553.length)
                        $tblPedidos=$Mod553

                    if($tblPedidos.length)  var $tBody=$tblPedidos.find('tbody').first()

                    $.each(data,function(ix,dt){                        
                        if($tblPedidos.length){
                            var id=dt.id
                            ,   $prevTr=$tBody.find('[data-id="'+id+'"] > td')
                            $prevTr.parents('tr').remove()
                        }

                        if(dt.alert!=undefined&&_WEBNOTIF){
                            var _push=dt.alert
                            ,   _tag=_push.options.tag  
                            if(_push.status){                      
                                _push.options.body=FindAsist(_push.options.body)
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
                    if($tblPedidos.length)  InsertIntoOpfrmFila(data,$tBody);          
                });               
            });            
        });
    }    
})

var CalcConv=function(rentCurrency,rentCurrencyPrefix,_USD){
    var     _CONV=1
        ,   CurrPrefix=rentCurrencyPrefix
        ,   currency='COP'
    if(rentCurrency=='COP'&&currency=='USD'){
        _CONV=1/_USD;
        CurrPrefix='USD $'
    }
    else if(rentCurrency=='USD'&&$currency=='COP'){
        _CONV=_USD;
        CurrPrefix='$'
    }

    return {conv:_CONV,prefix:CurrPrefix};
}
var YearDiff=function(YearF,YearI){
    var AnioF=YearF.getYear()
    ,   AnioI=YearI.getYear()
    ,   rta=AnioF-AnioI
    ,   MesF=YearF.getMonth()
    ,   MesI=YearI.getMonth()
    ,   DiaF=YearF.getDate()
    ,   DiaI=YearI.getDate()

    if((MesI>MesF)||(MesI==MesF&&DiaI>DiaF))   rta=rta-1;
    
    return rta
}
var MasImpuesto=function(valor,impuestos){
    var val=Number(impuestos)
    return Math.round(valor*(1+(val/100)),0)
}
var AplicatRt=function(valor,impuestos){
    var val=Number(impuestos)
    return Math.round(valor*(val/100),0)
}

function addCurrency(conv,pref,nStr)
{
    var numero=Number(nStr)
    numero=Math.round(numero*conv)
    return (pref+' '+addCommas(numero));
}

var CalcularPrecio=function(){
    var cod=$('[name="cod"]:checked').val()
    if(cod!=undefined){
        var   isPromo=$('[name="promo['+cod+']"]').val()=="1"
        ,   promo_type=$('[name="promo_type['+cod+']"]').val()
        ,   promo_val=$('[name="promo_val['+cod+']"]').val()
        ,   rec_dif_entr=Number($('[name="rec_dif_entr['+cod+']"]').val())
        ,   cost_otr_agen=Number($('[name="cost_otr_agen['+cod+']"]').val())
        ,   cost_edmin=Number($('[name="cost_edmin['+cod+']"]').val())
        ,   edmin=Number($('[name="edmin['+cod+']"]').val())
        ,   sucursal=$('[name="sucursal"]:checked').val()
        ,   dhour=Number($('[name="dhour['+sucursal+']"]').val())
        ,   apert_anticip=Number($('[name="apert_anticip['+sucursal+']"]').val())
        ,   dias=Number($('[name="diasReal['+cod+']"]').val())
        ,   impuesto=Number($('[name="suma_imp['+cod+']"]').val())
        ,   diasReal=dias
        ,   hours_rent=Number($('[name="hours_rent['+cod+']"]').val())
        ,   hext=$('[name="hext['+cod+']"]').val()
        ,   limdia=Number($('[name="limdia['+cod+']"]').val())
        ,   cost_h_extra=0
        ,   priceShow=Number($('[name="price['+cod+']"]').val())
        ,   cost_otr_agen=Number($('[name="cost_otr_agen['+cod+']"]').val())
        ,   show_hext=Number($('[name="show_hext['+cod+']"]').val())
        ,   valDolar=Number($('[name="show_hext['+cod+']"]').val())
        ,   rentCurrency=$('[name="show_hext['+cod+']"]').val()
        ,   rentCurrencyPrefix=$('[name="rentCurrencyPrefix['+cod+']"]').val()
        ,   $adicional=$('[name^=adicional]:checked')
        ,   user_cumple=$('[name="fecha_nac"]').val()
        ,   tneg=$('[name="tneg"]:checked').val()
        ,   e_tdoc=$('[name="e_tdoc"]').val()
        ,   CalMoneda=CalcConv(rentCurrency,rentCurrencyPrefix,valDolar)
        ,   _CONV=CalMoneda.conv
        ,   _PREFIX_CUR=CalMoneda.prefix
        ,   diasAdicional=diasReal+(hours_rent!=0?1:0)
        
        if(tneg=="1") e_tdoc="0";

        if(show_hext==1){
            if(hext=='hora'&&hours_rent>=limdia) {
                diasReal++
                hours_rent=0
            }
            else if(hext=='hora')  {
                cost_h_extra=Math.round((priceShow/limdia)*hours_rent)
            }
            else if(hours_rent!=0){
                diasReal++
                hours_rent=0
            }
        }
        else if(hext=='hora'&&hours_rent>=limdia) {
            diasReal++
            hours_rent=0
        }

        var sub_s_total=(priceShow*diasReal)
        ,   sub_total=sub_s_total+cost_h_extra
        ,   dto_total=0
        ,   total_a_impuesto=0

        if(isPromo){
            if(promo_type=='por'){
                dto_total=Math.round(sub_total*(parseInt(promo_val)/100));           
            }
            else{
                dto_total=parseInt(promo_val)*priceShow;
            }
        }

        var $recargos=$('<div />')
        ,   $adicionales=$('<div />')
        ,   addProd=0
        ,   addRec=0
        ,   countAdd=0
        ,   countRec=0

        /* RECARGOS */
        $recargos.append('<h4 class="h4Title">Recargos</h4>')
        if(rec_dif_entr!=0){
            var valFormat=MasImpuesto(rec_dif_entr,impuesto)
            ,   valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
            addRec=addRec+rec_dif_entr
            countRec++;
            $recargos.append('<div class="_line"><strong>Entrega en otra ciudad</strong>: <span>'+valMoneda+'</span></div>')
        }    
        if(cost_otr_agen!=0){
            var valFormat=MasImpuesto(cost_otr_agen,impuesto)
            ,   valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
            addRec=addRec+cost_otr_agen
            countRec++;
            $recargos.append('<div class="_line"><strong>Entrega en otra agencia</strong>: <span>'+valMoneda+'</span></div>')
        }

        /* ADICIONAL POR HORA */
     
        if(sucursal!=null){                     
            if(dhour==1&&apert_anticip!=0){            
                var     valFormat=MasImpuesto(apert_anticip,impuesto)
                ,       valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
                addRec=addRec+apert_anticip
                countRec++
                $recargos.append('<div class="_line"><strong>Atención Extra Horaria</strong>: <span>'+valMoneda+'</span></div>')
            }
        }
        /* PAGO POR EDAD MINIMA */

        var bdate= new Date(user_cumple)
        ,   hoy=new Date()
        ,   ydif=YearDiff(hoy,bdate)    
        ,   Rtf=e_tdoc=="2"?4:0
        if(edmin>ydif&&cost_edmin!=0){
            var     valFormat=MasImpuesto(cost_edmin*diasAdicional,impuesto)
            ,       valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
            addRec=addRec+(cost_edmin*diasAdicional)
            countRec++;
            $recargos.append('<div class="_line"><strong>Recargo por edad</strong>: <span>'+valMoneda+'</span></div>')
        }
        



        /* CALCULO DE ADICIONALES */
        $adicionales.append('<h4 class="h4Title">Adicionales</h4>')
        $adicional.each(function(index,data){
            var rindex=$(this).val()
            ,   ad_valor=Number($('[name="adicional_val['+rindex+']"]').val())
            ,   ad_type=Number($('[name="adicional_type['+rindex+']"]').val())
            ,   ad_name=$('[name="adicional_name['+rindex+']"]').val()
            ,   ad_cant=Number($('[name="adicional_cnt['+rindex+']"]').val())
            countAdd++;
            if(ad_type==1){
                addProd=addProd+ad_valor
                var     valFormat=MasImpuesto(ad_valor,impuesto)
                ,       valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
                $adicionales.append('<div class="_line"><strong>'+ad_name+'</strong>: <span>'+valMoneda+'</span></div>')
            }
            else if(ad_type==2){
                addProd=addProd+(ad_valor*diasAdicional)
                var     valFormat=MasImpuesto(ad_valor*diasAdicional,impuesto)
                ,       valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
                $adicionales.append('<div class="_line"><strong>'+ad_name+' ('+diasAdicional+' dias)</strong>: <span>'+valMoneda+'</span></div>')
            }
            else if(ad_type==3){
                addProd=addProd+(ad_valor*ad_cant)
                var     valFormat=MasImpuesto(ad_valor*ad_cant,impuesto)
                ,       valMoneda=addCurrency(_CONV,_PREFIX_CUR,valFormat)
                $adicionales.append('<div class="_line"><strong>'+ad_name+' ('+ad_cant+' dias)</strong>: <span>'+valMoneda+'</span></div>')
            }
        })
       

        /* CALCULO DE IMPUESTOS */
        

        var Total_Antes_IMP=(sub_total-dto_total)+addProd+addRec
        ,   Total_Con_IVA=MasImpuesto(Total_Antes_IMP,impuesto)
        ,   RteFuente=AplicatRt(Total_Antes_IMP,Rtf)
        ,   $valores=$('[data-tipos="valores"]')
        ,   $total=$('[data-tipos="total"]')
        ,   $rtf=$('[data-tipos="rtf"]')
        ,   sub_total_show=addCurrency(_CONV,_PREFIX_CUR,MasImpuesto(sub_total,impuesto))
        ,   dto_total_show=addCurrency(_CONV,_PREFIX_CUR,MasImpuesto(dto_total,impuesto))


        if(hours_rent!=0){
            if(hours_rent==1)
                var $subTotal=$('<div class="_line"><strong>Valor por '+diasReal+' dias y 1 hora</strong>: <span>'+sub_total_show+'</span></div>')
            else
                var $subTotal=$('<div class="_line"><strong>Valor por '+diasReal+' dias y '+hours_rent+' horas</strong>: <span>'+sub_total_show+'</span></div>')
        }
        else 
            var $subTotal=$('<div class="_line"><strong>Valor por '+diasReal+' dias</strong>: <span>'+sub_total_show+'</span></div>') 

        var $dto_total=$('<div class="_line"><strong>Descuento Promoción</strong>: <span>'+dto_total_show+'</span></div>')
        
        $valores.empty()
        $valores.append($subTotal)

        if(show_hext==0&&hours_rent!=0){
            if(hours_rent==1)
                $valores.append('<div class="_line">Esta reserva cuenta con 1 hora extra, que sera cargada por la Rentadora al momento de retirar el vehículo</div>' )
            else
                $valores.append('<div class="_line">Esta reserva cuenta con '+hours_rent+' horas extras, que seran cargadas por la Rentadora al momento de retirar el vehículo</div>' )
        }

        else if(cost_h_extra!=0){
            var cost_h_extra_iva=MasImpuesto(cost_h_extra,impuesto)
            if(hours_rent==1)
                $valores.append('<div class="_line">La hora extra de esta reserva por valor de '+addCurrency(_CONV,_PREFIX_CUR,cost_h_extra_iva)+' ya está cargada en el precio final</div>' )
            else
                $valores.append('<div class="_line">Las '+hours_rent+' horas extras de esta reserva por valor de '+addCurrency(_CONV,_PREFIX_CUR,cost_h_extra_iva)+' ya están cargadas en el precio final</div>' )
        }
        if(dto_total!=0)    $valores.append($dto_total)
        if(countRec!=0)     $valores.append($recargos)
        if(countAdd!=0)     $valores.append($adicionales)



        $total.html('<div class="_line"><strong>Total</strong>: <span>'+addCurrency(_CONV,_PREFIX_CUR,(Total_Con_IVA-RteFuente))+'</span></div>')
        if(Rtf!=0){
            $rtf.html('Se aplicó una retención del '+Rtf+'% ('+addCurrency(_CONV,_PREFIX_CUR,RteFuente)+')<br />Recuerde llevar RUT y Cámara de Comercio');
            $rtf.slideDown('fast')
        }
        else{
            $rtf.slideUp('fast')
        }
    }

    return {    sub_total:sub_total
            ,   rtf:RteFuente
            ,   cost_h_extra:cost_h_extra
            ,   dto_total:dto_total
            ,   addRec:addRec
            ,   addProd:addProd}
}

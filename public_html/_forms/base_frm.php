<?php
$loadcont=isset($_REQUEST["loadcont"])?$_REQUEST["loadcont"]:0;
$md=isset($_GET["md"])?$_GET["md"]:'';
$id_sha=mb_substr($md,0,40);
$c_sha=mb_substr($md,40,32);
$id_sha_t=mb_substr($md,72,40);
$det_plus=intval(mb_substr($md,112,3));
$id_sha_t2=mb_substr($md,115,40);

$sWhere=encrip_mysql('adm_ventanas.ID_VENTANA',2);
$s=$sqlCons[1][71]." WHERE $sWhere=:c_sha LIMIT 1";
$req = $dbEmpresa->prepare($s); 
$req->bindParam(':c_sha', $c_sha);
$req->execute();	
if($reg = $req->fetch()){
	$cnf=$reg["ID_VENTANA"];
}


if($cnf==36) 	$permiso=$PermisosA[8]["P"];
else 			$permiso=$PermisosA[$cnf]["P"];


if(nuevo_item()==$id_sha) $nuevo=true;
else $nuevo=false;

$ArrayTempl=array(
            'PROYECTO'=>$_PROYECTO
        ,   'EMPRESA'  =>$_EMPRESA
        ,   'MODULO'    =>0
        ,   'OBJETO'    =>0
        ,   'TP'        =>'img'
        ,   'EXT'       =>'jpg'
        ,   'All'       =>false
        ,   'Cual'      =>'t02');

$ArrayImg=$ArrayTempl;
$picname=$_PARAMETROS["S3_URL4"].ImgBlanc(0,$ArrayImg);

?>
<script id="_JS_ADD" type="text/javascript">
	
	(function(){
		var nuevo=<?php echo $nuevo?"true":"false"; ?>;
		<?php
		if($cnf==1){
		?>
			$("#fecha").on("change",$.debounce( 250, function(){
				if($(this).attr("data-adate")!=$(this).val()){
					$(this).attr("data-adate",$(this).val());
					DataCarga($("#u_frm02"),$parents('form'));
					_EqBuscarProg();

				  	var DifFecha=DateDif($(this).val());
				  	if(DifFecha<0){
				  		$('[data-name="cierreot"]').slideUp('fast');
				  		var OptSel=$("#tmmto").find('option[value="'+$("#tmmto").val()+'"]');
				  		if(OptSel.attr("data-range")=="1"){
				  			OptSel.removeAttr("selected");
							$("#tmmto").find('option[value="0"]').attr("selected","selected");
							$("#tmmto").change();
				  		}
				  		$("#tmmto").find('option[value="2"]').attr("disabled","disabled");
				  	}
				  	else{
				  		$('[data-name="cierreot"]').slideDown('fast');
				  		var DifFechaFecha=DateDif($(this).val(),$("#fecha_cierre").val());
				  		if(DifFechaFecha<0) 	$("#fecha_cierre").val($(this).val())
				  		$("#tmmto").find('option[value="2"]').removeAttr("disabled");
				  	}
				}
			}));
			$("#tmmto").on("change",$.debounce( 250, function(){
				var OptSel=$(this).find('option[value="'+$(this).val()+'"]');
				if(OptSel.attr("data-range")=="10"){
					_EqBuscarProg();
					$('[data-name="pmmto"]').slideDown('fast');						
					$("#accont1").click();
					$("#accont").slideUp('fast');
				}
				else{
					$('[data-name="pmmto"]').slideUp('fast');
					$("#accont").slideDown('fast');						

				}
				if(parseInt(OptSel.attr("data-range"))>=9)	$('[data-name="tfalla"]').slideUp('fast');
				else 										$('[data-name="tfalla"]').slideDown('fast');
				
			}));
			$("#pmmto").on("change",$.debounce( 250, function(){
				var OptSel=$(this).find('option[value="'+$(this).val()+'"]');
				if(OptSel.length){
					var Opt={cont:GetAttrs(OptSel)};
					AsignarJSON(Opt);
				}
			}));
			$("#busccit").on("autocompleteclose", function( event, ui ) {
				DataCarga($("#u_frm02"),$(this).parents('form')); 
				DataCarga($("#u_eqrel"),$(this).parents('form')); 
				_EqBuscarProg();
			});			
			
			/////////////////
			$("#repuesto").on("change",function(){
				if($(this).val()=="NU"){						
					if($("#compreti").is(":visible")) $("#compreti").slideUp(100);	
					$('#ccompra2').attr("checked",false).button("refresh");
					$('#ccompra1').attr("checked",true).button("refresh").click();						
				}
				else{
					if(!$("#compreti").is(":visible")) $("#compreti").slideDown(100);						
				}										
				_EqBuscarProg()
				
				
			});
			$("#repuesto_alm").on("change",function(){
				if($(this).val()==1)	$("#u_frm_u").slideDown(100); 
				else 					$("#u_frm_u").slideUp(100); 
			});
			$("#prepreciorep").on("change",function(){
				var Valor=$(this).find('option[value="'+$(this).val()+'"]').attr("data-valor");
				$("#valpre").val(Valor);
				_SumaP($("#valpre"));
			});
			
			$("#almrep1,#almrep2").on("click",function(){	
				$("#repuesto_alm").val($("#almrep1").prop("checked")?1:0)
									.change();
			});
			

		<?php
		}					
	    if(($cnf==104)||($cnf==3)){
	    ?>         	
	    	$("#u_frec").on("change",function(){
			   if($(this).val()==5){
				   $("#frec_tiempo").val("").datepicker({showWeek: true});
			   }
			   else{
				   if(!IsNumeric($("#frec_tiempo").val())) $("#frec_tiempo").val("0");
				   $("#frec_tiempo").datepicker( "destroy" );
			   }
			});
	    <?php 
	    }		
	    if(($cnf==104)){
	    ?>
	    	$("#busccit").on("autocompleteclose", function( event, ui ){
				DataCarga($("#u_eqrel"),$(this).parents('form'));
			});
			
	    <?php
		}
		if(($cnf==1)||($cnf==3)||($cnf==104)){
		?>
		$('[data-addfnuc="ctotal"],[data-addfnuc="cant"],[data-addfnuc="val"]')
											.on("keyup",$.debounce( 250, function(){ _SumaP($(this)) }))
											.on("change",$.debounce( 250, function(){ _SumaP($(this)) }));
			
		function _SumaP(esto){
			var Padre=esto.parents('[data-tipo="valores"]').first();
			var Cant=IsNumeric(Padre.find('[data-addfnuc="cant"]').val())?parseInt(Padre.find('[data-addfnuc="cant"]').val()):0;
			var vUnid=IsNumeric(Padre.find('[data-addfnuc="val"]').val())?parseInt(Padre.find('[data-addfnuc="val"]').val()):0;
			var cTotal=IsNumeric(Padre.find('[data-addfnuc="ctotal"]').val())?parseInt(Padre.find('[data-addfnuc="ctotal"]').val()):0;
			if(esto.attr("data-addfnuc")=="ctotal") 	var vUnid=(Cant==0)?0:Math.round((cTotal/Cant),2);						
			else										var cTotal=Math.round(vUnid*Cant,2);
			if(esto.attr("data-addfnuc")=="ctotal") 	Padre.find('[data-addfnuc="val"]').val(vUnid);
			else										Padre.find('[data-addfnuc="ctotal"]').val(cTotal);
		}
		function _EqBuscarProg(){
			var config={dir:'/autocomplete'};					
			var option=$(this).parents('form').serializeObject();
			option.tp=37;
			DinamicCombo(config,option,$("#pmmto"),
				function(){	
					if($("#pmmto").is(":visible")) $("#pmmto").change()
				}
			)
		}
		<?php
		}			
		
	    if(($cnf==25)||($cnf==26)){ ?>			
		$("#u_caract").delegate('[id^=DescCar_],[id^=UndCar_],[id^=ValorCar_]',"change",function(e){			
			e.preventDefault();				
			var este=$(this);
			var fila=$(this).parents("tr");			
			fila.find('[id^=IdCar_]').remove()				
		});	
		<?php
		}
		if($cnf==26){ ?>			
		/*************/
		$("#contador,#u_estimado,#u_frec").on("change",function(){
			var Contador=$("#contador").find('option[value="'+$("#contador").val()+'"]').html();
			var Frecuencia=$("#u_frec").find('option[value="'+$("#u_frec").val()+'"]').html();
			$('[data-name="u_est_msg"]').html($("#u_estimado").val()+" "+Contador+" x "+Frecuencia);
		});
		$("#contador").change();
	    
		
		$("#restandar").on("change",function(){
			var opcion={md:$("#md").val(),tp:1,cr:$(this).val()};
			BuscarOtros($("#u_frm01"),$("#u_frm01"),opcion);
		});						
		
		$("#u_frm01").delegate('[id^=AddItem_]',"click",function(e){
			e.preventDefault();	
			var fila=$(this).parents("tr");			
			$.getJSON( "/autocomplete", {sh:2,tp:27,eq:$(this).attr("data-value")}, function( dato, status, xhr ) {
				InsertIntoOpfrmFila(dato,$("#u_frm02").find("ol").first());				
				fila.hide('slow',function(){ $(this).remove() });
			});
		});
		<?php
		}

		//ADD POINT
		if($cnf==1004){ ?>
		$("#some_points").delegate('[id=AddItemMassive]',"click",function(e){
			e.preventDefault();	
			var form=$(this).parents("form").first();
			var formser=form.serializeObject();
			formser.tp=64;
			var AddItem=form.find('[id^=AddItem_]');	
			$('[data-sel="true"]').removeAttr("checked");
			$.getJSON( "/autocomplete", formser, function( dato, status, xhr ) {
				InsertIntoOpfrmFila(dato,$("#u_point").find("ol").first());	
			});
			AddItem.each(function(indexAdd,valueAdd){
				if($(this).attr("checked")=='checked'){
					$(this).removeAttr("checked");
					var fila=$(this).parents("tr");
					fila.hide('fast');
				}
			});	
		});
		<?php
		}
		
		if(($cnf==70)&&($_PROYECTO==8)){ ?>
			
			$("#fechaf").on("change", function( event, ui ) {
				$("#fechaimp").val($(this).val())
			});
		<?php 
		}	        
	   	          
		
		//PROYECTO Y MODELOS CRM
		if((($cnf==55)||($cnf==67))&&($_PROYECTO==8)){ 
	    ?>
		$("#u_caract").delegate('[id^=DescCar_],[id^=UndCar_],[id^=ValorCar_]',"change",function(e){			
			e.preventDefault();				
			var este=$(this);
			var fila=$(this).parents("tr");			
			fila.find('[id^=IdCar_]').remove()
		});
		<?php
		}
		
		 
		if(($cnf==69)||($cnf==74)){
		?>
		$("#busclink").on("autocompleteclose", function( event, ui ) {
			_SumaFila($(this));
		});

		
		$("#u_lista").delegate('[name^=PCant],[name^=PPrecio],[name^=Tax],[name^=PDesc]',"keyup",$.debounce( 250, function(){ _SumaFila($(this)) }))
					.delegate('[name^=PCant],[name^=PPrecio],[name^=Tax],[name^=PDesc]',"change",$.debounce( 250, function(){ _SumaFila($(this)) }))
					.delegate('[data-name^=DelPfolio]',"click",$.debounce( 250, function(){
						var Fila=$(this).parents("tr");
						Fila.find('[name^=PCant]').val('0');
						_SumaFila($(this))
					}))
			
		function _SumaFila(esto){
			var Fila=esto.parents("tr")
			,	Cant=parseFloat(Fila.find('[name^=PCant]').val())
			,	Precio=parseFloat(Fila.find('[name^=PPrecio]').val())
			,	Tax=parseFloat(Fila.find('[name^=Tax]').val())
			,	Promo=parseFloat(Fila.find('[name^=PDesc]').val())
			,	PromoTotal=Promo/100
			,	TaxTotal=Tax/100

			var PrecioDcto=Precio*(1-PromoTotal)
			,	PrecioTax=PrecioDcto*(1+TaxTotal)
			,	Ptotal=Math.round(PrecioTax*Cant,0)
			
			Fila.find('[name^=PTotalL]').val(Ptotal);
			var span=$("<span />").html(Ptotal).formatNumber()
			Fila.find("[data-addfnuc=PSTotal]").html("$").append(span);
			_SumaTotal()
		
		}
		function _SumaTotal(){
			var STotal=0
			,	SDescuento=0
			,	STax=0
			,	Total=0;
			$('[name^=PCant]').each(function(index, element) {
				var Fila=$(this).parents("tr")
				,	Cant=parseFloat(Fila.find('[name^=PCant]').val())
				,	Precio=parseFloat(Fila.find('[name^=PPrecio]').val())
				,	Tax=parseFloat(Fila.find('[name^=Tax]').val())	
				,	Promo=parseFloat(Fila.find('[name^=PDesc]').val())
				,	PromoTotal=Promo/100
				,	TaxTotal=Tax/100


				var PrecioDcto=Precio*(1-PromoTotal)
				,	PrecioTax=PrecioDcto*(1+TaxTotal)
				,	fDcto=Math.round(Cant*Precio*PromoTotal,0)
				,	fTax=Math.round(PrecioDcto*TaxTotal*Cant,0)
				,	fSTotal=Math.round(Precio*Cant,0)

				STotal+=fSTotal;
				SDescuento+=fDcto;
				STax+=fTax
				Total+=Math.round(PrecioTax*Cant,0);
			});
			var span_stotal=$("<span />").html(STotal).formatNumber()
			,	span_descuentos=$("<span />").html(SDescuento).formatNumber()
			,	span_taxes=$("<span />").html(STax).formatNumber()
			,	span_total=$("<span />").html(Total).formatNumber()

			$("#u_lista").find("[data-name=sub_total]").html("$").append(span_stotal)
			$("#u_lista").find("[data-name=descuentos]").html("- $").append(span_descuentos)
			$("#u_lista").find("[data-name=taxes]").html("$").append(span_taxes)
			$("#u_lista").find("[data-name=total]").html("$").append(span_total);

		}
		<?php
		}
		//MARCA GPS
		if(($cnf==401||$cnf==405)||($cnf==402||$cnf==406)){
		?>
			$("#desc_mempresa").on("autocompleteclose", function( event, ui ) {
				$("#vsucursal").attr('data-id_mempresa',$('[name="id_mempresa"]').val())
				$("#vdias").attr('data-id_mempresa',$('[name="id_mempresa"]').val())
	            DataCarga($("#vsucursal"),$(this).parents('form')); 
	        });
		<?php
		}
		//APPETITOS
		if($cnf==918){
		?>
			$("#desc_mempresa").on("autocompleteclose", function( event, ui ) {
				$("#vsucursal").attr('data-id_mempresa',$('[name="id_mempresa"]').val())
	            DataCarga($("#vsucursal"),$(this).parents('form')); 
	        });
		<?php
		}
		if($cnf==410||$cnf==409){
		?>
			$("#desc_mempresa").on("autocompleteclose", function( event, ui ) {
				$("#vbonos").attr('data-id_mempresa',$('[name="id_mempresa"]').val())
				$("#vpublic").attr('data-id_mempresa',$('[name="id_mempresa"]').val())
	            DataCarga($("#vbonos"),$(this).parents('form')); 
	            DataCarga($("#vpublic"),$(this).parents('form')); 
	        });
		<?php
		}
		// La Cancha
		if(($cnf==503||$cnf==512)&&($_PROYECTO==27)){ 
		?>		
		$("#fecha, #cancha").on('change',function(){
			var config={dir:'/autocomplete'}
			,	$esto=$(this)
			,	$fecha=$('#fecha')
			,	$cancha=$('#cancha')
			,	$jornada=$("#jornada")
			,	$price=$("#price")
			,	$trelease=$("#trelease")
			,	IsCancha=$esto.attr('id')=='cancha'
			,	option={tp:5030
					,	fecha:$fecha.val()
					,	cancha:$cancha.val()
					,	IsCancha:IsCancha};	
			
			DinamicCombo(config,option,$jornada,function(data){
				var _price=$jornada.find('option').first().attr('data-price')
				$price.val(_price)
				if(data.trelease!=null)
					$trelease.val(data.trelease)
			});			
		});
		$("#jornada").on('change',function(){
			var	$esto=$(this)
			,	price=$esto.find('option:selected').attr('data-price')
			,	$price=$("#price")
			$price.val(price);
		})
		<?php
		}
		if($_PROYECTO==38){
            if($cnf==503){?>
				$("#pais").on('change',function(){
					var config={dir:'/autocomplete'}
					,	$esto=$(this)	
					,	$ciudad=$('#ciudad')				
					,	option={tp:5030
							,	id_pais:$esto.val()};						
					DinamicCombo(config,option,$ciudad);			
				});

				$("#cat").on('change',function(){
					var config={dir:'/autocomplete'}
					,	$esto=$(this)	
					,	$serv=$('#serv')				
					,	option={tp:5031
							,	id_cat:$esto.val()};						
					DinamicCombo(config,option,$serv);			
				});
		<?php
			}
		}

		if($_PROYECTO==42){
            if($cnf==501){?>
                //Cambio de Evento
                $('[name="evento"]').on('change',function(){
                	var type=$(this).find('option:selected').attr('data-type')
                	,	$EsNumerada=$('#EsNumerada')
                	,	$NoEsNumerada=$('#NoEsNumerada')

                	$('[name="type_event"]').val(type);
                	if(type==1){
                		$EsNumerada.slideDown('fast')
                		$NoEsNumerada.slideUp('fast')
                	}
                	else{
                		$EsNumerada.slideUp('fast')
                		$NoEsNumerada.slideDown('fast')
                	}
                })
            <?php
            }
            //VENTAS
            elseif($cnf==502){?>
                //Cambio de Evento
                $('[name="evento"]').on('change',function(){
                	var $sillas=$('#sillas')
                	,	type=$(this).find('option:selected').attr('data-type')
                	,	$dto=$('#dto')
                	,	evento=$(this).val()
                	,	$SelCant=$('#SelCant')
                	,	$SelSilla=$('#SelSilla')

                	$("#costicket").val('$0')

                	if(type==1){
                		$SelCant.slideDown('fast')
                		$SelSilla.slideUp('fast',function(){ $sillas.empty() })


                		var config={dir:'/autocomplete'}
                		,	option={tp:5010
								,	evento:evento}
						,	$localidad=$('#localidad')
                		DinamicCombo(config,option,$localidad,function(data){
							
						});		
                	}
                	else{
                		$SelCant.slideUp('fast')
                		$SelSilla.slideDown('fast')

                		$sillas.attr({'data-evento':evento,'data-type':type})
                		DataCarga($sillas,$(this).parents('form')); 
                	}
               
                	$dto.empty()
                	$dto.attr({'data-evento':evento})	           	 	
	           	 	DataCarga($dto,$(this).parents('form')); 
                })
 				$('body').on('change click','[name="localidad"]',function(event){
 					var $cant_loc=$('#cant_loc')
 					,	$option_sel=$(this).find('option:selected')
 					//console.log($cant_loc)
 					$cant_loc.attr({'data-price':$option_sel.attr('data-price'),'max':$option_sel.attr('data-disp')})
                	CalcSillas();        	
                })

                $('body').on('click',"[name^=idsilla]",function(event){
                	CalcSillas();        	
                })
                $('body').on('change',"[name^=cantSilla], [name=cant_loc]",function(event){
                	CalcSillas();        	
                })
                $('body').on('click',"[name^=idDto],[name='cort']",function(event){
                	CalcSillas();        	
                })


                var CalcSillas=function(){
                	var cort=$('#cort1').prop('checked')
                	if(cort){
                		var Total=0;
                	}
                	else{
	               		var $sillas=$('[name^=idsilla]:checked')
	                	,	$cantSilla=$('[name^=cantSilla]').not('[val=0]')
	                	,	$cant_loc=$('[name=cant_loc]')
	                	,	$idDTO=$('[name="idDto"]:checked')
	                	,	dtoValue=$idDTO.length?parseFloat($idDTO.attr('data-valDTO')):0
	                	, 	sum=0
	                	,	cant=$sillas.length
	                	$sillas.each(function(index,data){                		
	                		sum=sum+parseFloat($(this).attr('data-sillaPrice'))
	                	})
	                	$cantSilla.each(function(index,data){                		
	                		sum=sum+parseFloat($(this).attr('data-sillaPrice'))*parseFloat($(this).val())
	                	})
	                	if($cant_loc.is(":visible")){
	                		sum=sum+parseFloat($cant_loc.attr('data-price'))*parseFloat($cant_loc.val())
	                	}

	                	var Total=Math.round(((100-dtoValue)/100)*sum)
	                }
                	$("#costicket").val('$'+addCommas(Total))
                }
            <?php
        	}
        }
        if($_PROYECTO==39){
			if($cnf==505){?>
				$("#cat").on('change',function(){
					$("[data-tp='1']").attr('data-cat',$(this).val())
		            DataCarga($("[data-tp='1']"),$(this).parents('form')); 
		        });
			<?php
			}
		}
        if($_PROYECTO==40){
            if($cnf==527){?>
            	$('body').on('change',"[name=type]",function(event){
                	var $esto=$(this)
                	,	selected=$esto.val()
                	if(selected==4)
                		$('[data-name="opts"]').slideDown('fast')
                	else
                	  	$('[data-name="opts"]').slideUp('fast')
                })
            <?php
	        }
	        if($cnf==534){?>
				$("#pais").on('change',function(){
					var config={dir:'/autocomplete'}
					,	$esto=$(this)	
					,	$ciudad=$('#ciudad')				
					,	option={tp:5340
							,	id_pais:$esto.val()};				
					DinamicCombo(config,option,$ciudad);			
				});
			<?php
			}
			if($cnf==500){?>
				$("#suc_ciudad").on('change',function(){
					var $opt_sel=$(this).find('option:selected')
					$('[name="_city"]').val($opt_sel.attr('data-city'))
					$('[name="_district"]').val($opt_sel.attr('data-district'))
					$('[name="_country"]').val($opt_sel.attr('data-country'))
					$('[name="_prefix"]').val($opt_sel.attr('data-prefix'))

					if($('[name="id_cont"]').val()=="0"){
						$('[name="pref_cont"]').val($opt_sel.attr('data-prefix'))
					}

					
		        });
				$("#diremp").on('change',function(){
					$('[name="dir_resp"]').val($(this).val())
		        });
		        $("#tel1emp").on('change',function(){
					$('[name="tel_resp"]').val($(this).val())

					if($('[name="id_cont"]').val()=="0"){
						$('[name="tel_con"]').val($(this).val())
					}
		        });
		        $("#tel2emp").on('change',function(){
					if($('[name="id_cont"]').val()=="0"){
						$('[name="tel2_con"]').val($(this).val())
					}
		        });
		        $("#emailemp").on('change',function(){
					if($('[name="id_cont"]').val()=="0"){
						$('[name="email_cont"]').val($(this).val())
					}
		        });

			<?php
			}
			if($cnf==506){?>
				$("#gama").on('change',function(){
					$("[data-tp='4']").attr('data-gama',$(this).val())
		            DataCarga($("[data-tp='4']"),$(this).parents('form')); 
		        });
			<?php
			}

			if($cnf==509){?>
				$("#suc_ciudad").on('change',function(){
					var $opt_sel=$(this).find('option:selected')
					$('[name="_prefix"]').val($opt_sel.attr('data-prefix'))
					if($('[name="id_cont"]').val()=="0"){
						$('[name="pref_cont"]').val($opt_sel.attr('data-prefix'))
					}					
		        });
			<?php
			}

			if($cnf==552||$cnf==553){?>

				$('body').on('click','#searchMod',function(event){
					$('#sucursales').find('tbody').empty()
					$('#adicionales').find('tbody').empty()
					$('#modelos').find('tbody').empty()

					$('[name="fecha_ini"]'
						+',	[name="hora_ini"]'
						+',	[name="fecha_fin"]'
						+',	[name="hora_fin"]'
						+',	[name="codigo"]').prop('readonly',true)
					$('#searchMod,[name="ciudadi"],[name="ciudadf"],[name="gama"]').prop('disabled',true)

		        });

		        $('body').on('click','#nuevaBusqueda',function(event){
		        	event.preventDefault()
		        	event.stopPropagation()

					$('#sucursales').find('tbody').empty()
					$('#adicionales').find('tbody').empty()
					$('#modelos').find('tbody').empty()

					$('[name="fecha_ini"]'
						+',	[name="hora_ini"]'
						+',	[name="fecha_fin"]'
						+',	[name="hora_fin"]'
						+',	[name="codigo"]').prop('readonly',false)
					$('#searchMod,[name="ciudadi"],[name="ciudadf"],[name="gama"]').prop('disabled',false)

					var $modelo=$('[data-tipos="modelo"]')
					,	$rentadora=$('[data-tipos="rentadora"]')
					,	$cod_search=$('[name="cod_search"]')
					$modelo.text('-')
					$rentadora.text('-')
					$cod_search.val('')

		        });

				$('body').on('click','[data-cod]',function(event){
					CalcularPrecio()

					var $cod=$('[name="cod"]:checked')
					,	$tr=$cod.parents('tr')
					,	$inputs=$tr.find('input')
					,	$trOtros=$tr.siblings()
					,	$inputsOtros=$trOtros.find('input').not('[name="cod"]')
					,	cod=$cod.val()
					,	$modelo=$('[data-tipos="modelo"]')
					,	$rentadora=$('[data-tipos="rentadora"]')
					,	$cod_search=$('[name="cod_search"]')

					$inputs.removeAttr('disabled');
					$inputsOtros.attr('disabled','disabled')

					$modelo.text($('[name="modelo['+cod+']"]').val())
					$rentadora.text($('[name="rentadora['+cod+']"]').val())
					$cod_search.val(cod)

		        });
				$('body').on('click','[data-sucursal]',function(event){
		        	CalcularPrecio()					
		        });
		        $('body').on('click','[data-adicional]',function(event){
		        	CalcularPrecio()					
		        });

		        $('body').on('change','[name="fecha_nac"], [name="e_tdoc"], [name="tneg"]',function(event){		        	
		        	CalcularPrecio()					
		        });		        
			<?php
			}
        }
        if($_PROYECTO==45){
        	if($cnf==500){?>
				$("#suc_ciudad").on('change',function(){
					var $opt_sel=$(this).find('option:selected')
					$('[name="_prefix"]').val($opt_sel.attr('data-prefix'))
				});
			<?php
			}
        }
        ?>
	})();
</script>
<?php
if($cnf==19){	
	if(!$nuevo){
		$sWhere=encrip_mysql('s_cresp.ID_RESP');
		$sWhere_q='';						
		if($PermisosA[8]["P"]!=1) 	$sWhere_q.=" AND s_cresp.ID_RESP IN (SELECT s_cresp_grupo.ID_RESP FROM s_cresp_grupo WHERE s_cresp_grupo.ID_GRUPO=$_GRUPO) ";
		$s=$sqlCons[1][101]." WHERE $sWhere=:id $sWhere_q LIMIT 1";
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if(!$reg = $req->fetch()) exit(0);			
		$idImg=$reg["ID_RESP"];
		$sub_titulo=$reg["NOMB_RESP"];

		$picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
                                            'PROYECTO'	=>$_PROYECTO
                                        ,   'EMPRESA'  	=>$_EMPRESA
                                        ,   'MODULO'    =>$cnf
                                        ,   'OBJETO'    =>$idImg
                                        ,   'TP'        =>'img'
                                        ,   'EXT'       =>$reg["F_EXT"]
                                        ,   'All'       =>false
                                        ,   'Cual'      =>'t02'));
	}
	$latitude_def=($reg["REF_LAT"]==''?$latitude_def:$reg["REF_LAT"]);
    $longitude_def=($reg["REF_LON"]==''?$longitude_def:$reg["REF_LON"]);
    $zoom_def=($reg["ZOOM_MAP"]==''?"15":$reg["ZOOM_MAP"]); 
  	$id_ciudad=$reg["ID_CIUDAD"]==''?$_IdCiudadPpal:$reg["ID_CIUDAD"];
?>
	<form class="iform big col_bg03" name="frm-subir" method="post" action="/tedit">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
	    	<div class="col50">
		        <label class="frm-label req" for="nombre" data-txtid="txt-1130-0"></label>
				<input class="input" type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo imprimir($reg["NOMB_RESP"]) ?>" data-required="true"/>
		        
				<label class="frm-label" for="abr" data-txtid="txt-1077-0"></label>
				<input class="input" type="text" name="abr" id="abr" maxlength="5" value="<?php echo imprimir($reg["ABR_RESP"]) ?>"/>

		        <label class="frm-label" for="desc" data-txtid="txt-1041-0"></label>
				<textarea class="input" data-maxlength="140" name="desc" id="desc"  cols="" rows=""><?php echo imprimir($reg["COMENT_RESP"],2) ?></textarea> 
		        
		        <?php
		        if($_PROYECTO==8){ ?>			        
			        <label class="frm-label req" for="moneda" data-txtid="txt-1156-0"></label>
					<select class="input" name="moneda" id="moneda" data-required="true">
			            <?php 
			                $s=$sqlCons[1][79]." WHERE fac_moneda.HAB_MONEDA=0 ".$sqlOrder[1][79];
							$req = $dbEmpresa->prepare($s);
							$req->bindParam(':idioma', $_IDIOMA);
							$req->execute();
			            	echo crear_select($req,'ID_MONEDA','COD01_MONEDA',$reg["ID_MONEDA"],0);
			            ?>
					</select>
				<?php
				} ?>

		        <?php
		        if($reg["PPAL"]!=1){
				?>        
			        <fieldset class="fieldset">
		                <legend class="legend medium" data-txtid="txt-1078-0"></legend>   
		                <label class="frm-label" for="imagen" data-txtid="txt-1078-1"></label>    
		                <div class="options">
		                    <input type="radio" id="ppal1" name="ppal" value="1" <?php echo $reg["PPAL"]==1?'checked':'';?> /><label for="ppal1" data-txtid="txt-1002-0"></label>
		                    <input type="radio" id="ppal2" name="ppal" value="2" <?php echo $reg["PPAL"]==0?'checked':'';?>/><label for="ppal2" data-txtid="txt-1003-0"></label>
		                </div>
		            </fieldset>
		        <?php }
				else{
				?>
		        	<input type="hidden" name="ppal" id="ppal" value="1"/>
		        <?php
				}?>

				<fieldset class="fieldset">
	        		<div data-maps="true" class="mapa" data-setMarker="true" data-setInverse="true" data-setDecode="true">  
				        <label class="frm-label req" for="ciudad" data-txtid="txt-1058-0"></label>
			            <select class="input" name="ciudad" id="ciudad" data-name="city" data-required="true">
			                <option value="0" data-txtid="txt-1128-0"></option>
			                <?php 
			                    $s=$sqlCons[1][45].' WHERE fac_ciudades.ID_CIUDAD IN (SELECT ID_CIUDAD FROM adm_ciudad) '.$sqlOrder[1][45];
			                    $reqC = $dbEmpresa->prepare($s);
			                    $reqC->execute();    
			                    while($regC = $reqC->fetch()){
			                    	if($regC['ID_CIUDAD']==$id_ciudad)	$selec_in='selected="selected"';
			                    	else 								$selec_in='';
									echo sprintf('<option value="%s" %s data-city="%s" data-district="%s" data-country="%s">%s</option>'
										,	$regC["ID_CIUDAD"]
										,	$selec_in
										,	$regC["NOMB_CIUDAD"]
										,	$regC["DISTRITO_CIUDAD"]
										,	$regC["NOMB_PAIS"]
										,	$regC["NOMB_CIUDAD"]);
								}
			                ?>
			            </select>	
			            <label class="frm-label" for="direc" data-txtid="txt-1065-0"></label>
						<input class="input" type="text" name="direc" id="direc" maxlength="100" value="<?php echo imprimir($reg["DIRECCION"]) ?>" data-name="direction"/>

						<label class="frm-label" for="tel" data-txtid="txt-1059-0"></label>
						<input class="input" type="tel" name="tel" id="tel" maxlength="15" value="<?php echo imprimir($reg["TELEFONO"]) ?>"/>

						<input type="hidden" data-name="lat" name="latu" value="<?php echo $latitude_def ?>"/>
	                    <input type="hidden" data-name="lon" name="lonu" value="<?php echo $longitude_def ?>"/>
	                    <input type="hidden" data-name="zoom" name="zoom" value="<?php echo $zoom_def ?>"/> 		                    
	                    <div class="m02" data-name="map"></div>
	                    <button data-name="decode" data-txtid="txt-1429-0"></button>
	               	</div>
	            </fieldset>

	            <!-- Imagenes -->
	            <fieldset class="fieldset">
	                <legend class="legend medium" data-txtid="txt-1004-0"></legend>
	                <div class="wrapimg display col_bg03">
	                    <div class="_fix"></div>
	                    <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
	                </div>
	                <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="frm-cimg">      
	                    <input type="radio" id="frm-cimg1" name="imagen" value="1" data-expand="true"                     /><label for="frm-cimg1" data-txtid="txt-1002-0"></label>
	                    <input type="radio" id="frm-cimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="frm-cimg2" data-txtid="txt-1003-0"></label>
	                    <input type="radio" id="frm-cimg3" name="imagen" value="3" data-expand="true"                     /><label for="frm-cimg3" data-txtid="txt-1005-0"></label>
	                </div>
	                <div class="hide">
	                    <input class="input" type="file" name="imagen" id="frm-file" />
	                </div>
	            </fieldset>
	            <!-- end -->
		        
			</div><!--
	     --><div class="col50">
		        <!-- LINK MAS CIUDADES -->
			    <fieldset class="fieldset">
			        <legend class="legend medium" data-txtid="txt-1298-0"></legend>
			        <label class="frm-label" for="busclink"><span data-txtid="txt-1008-0"></span> <span data-txtid="txt-1058-0"></span></label>
			        <div class="autosearch">
			            <input class="input asearch" type="text" name="busclink" id="busclink" value="" data-table="u_ciudades" data-autocomplete="true" data-tp="1"/>
			        </div>
			        <div id="u_ciudades" data-tp="1" data-carga="true" data-md="<?php echo $md?>"></div>
			    </fieldset>
			    <?php
			    if($PermisosA[8]["P"]==1){	    	
					$m_md=$id_sha.encrip(8,2).nuevo_item().'003';?>
				    <!-- LINK GRUPOS -->
				    <fieldset class="fieldset">
				        <legend class="legend medium" data-txtid="txt-1299-0"></legend>
				        <input type="checkbox" data-target="u_grupos" data-sel="true" id="u_grupos_st"/><label for="u_grupos_st" class="frm-label top" data-txtid="txt-1116-0"></label>         
				        <div id="u_grupos" data-id="u_grupos" data-carga="true" data-md="<?php echo $m_md?>"></div>
				    </fieldset>
		        <?php
		    	}
		    	?>
		    </div>
		</div>
		<input type="hidden" name="md" value="<?php echo $md?>" />

		<div class="message"></div>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
	</form>
<?php
}
elseif($cnf==8){
	if(!$nuevo){		
		$sWhere=encrip_mysql('adm_grupos.ID_GRUPO');
		$s=$sqlCons[1][64]." WHERE $sWhere=:id LIMIT 1";
		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if(!$reg = $req->fetch()) exit(0);	
				
		$idImg=$reg["ID_GRUPO"];
		$sub_titulo=$reg["DESC_GRUPO"];
		$nuevo=false;

		$flag=$reg["ADMFLAG"];
	}
	else{				
		$flag=0;
	}		
?>
	<form class="iform big col_bg03" name="frm-subir" method="post" action="/tedit">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
	    	<div class="col50">   
		     	<label class="frm-label req" for="nombre" data-txtid="txt-1130-0"></label>
				<input class="input" type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo imprimir($reg["DESC_GRUPO"]) ?>" data-required="true"/>
		        
		        <label class="frm-label" for="desc" data-txtid="txt-1091-0"></label>
				<textarea class="input" name="desc" id="desc"  cols="" rows=""><?php echo imprimir($reg["COMEN_GRUPO"],2) ?></textarea> 
	         
		    </div><!--
		  --><div class="col50">
				<?php 
	    		if((($PermisosA[10000]["P"]==1)||($flag!=1))&&($reg["ID_GRUPO"]!=$_GRUPO)){ ?>
					<!-- LINK MODULOS -->
				    <fieldset class="fieldset">
				        <legend class="legend medium" data-txtid="txt-1296-0"></legend>
				        <input type="checkbox" data-target="u_grupos" data-sel="true" id="u_modulos_st"/><label for="u_modulos_st" class="frm-label top" data-txtid="txt-1116-0"></label>      
				        <div id="u_modulos" data-id="u_modulos" data-carga="true" data-md="<?php echo $md.nuevo_item().'001'?>"></div>
				    </fieldset>
			    <?php
				}
				if(($PermisosA[10000]["P"]==1)||($flag!=1)){ ?>
				    <!-- LINK AREAS -->
				    <fieldset class="fieldset">
				        <legend class="legend medium" data-txtid="txt-1296-0"></legend>
				        <input type="checkbox" data-target="u_areas" data-sel="true" id="u_grupos_st"/><label for="u_areas_st" class="frm-label top" data-txtid="txt-1116-0"></label>        
				        <div id="u_areas" data-id="u_areas" data-carga="true" data-md="<?php echo $md.nuevo_item().'002'?>"></div>
				    </fieldset>
			    <?php
				} ?>
			</div>
		</div>    
		<input type="hidden" name="md" value="<?php echo $md?>" />
		<div class="message"></div>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
	</form>
<?php
}
elseif($cnf==36){
	if(!$nuevo){		
		$sWhere=encrip_mysql('adm_usuarios.ID_USUARIO');
		$s=$sqlCons[1][0]." WHERE $sWhere=:id LIMIT 1";
		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if(!$reg = $req->fetch()) exit(0);	
				
		$idImg=$reg["ID_USUARIO"];
		$sub_titulo=$reg["NOMBRE_U"].' '.$reg["APELLIDO_U"];

		$picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
	                                            'PROYECTO'	=>$_PROYECTO
	                                        ,   'EMPRESA'  	=>$_EMPRESA
	                                        ,   'MODULO'    =>$cnf
	                                        ,   'OBJETO'    =>$idImg
	                                        ,   'TP'        =>'img'
	                                        ,   'EXT'       =>$reg["F_EXT"]
	                                        ,   'All'       =>false
	                                        ,   'Cual'      =>'t02'));
	}
	else{		
		if($id_sha_t!=''){
			$sWhere=encrip_mysql('adm_grupos.ID_GRUPO');
			$s=$sqlCons[1][64]." WHERE $sWhere=:idt LIMIT 1";
			$req = $dbEmpresa->prepare($s); 
			$req->bindParam(':idt', $id_sha_t);
			$req->execute();	
			$reg = $req->fetch();		
		}		
		$sub_titulo="";
	}		

	$flag=$reg['FLAG_U']==''?0:$reg['FLAG_U'];
?>
	<form class="iform col_bg03" name="frm-subir" method="post" action="/tedit" novalidate>
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
			<?php 
			if($nuevo){ ?>
			    <label class="frm-label" data-txtid="txt-1166-0"></label>
			    <div class="options">
			        <input type="radio" id="usexist1" name="usexist" data-group="usexist" data-expid="usex1" value="1" /><label for="usexist1" data-txtid="txt-1167-0"></label>
			        <input type="radio" id="usexist2" name="usexist" data-group="usexist" data-expid="usex2" value="2" checked="checked"/><label for="usexist2" data-txtid="txt-1009-0"></label>
			    </div>
		    <?php 
			}
		    else{
			?>
				<input type="hidden" name="usexist" value="2" />
			<?php
		    } ?>

		    
		    <fieldset data-id="usex1" data-grupo="usexist" class="fieldset" style="display:none">
		    	<legend class="legend medium" data-txtid="txt-1167-0"></legend> 
		    	<label class="frm-label req" for="correo_exist" data-txtid="txt-1072-0"></label>
		        <input class="input" type="email" name="correo_exist" id="correo_exist" maxlength="100" value="" data-required="true"/>

		        <label class="frm-label req" for="grupo_exist" data-txtid="txt-1431-0"></label>
		        <select class="input" name="grupo_exist" id="grupo_exist" data-required="true">
		        <?php	           
		        	$Condicion=$PermisosA[10000]["P"]==1?' adm_grupos.HAB_GRUPO=0 ':' adm_grupos.HAB_GRUPO=0 AND adm_grupos.ADM_GRUPO NOT IN (2,3) ';
		            $s=$sqlCons[1][64]." WHERE $Condicion ".$sqlOrder[1][64];
		            $req = $dbEmpresa->prepare($s);
		            $req->execute();
		            echo crear_select($req,'ID_GRUPO','DESC_GRUPO',$reg["ID_GRUPO"],1,'txt-1431-1');
		        ?>
		        </select>            
		   	</fieldset>
		   	

		   	<fieldset data-id="usex2" data-grupo="usexist" class="fieldset">
		   		<legend class="legend medium" data-txtid="txt-1009-0"></legend> 
		        <label class="frm-label req" for="nombre" data-txtid="txt-1130-0"></label>
		        <input class="input" type="text" name="nombre" id="nombre" maxlength="35" value="<?php echo imprimir($reg["NOMBRE_U"]) ?>" data-required="true"/>

		        <label class="frm-label" for="apellido" data-txtid="txt-1068-0"></label>
		        <input class="input" type="text" name="apellido" id="apellido" maxlength="35" value="<?php echo imprimir($reg["APELLIDO_U"]) ?>"/>
		        
		        <label class="frm-label req" for="correo" data-txtid="txt-1072-0"></label>
		        <input class="input" type="email" name="correo" id="correo" maxlength="100" value="<?php echo imprimir($reg["CORREO_U"]) ?>" data-required="true"/>
		        
		        <?php
		    	if($PermisosA[10000]["P"]==1){?>
			        <fieldset class="fieldset">
                        <legend class="legend" data-txtid="txt-1436-0"></legend>
                       
	                    <div class="options" id="flagus">      
			                <input type="radio" id="flagus1" name="flagus" value="0" <?php echo $flag==0?'checked="checked"':''?> /><label for="flagus1" data-txtid="txt-1436-1"></label><br />
			                <input type="radio" id="flagus2" name="flagus" value="1" <?php echo $flag==1?'checked="checked"':''?> /><label for="flagus2" data-txtid="txt-1437-1"></label><br />
			                <input type="radio" id="flagus3" name="flagus" value="2" <?php echo $flag==2?'checked="checked"':''?> /><label for="flagus3" data-txtid="txt-1438-1"></label>
			            </div>
                    </fieldset>  

                    <fieldset class="fieldset">
			            <legend class="legend" data-txtid="txt-1083-0"></legend>		            
			            <div data-tp="2" data-carga="true"></div>
			        </fieldset>
			    <?php
				}
				else{?>
			        <label class="frm-label req" for="grupo" data-txtid="txt-1431-0"></label>
			        <?php	           
			        	$Condicion=$PermisosA[10000]["P"]==1?' adm_grupos.HAB_GRUPO=0 ':' adm_grupos.HAB_GRUPO=0 AND adm_grupos.ADM_GRUPO NOT IN (2,3) ';
			        ?>
			            <select class="input" name="grupo" id="grupo" data-required="true">
			            <?php 
			                $s=$sqlCons[1][64]." WHERE $Condicion ".$sqlOrder[1][64];
			                $req = $dbEmpresa->prepare($s);
			                $req->execute();
			                echo crear_select($req,'ID_GRUPO','DESC_GRUPO',$reg["ID_GRUPO"],1,'txt-1431-1');
			            ?>
			            </select>
			        <?php 
			    }
				if($nuevo){ ?>
			        <fieldset class="fieldset">	    
			        	<legend class="legend medium" data-txtid="txt-1097-0"></legend>            
			            <label class="frm-label" for="password" data-txtid="txt-1097-0"></label>
			            <input class="input" type="password" name="password" id="password" value=""/>
			        </fieldset>
		        <?php } ?>
		                      
		        <!-- Imagenes -->
		        <fieldset class="fieldset">
		            <legend class="legend medium" data-txtid="txt-1004-0"></legend>
		            <div class="wrapimg display col_bg03">
		                <div class="_fix"></div>
		                <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
		            </div>
		            <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="frm-cimg">      
		                <input type="radio" id="frm-cimg1" name="imagen" value="1" data-expand="true"                     /><label for="frm-cimg1" data-txtid="txt-1002-0"></label>
		                <input type="radio" id="frm-cimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="frm-cimg2" data-txtid="txt-1003-0"></label>
		                <input type="radio" id="frm-cimg3" name="imagen" value="3" data-expand="true"                     /><label for="frm-cimg3" data-txtid="txt-1005-0"></label>
		            </div>
		            <div class="hide">
		                <input class="input" type="file" name="imagen" id="frm-file" />
		            </div>
		        </fieldset>
		        <!-- end -->	           
			</fieldset>
		</div>
		<input type="hidden" name="md" value="<?php echo $md?>" />
		<div class="message"></div>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
	</form>
<?php
}
//CLASES DE NEGOCIO
elseif($cnf==10003){
	if(!$nuevo){		
		$sWhere=encrip_mysql("adm_empresas_btipo.TIPO_GRUPOPAL");
		$s=$sqlCons[1][85]." WHERE $sWhere=:id LIMIT 1";
		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if(!$reg = $req->fetch()) exit(0);	
				
		$idImg=$reg["TIPO_GRUPOPAL"];
		$picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
	                                            'PROYECTO'	=>$_PROYECTO
	                                        ,   'EMPRESA'  	=>$_EMPRESA
	                                        ,   'MODULO'    =>$cnf
	                                        ,   'OBJETO'    =>$idImg
	                                        ,   'TP'        =>'img'
	                                        ,   'EXT'       =>$reg["F_EXT"]
	                                        ,   'All'       =>false
	                                        ,   'Cual'      =>'t02'));
		$sub_titulo=$reg["NOMB_GRUPOPAL"];
	}
?>
	<form class="iform big col_bg03" name="frm-subir" method="post" action="/tedit">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
	    	<div class="col50"> 
				<?php
				if($nuevo){ ?>
	            <label class="frm-label req" for="cnegocio" data-txtid="txt-1220][0].' '.$_textos[1219-0"></label>
	            <select class="input" name="cnegocio" id="cnegocio" data-required="true">
	            <?php 
	                $s=$sqlCons[1][85]." WHERE adm_empresas_btipo.HAB_GRUPOPAL=0 ".$sqlOrder[1][85];
	                $req = $dbEmpresa->prepare($s);
	                $req->execute();
	                echo crear_select($req,'TIPO_GRUPOPAL','NOMB_GRUPOPAL',0,0);
	            ?>
	            </select>  
	    		<?php
	    		}?>   		
	            
	            <?php
		        $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
		        $req = $dbEmpresa->prepare($s);
		        $req->execute();    
		        while($reg = $req->fetch()){
		        ?>
		            <fieldset class="fieldset">
		                <legend class="legend medium"><?php echo $reg["IDIOMA"]?></legend>
		                <?php
		                $sWhere=encrip_mysql('adm_empresas_btipo_desc.TIPO_GRUPOPAL');
		                $s=$sqlCons[1][86]." WHERE $sWhere=:id AND adm_empresas_btipo_desc.ID_IDIOMA=:idioma LIMIT 1";
		                $reqId = $dbEmpresa->prepare($s);
		                $reqId->bindParam(':id', $id_sha);
		                $reqId->bindParam(':idioma', $reg["ID_IDIOMA"]);
		                $reqId->execute();  
		                $regId = $reqId->fetch();
		                ?>
		                <label class="frm-label" for="nomb_<?php echo $reg["ID_IDIOMA"]?>" data-txtid="txt-1130-0"></label>
		                <input class="input" type="text" name="nomb[<?php echo $reg["ID_IDIOMA"]?>]" id="nomb_<?php echo $reg["ID_IDIOMA"]?>" maxlength="40" value="<?php echo imprimir($regId["NOMB_GRUPOPAL"]) ?>"/>         
		       
		                <label class="frm-label" for="desc_<?php echo $reg["ID_IDIOMA"]?>" data-txtid="txt-1091-0"></label>
		                <textarea class="input" name="desc[<?php echo $reg["ID_IDIOMA"]?>]" id="desc_<?php echo $reg["ID_IDIOMA"]?>" cols="" rows=""><?php echo imprimir($regId["DESC_GRUPOPAL"],2) ?></textarea>
		                
		                <input type="hidden" name="idioma[<?php echo $reg["ID_IDIOMA"]?>]" id="idioma_<?php echo $reg["ID_IDIOMA"]?>"  value="<?php echo $reg["ID_IDIOMA"] ?>"/>  
		            </fieldset>
		        
		        <?php
		        }
		        ?>  

	            <!-- Imagenes -->
	            <fieldset class="fieldset">
	                <legend class="legend medium" data-txtid="txt-1004-0"></legend>
	                <div class="wrapimg display col_bg03">
	                    <div class="_fix"></div>
	                    <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
	                </div>
	                <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
                    <div class="options" id="frm-cimg">        
	                    <input type="radio" id="frm-cimg1" name="imagen" value="1" data-expand="true"                     /><label for="frm-cimg1" data-txtid="txt-1002-0"></label>
	                    <input type="radio" id="frm-cimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="frm-cimg2" data-txtid="txt-1003-0"></label>
	                    <input type="radio" id="frm-cimg3" name="imagen" value="3" data-expand="true"                     /><label for="frm-cimg3" data-txtid="txt-1005-0"></label>
	                </div>
	                <div class="hide">
	                    <input class="input" type="file" name="imagen" id="frm-file" />
	                </div>
	            </fieldset>
	            <!-- end -->
	     	</div><!--
	     --><div class="col50">
	    		<?php 
	    		if($PermisosA[10000]["P"]==1){ ?>
			    <!-- LINK MODULOS -->
			    <fieldset class="fieldset">
			        <legend class="legend medium" data-txtid="txt-1296-0"></legend>
			        <input type="checkbox" data-target="u_modulos" data-sel="true" id="u_grupos_st"/><label for="u_modulos_st" class="frm-label top" data-txtid="txt-1116-0"></label>     
			        <div id="u_modulos" data-id="u_modulos" data-carga="true" data-tp="1" data-md="<?php echo $md ?>"></div>
			    </fieldset>
			    <?php
			    }
			    else echo $_textos[1120][0] ?>
	    	</div>
	    </div>
	    <input type="hidden" name="md" value="<?php echo $md?>" />
	    <div class="message"></div>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
	</form>
<?php
}
//TIPO DE EMPRESA
elseif($cnf==10002){	
	if(!$nuevo){		
		$sWhere=encrip_mysql("adm_empresas_tipo.ID_TIPOE");
		$s=$sqlCons[1][72]." WHERE $sWhere=:id LIMIT 1";
		
		$req = $dbEmpresa->prepare($s); 
		$req->bindParam(':id', $id_sha);
		$req->execute();	
		if(!$reg = $req->fetch()) exit(0);	
				
		$idImg=$reg["ID_TIPOE"];
		$sub_titulo=$reg["NOMB_TIPOE"];

		$picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
	                                            'PROYECTO'	=>$_PROYECTO
	                                        ,   'EMPRESA'  	=>$_EMPRESA
	                                        ,   'MODULO'    =>$cnf
	                                        ,   'OBJETO'    =>$idImg
	                                        ,   'TP'        =>'img'
	                                        ,   'EXT'       =>$reg["F_EXT"]
	                                        ,   'All'       =>false
	                                        ,   'Cual'      =>'t02'));
	}
	
?>
	<form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
			<label class="frm-label req" for="cnegocio" data-txtid="txt-1219-0"></label>
		    <select class="input" name="cnegocio" id="cnegocio" data-required="true">
		    <?php 
		        $s=$sqlCons[1][85]." WHERE adm_empresas_btipo.HAB_GRUPOPAL=0 ".$sqlOrder[1][85];
		        $req = $dbEmpresa->prepare($s);
		        $req->execute();
		        echo crear_select($req,'TIPO_GRUPOPAL','NOMB_GRUPOPAL',$reg["TIPO_GRUPOPAL"],0);
		    ?>
		    </select>

		    <?php
		    $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
		    $req = $dbEmpresa->prepare($s);
		    $req->execute();    
		    while($reg = $req->fetch()){
		    ?>
		        <fieldset class="fieldset">
		            <legend class="legend medium"><?php echo $reg["IDIOMA"]?></legend>
		            <?php
		            $sWhere=encrip_mysql('adm_empresas_tipo_desc.ID_TIPOE');
		            $s=$sqlCons[1][87]." WHERE $sWhere=:id AND adm_empresas_tipo_desc.ID_IDIOMA=:idioma LIMIT 1";
		            $reqId = $dbEmpresa->prepare($s);
		            $reqId->bindParam(':id', $id_sha);
		            $reqId->bindParam(':idioma', $reg["ID_IDIOMA"]);
		            $reqId->execute();  
		            $regId = $reqId->fetch();
		            ?>
		            <label class="frm-label" for="nomb_<?php echo $reg["ID_IDIOMA"]?>" data-txtid="txt-1130-0"></label>
		            <input class="input" type="text" name="nomb[<?php echo $reg["ID_IDIOMA"]?>]" id="nomb_<?php echo $reg["ID_IDIOMA"]?>" maxlength="45" value="<?php echo imprimir($regId["NOMB_TIPOE"]) ?>"/>         
		   
		            <label class="frm-label" for="desc_<?php echo $reg["ID_IDIOMA"]?>" data-txtid="txt-1091-0"></label>
		            <textarea class="input" name="desc[<?php echo $reg["ID_IDIOMA"]?>]" id="desc_<?php echo $reg["ID_IDIOMA"]?>" cols="" rows=""><?php echo imprimir($regId["DESC_TIPOE"],2) ?></textarea>
		            
		            <input type="hidden" name="idioma[<?php echo $reg["ID_IDIOMA"]?>]" id="idioma_<?php echo $reg["ID_IDIOMA"]?>"  value="<?php echo $reg["ID_IDIOMA"] ?>"/>  
		        </fieldset>
		    
		    <?php
		    }
		    ?>  
		    <!-- Imagenes -->
		    <fieldset class="fieldset">
		        <legend class="legend medium" data-txtid="txt-1004-0"></legend>
		        <div class="wrapimg display col_bg03">
		            <div class="_fix"></div>
		            <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
		        </div>
		        <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
                <div class="options" id="frm-cimg">        
		            <input type="radio" id="frm-cimg1" name="imagen" value="1" data-expand="true"                     /><label for="frm-cimg1" data-txtid="txt-1002-0"></label>
		            <input type="radio" id="frm-cimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="frm-cimg2" data-txtid="txt-1003-0"></label>
		            <input type="radio" id="frm-cimg3" name="imagen" value="3" data-expand="true"                     /><label for="frm-cimg3" data-txtid="txt-1005-0"></label>
		        </div>
		        <div class="hide">
		            <input class="input" type="file" name="imagen" id="frm-file" />
		        </div>
		    </fieldset>
		    <!-- end -->
		</div>
	    <input type="hidden" name="md" value="<?php echo $md?>" />
	    <div class="message"></div>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
	</form>
<?php
}
//EMPRESAS
elseif($cnf==10004){	
if(!$nuevo){		
	$sWhere=encrip_mysql("adm_empresas.ID_MEMPRESA");
	$s=$sqlCons[1][81]." WHERE $sWhere=:id LIMIT 1";		
	$req = $dbEmpresa->prepare($s); 
	$req->bindParam(':idioma', $_IDIOMA);
	$req->bindParam(':id', $id_sha);
	$req->execute();	
	if(!$reg = $req->fetch()) exit(0);	
			
	$idImg=$reg["ID_MEMPRESA"];
	$sub_titulo=$reg["NOMB_MEMPRESA"];
	$nuevo=false;
	$picname=$_PARAMETROS["S3_URL4"].ImgBlanc($reg["M_IMG"],array(
                                            'PROYECTO'	=>$_PROYECTO
                                        ,   'EMPRESA'  	=>$_EMPRESA
                                        ,   'MODULO'    =>0
                                        ,   'OBJETO'    =>$idImg
                                        ,   'TP'        =>'LogoClient'
                                        ,   'EXT'       =>$reg["F_EXT"]
                                        ,   'All'       =>false
                                        ,   'Cual'      =>'t02'));
}
else{			
	$sub_titulo="";
	$nuevo=true;
}		
?>
	<form class="iform col_bg03" name="frm-subir" method="post" action="/tedit">
		<header class="frm-h">
			<h2 class="frm-tit col_titles" data-txtid="<?php echo $nuevo?'txt-1009-0':'txt-1017-0'?>"></h2>
			<h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
			<div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
		</header>
		<div class="frm-body">
			<label class="frm-label req" for="tempresa" data-txtid="txt-1161-0"></label>
		    <select class="input" name="tempresa" id="tempresa" data-required="true">
			<?php 
			    $s=$sqlCons[1][72]." WHERE adm_empresas_tipo.HAB_TIPOE=0 AND adm_empresas_tipo_desc.ID_IDIOMA=$_IDIOMA ".$sqlOrder[1][72];
		        $req = $dbEmpresa->prepare($s);
		        $req->execute();
		        echo crear_select($req,'ID_TIPOE','NOMB_TIPOE',$reg["ID_TIPOE"],1,'txt-1162-0');
			?>
			</select> 

			<label class="frm-label req" for="nombre" data-txtid="txt-1130-0"></label>
			<input class="input" type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $reg["NOMB_MEMPRESA"]?>" data-required="true"/>

		   	<?php
	    	if($PermisosA[10000]["P"]==1&&!$nuevo){?>
		    	<fieldset class="fieldset">
		            <legend class="legend" data-txtid="txt-1083-0"></legend>		            
		            <div data-tp="1" data-carga="true"></div>
		        </fieldset>
		    <?php
			}?>

		    <!-- Imagenes -->
		    <fieldset class="fieldset">
		        <legend class="legend medium" data-txtid="txt-1004-0"></legend>
		        <div class="wrapimg display col_bg03">
		            <div class="_fix"></div>
		            <div class="stdImg _zfix_00" style="background-image: url(<?php echo $picname; ?>)"></div>
		        </div>
		        <label class="frm-label" for="frm-cimg"><span data-txtid="txt-1000-0"></span></label>
          		<div class="options" id="frm-cimg">        
		            <input type="radio" id="frm-cimg1" name="imagen" value="1" data-expand="true"                     /><label for="frm-cimg1" data-txtid="txt-1002-0"></label>
		            <input type="radio" id="frm-cimg2" name="imagen" value="2" data-expand="true" checked="checked"   /><label for="frm-cimg2" data-txtid="txt-1003-0"></label>
		            <input type="radio" id="frm-cimg3" name="imagen" value="3" data-expand="true"                     /><label for="frm-cimg3" data-txtid="txt-1005-0"></label>
		        </div>
		        <div class="hide">
		            <input class="input" type="file" name="imagen" id="frm-file" />
		        </div>
		    </fieldset>
		    <!-- end -->

		    <input type="hidden" name="md" value="<?php echo $md?>" />
		</div>
	    <div class="message"></div>
		<div class="botones">
			<button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
		</div>
	</form>
<?php
}
else{
	if($_PROYECTO==1) 		include("base_frm_001.php");	
	elseif($_PROYECTO==8) 	include("base_frm_008.php");
	elseif($_PROYECTO==10) 	include("base_frm_010.php");
	elseif($_PROYECTO==13) 	include("base_frm_013.php");
	elseif($_PROYECTO==14) 	include("base_frm_014.php");
	elseif($_PROYECTO==15) 	include("base_frm_015.php");
	elseif($_PROYECTO==16) 	include("base_frm_016.php");
	elseif($_PROYECTO==18) 	include("base_frm_018.php");
	elseif($_PROYECTO==19) 	include("base_frm_019.php");
	elseif($_PROYECTO==20) 	include("base_frm_020.php");
	elseif($_PROYECTO==21) 	include("base_frm_021.php");
	elseif($_PROYECTO==22) 	include("base_frm_022.php");
	elseif($_PROYECTO==23) 	include("base_frm_023.php");
	elseif($_PROYECTO==24) 	include("base_frm_024.php");
	elseif($_PROYECTO==25) 	include("base_frm_025.php");
	elseif($_PROYECTO==26) 	include("base_frm_026.php");
	elseif($_PROYECTO==27) 	include("base_frm_027.php");
	elseif($_PROYECTO==28) 	include("base_frm_028.php");
	elseif($_PROYECTO==29) 	include("base_frm_029.php");
	elseif($_PROYECTO==31) 	include("base_frm_031.php");
	elseif($_PROYECTO==32) 	include("base_frm_032.php");
	elseif($_PROYECTO==36) 	include("base_frm_036.php");
	elseif($_PROYECTO==37) 	include("base_frm_037.php");
	elseif($_PROYECTO==38) 	include("base_frm_038.php");
	elseif($_PROYECTO==39) 	include("base_frm_039.php");
	elseif($_PROYECTO==40) 	include("base_frm_040.php");
	elseif($_PROYECTO==41) 	include("base_frm_041.php");
	elseif($_PROYECTO==42) 	include("base_frm_042.php");
	elseif($_PROYECTO==43) 	include("base_frm_043.php");
	elseif($_PROYECTO==44) 	include("base_frm_044.php");
	elseif($_PROYECTO==45) 	include("base_frm_045.php");
}?>
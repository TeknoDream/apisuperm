<header class="index colf02">
    <div id="head_top"></div>
    <div id="head_content"></div>
    <div id="head_foot"></div>
</header>
<div id="content" class="index colf02" data-relef="content">
	<script type="text/javascript"> 
	function BindStartPage() {	
		StartPage(1);
	}
	</script>
    <!--CONTENIDO DE LOGIN-->
    <div id="content_content">
    	<div class="peque">
        	<!-- -->
    		<div class="cabeza_pq azul cabeza"><h1>¿Que es SIIE?</h1></div>
    		<div class="cuerpo_pq lineaazul cuerpo">
                <p> SIIE es una aplicación modular, diseñada para uso empresarial en gestión de soluciones en procesos 
                administrativos o industriales de una manera personalizada según la necesidad establecida.</p>
    		</div>
            <!-- -->
    		<div class="cabeza_pq naranja cabeza"><H1>Objetivo de SIIE</H1></div>
   			<div class="cuerpo_pq lineanaranja cuerpo">
   				<p>Tener control sobre los diferentes procesos que realice la empresa, con la posibilidad de medirlos y compararlos. </p>
    		</div>
    	</div>
    
    	<div class="peque">
    		<div class="cabeza_pq verde cabeza"><H1>¿Como funciona?</H1></div>
    		<div class="cuerpo_pq lineaverde cuerpo">
                <p>El sistema proporciona a las empresas facilidades de alcance proyectivo y evaluativo de los procesos básicos que 
                está realiza; ayudando a que haya una gestión permanente y controlada de ellos, permitiendo generar a futuro los acuerdos
                de compromisos y funcionamiento de la empresa.</p>
                <p class="txt_pq">
                Si requiere información personalizada <a data-relid="contactenos" href="#contacto" title="Contactarse con Motum Data">contáctenos.</a>
                </p>
    		</div>
    	</div>
		<!-- -->
        <div class="final"></div>
      	<div class="cabeza verde logo01"><h1>BENEFICIOS</h1></div>
        <div class="cuerpo lineaverde">
            <h2>AHORRO</h2>
            <p>El diseño modular de SIIE, permite generar  indicadores medibles de la eficiencia de sus procesos esto con el
            fin de reducir costos en operatividad de los mismos y hacer proyecciones personalizadas a futuro.<p>
            <h2>CONFIABLE</h2>
            <p>El diseño modular de SIIE, permite generar informes con datos disponibles, auditables y cuantificables por
            procesos para la gestión y mejoramiento de su empresa.</p>
            <h2>FLEXIBLE</h2>
            <p>El diseño modular de SIIE permite personalizar el servicio según las necesidades de la empresa,  generando
            cada día mejores prácticas en la gestión de operación y mantenimiento de los procesos.</p>
        </div>
	</div>
    <!--FIN CONTENIDO-_>
    <!--LATERAL-->
    <div id="content_lateral">
    	

		<div class="cabeza verde cabeza"><H1>Información</H1></div>
		<div class="cuerpo lineaverde">
			<center><img src="imagenes/logo/motumdata01x120.png" width="120" height="120"></center>
            <p style="text-align:left; font-size:13px">
            Desarrollada por <a target="_blank" href="http://motumdata.com">Motum Data DEV</a><br />
            Twitter: <a target="_blank" href="http://twitter.com/motumdata">@motumdata</a><br />
            E-mail: <a target="_blank" href="mailto:contacto@motumdata.com">contacto@motumdata.com</a><br />
            Tel: +57 300 7747271
            </p>
      	</div>
      
		<div class="cabeza naranja"><H1>Contacto</H1></div>
        <div class="cuerpo lineanaranja">
            <p>Si desea recibir información adicional sobre SIIE o requiere una versión DEMO, podrá solicitarla haciendo clic 
            <a data-relid="contactenos" href="#contacto" title="Contactarse con Motum Data">aquí</a>.</p>
        </div>
    </div>    
    <!--FIN DE LATERAL-->
    <div class="final"></div>
</div>
<!--FORM CONTACTO-->
<div id="contContent" class="semi_3 cuerpo" data-relef="contacto">
    <div id="contactenos">
        <div class="header azul redondeado_03 borde_1"><H1>Contactenos</H1></div>
        <div class="info col_menu_t02 borde_1">
            <a target="_blank" href="http://motumdata.com">Motum Data DEV</a><br />
            Twitter: <a target="_blank" href="http://twitter.com/motumdata">@motumdata</a><br />
            E-mail: <a target="_blank" href="mailto:contacto@motumdata.com">contacto@motumdata.com</a><br />
            Tel: +57 300 7747271
        </div>
        <div class="contform col_bg01 redondeado_02 borde_1">
            <form id="contacto" name="contacto" method="post" action="/contact">  
            	<input type="hidden" value="<?php echo $keyForm?>" name="key"/>  
                <div class="etiqueta_top">
                    <label for="nombres" data-txtid="txt-1069-0"></label>
                    <input class="input borde_1 redondeado_01" data-over="glow04 active" type="text" name="nombres" id="nombres" maxlength="80" placeholder="Nombre"/>
                </div>
                <div class="etiqueta_top">
                    <label for="correo">E-mail</label>
                    <input class="input borde_1 redondeado_01" data-over="glow04 active" type="text" name="correo" id="correo" maxlength="80" placeholder="Correo Electrónico" />
                </div>    
                <div class="etiqueta_top">
                    <label for="telefono">Teléfono</label>
                    <input class="input borde_1 redondeado_01" data-over="glow04 active" type="tel" name="telefono" id="telefono" maxlength="30" placeholder="Teléfono de Contacto" />
                </div>
                <div class="etiqueta_top">
                    <label for="nombres">Mensaje</label>
                    <textarea class="input borde_1 redondeado_01" data-over="glow04 active" name="mensaje" cols="" rows=""></textarea>
                </div>
    
                <div id="note_contacto" class="note ui-widget">
                    <div id="div_mensaje_contacto">
                        <p><span class="div_contacto" id="div_icono_contacto" style="float: left; margin-right: .3em;"></span>
                        <span id="note_mensaje_contacto"></span></p>
                    </div>
                </div>    
            </form>
            <div class="etiqueta_top loader" id="loader_contacto" style="text-align:center;">
                <button id="bttn_contacto_send" data-form="contacto" data-textoac="true" data-icon="ui-icon-mail-closed" data-desac="false" type="submit"  class="btn_md_set">Enviar</button>
                <button id="bttn_contacto_close" data-form="contacto" data-textoac="true" data-icon="ui-icon-circle-minus" data-desac="false" type="submit"  class="btn_md_set">Cerrar</button>
            </div>
        </div>
        <div class="final"></div>
    </div>
</div>
<!--FIN CONTACTO-->
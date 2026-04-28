<?php

if($infId==2||$infId==3||$infId==4||$infId==5||$infId==6
    ||$infId==7||$infId==8||$infId==9||$infId==10||$infId==11){    
    $fechaOBJHoy = new DateTime();
    $fechaHoyF=($fechaOBJHoy->format('U'))*1000;
    $fecha=$fechaOBJHoy->format('d/m/Y');    
    
    $fechaOBJIni=$fechaOBJHoy->sub(new DateInterval('P30D'));
    $fechaIni=$fechaOBJIni->format('d/m/Y');     
?>
    <form class="iform col_bg03" name="frm-subir" method="get" action="/informe" target="_blank">
        <header class="frm-h">
            <h2 class="frm-tit col_titles"><?php echo $titulo ?></h2>
            <h3 class="frm-stit col_titles2"><?php echo $sub_titulo?></h3>
            <div class="x bt_col_close" data-close="form"><div class="cx"><i class="fa fa-close"></i></div></div>
        </header>   
        <div class="frm-body">
            
            <label class="frm-label req" for="fechai" data-txtid="txt-1054-0"></label>
            <input class="input" data-minobj="fechaf" data-maxdate="<?php echo $fechaHoyF ?>" data-id="fechai" type="text" name="fechai" id="fechai" data-tipo="date" value="<?php echo $fechaIni ?>" data-required="true"/>

            <label class="frm-label req" for="fechaf" data-txtid="txt-1055-0"></label>
            <input class="input" data-maxobj="fechai" data-maxdate="<?php echo $fechaHoyF ?>" data-id="fechaf" type="text" name="fechaf" id="fechaf" data-tipo="date" value="<?php echo $fecha ?>" data-required="true"/>
     

            <input type="hidden" name="md" value="<?php echo $md?>" />
        </div>
        <div class="message"></div>
        <div class="botones">
            <button class="button bt_col1 light" data-txtid="txt-1006-0"></button>
        </div>
    </form>
<?php
}
?>
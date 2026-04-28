<?php
$cnf=10;
$permiso=$PermisosA[$cnf]["P"];
$usuario_actual=$_AUSER["INFO_USUARIO"];
?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
            <?php   
            $s=$sqlCons[1][3]."
                LEFT JOIN adm_ventanas_etipo ON adm_ventanas_etipo.ID_VENTANA=adm_ventanas_cont.ID_VENTANA AND adm_ventanas_etipo.TIPO_GRUPOPAL=$_GCLIENTE
                LEFT JOIN adm_grupos_ven ON adm_grupos_ven.ID_VENTANA=adm_ventanas_cont.ID_VENTANA AND adm_grupos_ven.ID_GRUPO=$_GRUPO
                WHERE adm_ventanas_etipo.PERMISO=1 AND adm_grupos_ven.PERMISO_GRUPOVEN=1 ".$sqlOrder[1][3];
            $reqConfig = $dbEmpresa->prepare($s);
            $reqConfig->execute();

            $i=0;
            $Titulos=array();
            $cnfs=array();
            while($regConfig = $reqConfig->fetch()){
                $Titulos[]=imprimir($regConfig["TITULO_VENTANA"]);
                $cnfs[]=$regConfig["ID_VENTANA"];
                $_selected=$i==0?'_selected':'';
                ?><li class="li bt_col2 <?php echo $_selected?>" data-idtab="<?php echo $i+1; ?>"><span><?php echo imprimir($regConfig["TITULO_VENTANA"]); ?></span><i class="fa fa-caret-up"></i></li><?php   
                $i++;
            } ?>
        </ul>
    </nav>
    <!--FIN DE LATERAL-->

    <div class="cnf-lat col_bg02">
        <?php   
            foreach ($Titulos as $i => $Titulo){
        ?>
        <h2 class="cnf-tit" data-idtab="<?php echo $i+1; ?>"><span><?php echo $Titulo; ?></span></h2>
        <section class="cnf-cont" data-tab="<?php echo $i+1; ?>"> 
            
            <div class="lists" data-cnf="<?php echo $cnfs[$i] ?>" data-tipo="auto-list">
                <div class="w-results">
                    <section class="nav-bar col_bg02"><nav class="bars dsinline"></nav><section class="search dsinline"></section></section>
                    <section class="resuls col_bg02" data-tipo="2"></section>
                    <section class="pages col_bg02"></section>
                </div>
            </div>

        </section>
        <?php
        }?>
    </div>
</div>

<?php
$cnf=80;
$permiso=$PermisosA[$cnf]["P"];
$usuario_actual=$_AUSER;
?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
        <?php   
            $s=$sqlCons[1][73]
                ." AND adm_informes_detalle.HAB_INFORME=0 AND  adm_informes_grupo.HAB_GINFORME=0 "
                ." GROUP BY adm_informes_detalle.ID_GINFORME "
                .$sqlOrder[1][73];

            $reqConfig = $dbEmpresa->prepare($s);
            $reqConfig->execute();

            $i=0;
            $Titulos=array();
            $cnfs=array();
            while($regConfig = $reqConfig->fetch()){
                $Titulos[]=imprimir($regConfig["NOM_GINFORME"]);
                $cnfs[]=80;
                $scnfs[]=$regConfig["ID_GINFORME"];
                $_selected=$i==0?'_selected':'';
                ?><li class="li bt_col2 <?php echo $_selected?>" data-idtab="<?php echo $i+1; ?>" title="<?php echo imprimir($regConfig["DESC_GINFORME"]); ?>"><span><?php echo imprimir($regConfig["NOM_GINFORME"]); ?></span><i class="fa fa-caret-up"></i></li><?php
                $i++;
            }?>
        </ul>
    </nav>
    <!--FIN DE LATERAL-->

    <!--CONTENIDO DE LOGIN-->
    <div class="cnf-lat col_bg02">
        <?php   
        foreach ($Titulos as $i => $Titulo){?>
            <h2 class="cnf-tit" data-idtab="<?php echo $i+1; ?>"><span><?php echo $Titulo; ?></span></h2>
            <section class="cnf-cont" data-tab="<?php echo $i+1; ?>">
                <div class="lists" data-cnf="<?php echo $cnfs[$i] ?>" data-scnf="<?php echo $scnfs[$i] ?>" data-tipo="auto-list">
                    <div class="w-results">
                        <section class="resuls col_bg02" data-tipo="2"></section>
                        <section class="pages col_bg02"></section>
                    </div>
                </div>
            </section>
        <?php
        } ?>
    </div>
</div>
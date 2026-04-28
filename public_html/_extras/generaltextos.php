<?php
$cnf=10000;
$permiso=$PermisosA[$cnf]["P"];
$usuario_actual=$_AUSER;
$id_sha=encrip($_GCLIENTE);
$m_md=nuevo_item().encrip(10001,2);
?>
<div class="md_carga col_bg03" data-tpage="3" data-cnf="<?php echo $cnf?>">
    <!--LATERAL-->
    <nav class="cnf-nav col_bg01">
        <ul class="ul light">
            <?php if($PermisosA[10001]["P"]==1){ ?><li class="li bt_col2 _selected" data-idtab="1"><span data-txtid="txt-1214-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><!--
         --><?php if($PermisosA[10003]["P"]==1){ ?><li class="li bt_col2" data-idtab="2"><span data-txtid="txt-1219-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><!--
         --><?php if($PermisosA[10002]["P"]==1){ ?><li class="li bt_col2" data-idtab="3"><span data-txtid="txt-1161-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><!--
         --><?php if($PermisosA[10004]["P"]==1){ ?><li class="li bt_col2" data-idtab="4"><span data-txtid="txt-1160-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><!--
         --><?php if($PermisosA[10000]["P"]==1){ ?><li class="li bt_col2" data-idtab="6"><span data-txtid="txt-1110-0"></span><i class="fa fa-caret-up"></i></li><?php } ?><?php
            if($_PROYECTO==22) include('generaltextos_022.php');
         ?></ul>
    </nav>
    <!--FIN DE LATERAL-->

    <!--CONTENIDO DE LOGIN-->
    <div class="cnf-lat col_bg02">
        <?php if($PermisosA[10001]["P"]==1){ ?>
        <h2 class="cnf-tit" data-idtab="1"><span data-txtid="txt-1214-0"></span></h2>
        <section class="cnf-cont" data-tab="1">
            <div class="mform">
                <label class="frm-label" for="idioma-glob" data-txtid="txt-3004-0"></label>
                <select class="input" data-over="<?php echo $data_over; ?>" name="idioma" id="idioma-glob" data-autocombo="true" data-content="txtglob" data-total="false">
                <?php 
                    $s=$sqlCons[1][76]." WHERE fac_idioma.HAB_IDIOMA=0 ".$sqlOrder[1][76];
                    $req = $dbEmpresa->prepare($s);
                    $req->execute();
                    echo crear_select($req,'ID_IDIOMA','IDIOMA',0,1,'txt-1134-0');
                ?>
                </select>
                <div data-id="txtglob" data-carga="true" data-tp="1" data-md="<?php echo $m_md?>"></div>
            </div>
        </section>
        <?php } ?>

        <?php if($PermisosA[10003]["P"]==1){ ?>
        <h2 class="cnf-tit" data-idtab="2"><span data-txtid="txt-1219-0"></span></h2>
        <section class="cnf-cont" data-tab="2">
            <div class="lists" data-cnf="10003" data-scnf="1" data-tipo="auto-list">
                <div class="w-results">
                    <section class="nav-bar col_bg02"><nav class="bars dsinline"></nav><section class="search dsinline"></section></section>
                    <section class="resuls col_bg02" data-tipo="2"></section>
                    <section class="pages col_bg02"></section>
                </div>
            </div>
        </section>
        <?php } ?>

        <?php if($PermisosA[10002]["P"]==1){ ?>
        <h2 class="cnf-tit" data-idtab="3"><span data-txtid="txt-1161-0"></span></h2>
        <section class="cnf-cont" data-tab="3">
            <div class="lists" data-cnf="10002" data-scnf="1" data-tipo="auto-list">
                <div class="w-results">
                    <section class="nav-bar col_bg02"><nav class="bars dsinline"></nav><section class="search dsinline"></section></section>
                    <section class="resuls col_bg02" data-tipo="2"></section>
                    <section class="pages col_bg02"></section>
                </div>
            </div>
        </section>
        <?php } ?>

        <?php if($PermisosA[10004]["P"]==1){ ?>
        <h2 class="cnf-tit" data-idtab="4"><span data-txtid="txt-1160-0"></span></h2>
        <section class="cnf-cont" data-tab="4">
            <div class="lists" data-cnf="10004" data-scnf="1" data-tipo="auto-list">
                <div class="w-results">
                    <section class="nav-bar col_bg02"><nav class="bars dsinline"></nav><section class="search dsinline"></section></section>
                    <section class="resuls col_bg02" data-tipo="2"></section>
                    <section class="pages col_bg02"></section>
                </div>
            </div>
        </section>
        <?php } ?>

        <?php if($PermisosA[10000]["P"]==1){ ?>
        <h2 class="cnf-tit" data-idtab="6"><span data-txtid="txt-1110-0"></span></h2>
        <section class="cnf-cont" data-tab="6">
            <div class="lists" data-cnf="10000" data-scnf="1" data-tipo="auto-list">
                <div class="w-results">
                    <section class="nav-bar col_bg02"><nav class="bars dsinline"></nav><section class="search dsinline"></section></section>
                    <section class="resuls col_bg02" data-tipo="2"></section>
                    <section class="pages col_bg02"></section>
                </div>
            </div>
        </section>
        <?php } ?>

        <?php
        if($_PROYECTO==22) include('generalapp_cnt_022.php');
        ?>
    </div>
</div>
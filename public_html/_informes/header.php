<?php
$fechaOBJ = new DateTime();
$fecha=$fechaOBJ->format('d/m/Y');
?>
<header class="repots">
    <div class="head">
        <div class="lf">
            <div class="wrapimg imagen">
                <div class="_fix_50"></div>
                <div class="_zfix_00 stdImg" style="background-image:url(<?php echo $_LOGO_CLIENT?>)"></div>
            </div>
        </div>
        <div class="md">
            <h1><?php echo $titulo?></h1>
            <h2><?php echo $sub_titulo?></h2>
        </div>
        <div class="rg ">
            <div class="borde_7">
            <h3><span data-txtid="txt-1243-0"></span>: <?php echo $rev?></h3>
            <p><span data-txtid="txt-1244-0"></span>: <?php echo $fecharev?></p>
            <p><span data-txtid="txt-1245-0"></span>: <?php echo $fecha?></p>
            </div>
        </div>
        <div class="fin"></div>
    </div>
</header>
<?php

/*LOGO EMPRESA*/

$TituloWeb=$_PARAMETROS["RAZON_SOCIAL"];
if($_PARAMETROS["MENCABEZADO"]!="") $TituloTop=sprintf("%s - %s",$TituloWeb,$_PARAMETROS["MENCABEZADO"]);
else                                $TituloTop=$TituloWeb;

 
$md_usuario=encrip($_USUARIO).encrip(36,2); 
$ShowName=$_sysvars["name"]==''?$_sysvars["email"]:$_sysvars["name"].' '.$_sysvars["lastname"];
?><header class="servicio col_bg01">        
    <div class="content TMargin">  
        <div class="up">
            <div class="cclogo dsinline">
                <button class="redondeado_01 bt_col1 navicon dsinline" data-action="cmodulos" data-option="navicon">                    
                    <i class="fa fa-navicon"></i>
                </button>
                <div class="logo dsinline">                    
                    <div class="mclogo dsinline">
                        <?php
                        if($_PROYECTO==22){?>
                            <a href="/" title="<?php echo TituloWeb?>"><img src="<?php echo $_LOGO_CLIENT?>" alt="<?php echo TituloWeb?>"/></a>
                        <?php
                        }
                        else{
                        ?>
                            <a href="/" title="<?php echo TituloWeb?>"><img src="<?php echo $_LOGO?>" alt="<?php echo TituloWeb?>"/></a>   
                        <?php
                        }?>            
                    </div>
                </div>
            </div><!--                         
         --><h1 class="btitle dsinline"></h1><!--                         
         --><div class="menus dsinline">
                
                <?php
                if($_PARAMETROS["NOTIF_ADDR"]!=''&&$_PARAMETROS["NOTIF_KEY"]!=''){
                ?>
                    <button class="redondeado_01 bt_col2 navnotify dsinline _selected" data-action="notify" data-option="navnotify">                    
                        <i class="fa fa-bolt"></i>
                    </button>
                <?php
                }
                ?>
                
                <?php
                $_IS_MAN=file_exists("_manual/$_PROYECTO/$_CLIENTE/manual.pdf");
                $_LINK_MAN="/_manual/$_PROYECTO/$_CLIENTE/manual.pdf";
                if($_IS_MAN){?>
                <a href="<?php echo $_LINK_MAN ?>" target="_blank" class="buttonlink bt_col3">                    
                    <i class="fa fa-life-ring dsinline"></i>
                    <span class="buttonds  dsinline">Manual</span>                    
                </a>
                <?php
                }?>

                <button class="buttonlink bt_col3" data-option="toggle">                    
                    <p class="u-display stdImg dsinline col_bg02" style="background-image:url(<?php echo $_USR_IMG?>)"></p>
                    <p class="c-logo stdImg dsinline col_bg02" style="background-image:url(<?php echo $_LOGO_CLIENT?>)"></p>
                    <span class="buttonds  dsinline"><?php echo $ShowName?></span>
                    <i class="fa fa-angle-down dsinline"></i>
                </button>
                
                <ul class="smenu col_menu">
                    <li class="lim col_menu_t02 br-button"><a href="/abstract/?md=<?php echo $md_usuario?>">
                            <i class="fa fa-user dsinline bicon"></i><span class="blabel" data-txtid="txt-1192-0"></span>
                    </a></li>
                    <li class="lim col_menu_t02 br-button"><a href="/user">
                            <i class="fa fa-edit dsinline bicon"></i><span class="blabel" data-txtid="txt-1211-0"></span>
                    </a></li>
                    <li class="li title col_titles1 medium"><span data-txtid="txt-1160-0"></span></li>
                    <?php
                    $s=$sqlCons[1][81]." WHERE adm_empresas.HAB_MEMPRESA=0 AND adm_empresas.ID_MEMPRESA IN (SELECT adm_usuarios_empresa.ID_MEMPRESA FROM adm_usuarios_empresa WHERE adm_usuarios_empresa.ID_USUARIO=$_USUARIO) ".$sqlOrder[1][81];                 
                    $req = $dbEmpresa->prepare($s);
                    $req->bindParam(':idioma', $_IDIOMA);
                    $req->execute();
                    while($reg = $req->fetch()){
                        $id_sha_emp=encrip($reg["ID_MEMPRESA"]);
                        $md_empresa=$id_sha_emp.encrip(36,2).$id_sha_emp.'001'; 
                        $link=sprintf('/operation/?md=%s',$md_empresa);

                        $ClassSel=$_CLIENTE==$reg["ID_MEMPRESA"]?'_selected':'';
                    ?>
                        <li class="lim col_menu_t02 <?php echo $ClassSel?> br-button"><a href="<?php echo $link?>" class="">
                                <span class="blabel"><?php echo $reg["NOMB_MEMPRESA"] ?></span>
                        </a></li> 
                    <?php
                    }
                    if($PermisosA[4]["P"]==1){
                        $id_sha=nuevo_item();
                        $md=$id_sha.encrip(4,2).$id_sha.'001';?>
                        <li class="lim col_menu_t03 br-button" data-txtid="txt-1159-1" data-attr="title"><a href="<?php echo sprintf('/operation/?md=%s',$md)?>" class="">
                                <span class="blabel" data-txtid="txt-1159-0"></span>
                        </a></li> 
                    <?php
                    }?>
                </ul>
                <a class="buttonlink salir" href="/logout" data-action="logout" data-txtid="txt-1209-0" data-attr="title" data-key="<?php echo $_key?>">
                    <span data-txtid="txt-1209-0"></span>
                </a>
            </div>
        </div>
    </div>
</header>
<nav class="cmodulos col_bg01" data-id="cmodulos">        
    <div class="content TMargin">
        <div class="modules">
            <ul class="smodules col_menu">
                <?php
                $s=$sqlCons[1][69]."ORDER BY adm_ventanas_menu.UBICACION_MENU<>0 DESC,adm_ventanas_menu.UBICACION_MENU,adm_ventanas_menu.ORDEN_VENTANA";                    
                $req = $dbEmpresa->prepare($s);
                $req->execute();  

                $groupid=0;      
                while($reg = $req->fetch()){   
                    $name=($reg["TIPO_INFO"]==1&&$reg["SCVENTANA"]!="")?$reg["SCVENTANA"]:$reg["VENTANA_NOMBRE"];   
                    $selected=$_main==$reg["ACR_VENTANA"]?'_selected':'';
                    if($groupid!=$reg["UBICACION_MENU"]){
                        $groupid=$reg["UBICACION_MENU"];
                        if($reg["TIPO_INFO"]==1){?>
                            <li class="li title col_menu_t01"><span data-txtid="txt-1249-0"></span></li>
                        <?php
                        }
                        else{?>
                             <li class="li title col_menu_t01"><span><?php echo $reg["DESC_GVENTANA"]?></span></li>
                        <?php
                        }
                    }
                ?>
                <li class="lim col_menu_t02 br-button light <?php echo $selected?>" data-dinamic="true" data-href="<?php echo '/'.$reg["ACR_VENTANA"]?>" ><a href="<?php echo '/'.$reg["ACR_VENTANA"]?>" class="">
                        <span class="blabel"><?php echo $name ?></span>
                        <i class="fa fa-chevron-right"></i>
                </a></li> 
                <?php
                }?>
            </ul>
        </div>
    </div>
</nav>
<?php

$fechaOBJ = new DateTime();
$fecha=$fechaOBJ->format('d/m/Y');

$md=isset($_GET["md"])?$_GET["md"]:'';
$id_sha=mb_substr($md,0,40);
$c_sha=mb_substr($md,40,32);
$id_sha_t=mb_substr($md,72,40);
$det_plus=intval(mb_substr($md,112,3));

/***********************************/
/***********************************/
/***********************************/
$salidas=array();
foreach($_GET as $key => $val){
    if(($key!='md')&&($key!='md_object')) $salidas[$key]=$val; 
} 
$salidas["md"]=$md_object;
$salidas["md_original"]=$md;
$salidas["orden"]=0;
$salidas["hv"]=$regInf["TIPO_INFORME"]==1?'true':'false';
$salidas["_FRes"]=$regInf["FILERES_INFORME"];
$salidas["_FInc"]=$regInf["FILEREV_INFORME"];
$salidas["tp"]=2;

if($regInf["TIPO_INFORME"]==10){  
    ob_start(); 
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 17 May 1984 07:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header('Pragma: public');
    header('Content-Type: application / vnd.ms-excel'); 
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.$regInf["REF_INFORME"].'.csv;');
    header('Content-Transfer-Encoding: binary'); 
    $output = fopen('php://output', 'w');

        if($_PROYECTO==16)           include("_reports/reports_inc_016_exp.php"); //Disponibles  
    elseif($_PROYECTO==19)           include("_reports/reports_inc_019_exp.php"); //SCa  
    elseif($_PROYECTO==20)           include("_reports/reports_inc_020_exp.php"); //Appetitos  
    elseif($_PROYECTO==25)           include("_reports/reports_inc_025_exp.php"); //IER  
    elseif($_PROYECTO==31)           include("_reports/reports_inc_031_exp.php"); //CheckIn
    elseif($_PROYECTO==32)           include("_reports/reports_inc_032_exp.php"); //SuperMaestros 
    fclose($output);
    die();
}  
else{
    /***********************************/
    /***********************************/
    /***********************************/
    //LEE TODO EL GET
    $data='';
    foreach($_REQUEST as $key => $val){
        if($key!='__route__'&&$key!='_AJAX')
           $data.="data-$key=\"$val\" ";
        
    } 
    ?>
     <div class="md_carga" data-tpage="10" data-cnf="<?php echo $cnf?>" data-md="<?php echo $md?>" <?php echo $data?>></div>
<?php
} ?>
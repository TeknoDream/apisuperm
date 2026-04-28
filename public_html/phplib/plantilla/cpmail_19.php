<?php
$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','jpg',false,'t02');
$css_Tabla_Imagen='background-color: #D63330;padding:5px 0; color:#FFF';	

$css_Tabla='background-color: #F0F0F0;padding:0px;font-family: \'Trebuchet MS\', Arial, sans-serif, Helvetica;';	
$css_Titulo='color:#666;font-size:24px';
$css_Titulo2='color:#666;font-size:20px;margin:3px auto';
$css_Titulo3='color:#666;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#666;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:1px #CCC solid; padding:10px; margin:5px auto;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:14px;padding:10px;margin:10px;color:#333;background-color: #FFF;border:1px solid #d6d6d6;';	
$css_Cuerpo2='font-size:14px;padding:10px;margin:10px;color:#333;';	
$css_Pie='background-color: #D63330;margin:5px;color:#FFFFFF;font-size:12px; padding:5px;';

$css_Letrap="font-size:10px;";
$css_a='color:#333;';
$css_a2='color:#FFF;';
?>
<?php
$css_LOGO='max-height:8em';

$server_cn=$_PARAMETROS["LWSERVICE"];
$TEmail=$_PARAMETROS["S_NOMBCORTO"];
$FUrl=$_PARAMETROS["S_URLCORTA"];
$Slogan=$_PARAMETROS["S_SLOGAN"];
$OPUrl=$_PARAMETROS["WP_OPPAGE"];

$src_Imagen='http:'.$_PARAMETROS["S3_URL4"].ImgName($_PROYECTO,$_EMPRESA,0,0,'LogoApp','png',false,'big');
$css_Tabla_Imagen='padding:0.5em;color:#FFFFFF; background-color:#F2F2F2';	

$css_Tabla='background-color: #FFFFFF;padding:0px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border:1px solid #F0F0F0;';	
$css_Titulo='color:#333333;font-size:20px; padding:0px';
$css_Titulo2='color:#333333;font-size:20px;margin:3px auto';
$css_Titulo3='color:#333333;font-size:17px;margin:5px auto 1px auto';
$css_Titulo4='color:#333333;font-size:16px;margin:5px auto 1px auto';

$css_Field='border:none padding:10px; margin:5px auto;background-color:#FFFFFF;';
$css_Legen='font-size:16px; color:#333; margin:2px 10px; padding:5px; font-weight:bold';
$css_Ol='padding:5px 10px;';

$css_Cuerpo='font-size:1em;padding:0.5em;margin:10px 0 5px 0;color:#333333;';	
$css_Cuerpo2='font-size:1em;padding:0.5em;margin:10px 10px 5px 10px;color:#333333;';	
$css_Pie='background-color: #a4401f;margin:5px;color:#FFFFFF;font-size:12px; padding:5px; text-align:center';

$css_Letrap="font-size:10px;";
$css_a='color:#a4401f;';
$css_a2='color:#FFF;';
?>
<?php
$_TZ=isset($_sysvars_r["tz"])?$_sysvars_r["tz"]:'-05:00';
$_USUARIO=isset($_sysvars_r["id"])?$_sysvars_r["id"]:0; //_sysvars_r
$_PROYECTO=$_sysvars_r["project"];
$_EMPRESA=$_sysvars_r["company"];
$_AUSER=$_sysvars_r;

$_GRUPO=$_sysvars_r["admin_group"];
$PermisosA=$_sysvars_r["PermisosA"];
$_PARAMETROS=$_sysvars_r["_PARAMETROS"];

$_IDIOMA=$_sysvars_r["lang_id"]!=""?$_sysvars_r["lang_id"]:1;
$_CLIENTE=$_sysvars_r["client"]!=""?$_sysvars_r["client"]:0;
$_GCLIENTE=$_sysvars_r["gclient"]!=""?$_sysvars_r["gclient"]:0;
include("variables.php");
?>
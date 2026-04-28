<head>
  <meta charset="UTF-8">
  <meta name="robots" content="nofollow" />
  <title><?php echo $http_title?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="icon" href="<?php echo $favicon?>" type="image/x-icon"/>
  <meta name="author" content="Motum Data DEV">
  <meta name="description"        content="<?php echo $_mt_description?>">

  <link rel="manifest" href="/manifest.json">
  <!--CSS-->
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">  
  <link href="/css/new-global.css"        rel="stylesheet"/>
  <link href="/css/new-colors.css"           rel="stylesheet"/>
  <link href="/css/new-css.css" 					rel="stylesheet"/>
  <link href="/css/new-report.css"           rel="stylesheet"/>
  <link href="/css/jquery-ui.css"   rel="stylesheet"/>

  <link href="/css/jquery-te-1.4.0.css" 		rel="stylesheet"/>
  <link href="/css/jquery.minicolors.css" 	rel="stylesheet"/>
  <link href="/css/tagger.css" 				rel="stylesheet"/>
  <link href="/css/jquery.tagedit.css" 		rel="stylesheet"/>
  <link href="/css/jquery.datetextentry.css" 	rel="stylesheet"/>
  <link href="/css/jquery.timepicker.css" 	rel="stylesheet"/>

  <?php
  if($PasteCSS){
  	echo '<link href="/'.$Launch_CSS.'" 	rel="stylesheet"/>';
  }
  $colors=isset($_PARAMETROS["ID_CNFCOLOR"])?$_PARAMETROS["ID_CNFCOLOR"]:3;
  ?>

  <script>
      _PROYECTO=<?php echo $_PROYECTO?>;
      imgUrl='<?php echo $_OP_URL; ?>';
      _APP_ANDROID={id:'<?php echo $_PARAMETROS["MT_GPLAYID"] ?>',name:'<?php echo $_PARAMETROS["MT_GPLAYNAME"] ?>'}
      _APP_APPLE_IPAD={id:'<?php echo $_PARAMETROS["MT_IPADID"] ?>',name:'<?php echo $_PARAMETROS["MT_IPADNAME"] ?>'}
      _APP_APPLE_IPHONE={id:'<?php echo $_PARAMETROS["MT_IPHONEID"] ?>',name:'<?php echo $_PARAMETROS["MT_IPHONENAME"] ?>'}

      dnode={   _token_a:'<?php echo $_COOKIE["_token_a"];?>'
              , _token_b:'<?php echo $_COOKIE["_token_b"];?>'
              , _session:'<?php echo session_id();?>'
              , apikey:'<?php echo $_PARAMETROS["NOTIF_API"] ?>'
              , _company:'<?php echo $_CLIENTE ?>'}
      _notifier={url:'<?php echo $_PARAMETROS["NOTIF_ADDR"] ?>',sockets:'<?php echo $_PARAMETROS["NOTIF_KEY"] ?>'}
      G_SITEKEY='<?php echo $_PARAMETROS["G_SITEKEY"]; ?>';
      var _exts=[];
      <?php
      foreach($_files_clase as $i => $_filext){
          echo "_exts[$i]=['".implode("','",$_filext)."'];\n";
      }
      ?> 
  </script>

  <!--JQUERY-->
  <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

  <!--[if IE]><script language="javascript" type="text/javascript" src="/js/Extras/excanvas.js"></script><![endif]-->
  <!-- GRAFICOS JQUERY -->
  <script src="/js/jit-yc.js"></script>

  <!--Chart.js-->
  <script src="/js/Chart.js"></script>

  <!--UI-->
  <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

  <!--UPLOAD-->
  <script src="/js/ajaxfileupload.js"></script>

  <!--Nestable-->
  <script src='/js/jquery.nestable.js'></script>

  <!--COLORS JS -->
  <script src="/js/jquery.minicolors.js"></script>

  <!--REWRITE JS -->
  <script src="/js/jquery.address-1.5.min.js"></script>

  <script src="/js/jquery.timepicker.js"></script>

  <!--TAG -->
  <script src="/js/tagger.js"></script>
  <script src="/js/jquery.tagedit.js"></script>
  <script src="/js/jquery.autoGrowInput.js"></script>

  <!--FORMATS -->
  <script src="/js/jquery.serialize-object.js"></script>
  <script src="/js/jquery.formatNumber-0.1.1.min.js"></script>
  <script src="/js/jquery-te-1.4.0.min.js"></script>
  <script src="/js/ui/jquery.ui.datepicker-es.js"></script>
  <script src="/js/ui/jquery-ui-timepicker-addon.js"></script>
  <script src="/js/jquery.maskMoney.js"></script>
  <script src="/js/jquery.autosize-min.js"></script>
  <script src="/js/jquery.datetextentry.js"></script>

  <script src="/js/ckeditor/ckeditor.js"></script>
  <script src="/js/ckeditor/adapters/jquery.js"></script>

  <!--DEBOUNCE-->
  <script src="/js/jquery.ba-throttle-debounce.min.js"></script>

  <!--Polygon-->
  <script src="/js/polygon(1.0).js"></script>

  <!--MAPS-->
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

  <!--MD LIBS -->
  <script src="/js/mdata_scripts.js"></script> 
  <script src="/js/mdata_start.js"></script>
  <script src="/js/mdata_charge.js"></script>
  <script src="/js/mdata_forms.js"></script>
  <script src="/js/mdata_maps.js"></script>
  <?php
  $custom_js=sprintf('js/mdata_custom_%03d.js',$_PROYECTO);
  if(file_exists($custom_js)) echo '<script src="/'.$custom_js.'"></script>';

  if(!$verificar){?>
    <!-- CATPCHA -->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCaptcha&render=explicit&hl=es" async defer></script>
  <?php
  }?>
  <script id="_JS_ADD"></script>
  <style id="_CSS_ADD"></style>
</head>
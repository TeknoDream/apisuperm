<div class="loader_all show launch" data-cform="true">
	<div class="x-forms">
    	<div class="cforms" data-type="form"  data-direct="1">

            <?php
            $id_form="";
            ?>
    		<div class="b_form  <?php echo $_main==$id_form?'':'hide'?>" data-spage="/<?php echo $id_form?>">
                <div class="_form_tit">
                    <a href="<?php echo $_PARAMETROS["LWSERVICE"] ?>"><img class="slogo" src="<?php echo $_LOGO_SET?>" alt="<?php echo $_mt_title?>" /></a>
                </div><!--
             --><form class="form" action="/<?php echo $Launch_Dir?>/phplib/base_acc.php" method="POST"> 
                    <h1 class="t1" data-txtid="txt-1173-0"></h1>
                    <input type="hidden" name="tp" value="500">          
                   
                    <label class="eti req" data-txtid="txt-1072-0"></label>
                    <input type="text" name="usuario" class="input"  value="" />
                   
                    <label class="eti req" data-txtid="txt-1097-0"></label>
                    <input type="password" name="passw" class="input" value="" />
                   	
                     <div class="eti">
                        <input class="input" type="checkbox" name="recordar" id="recordar" value="1" />
                        <label for="recordar"><span data-txtid="txt-1172-0"></span></label>
                    </div>
                    
                    <div class="message"></div>
                           
                    <div class="botones">
                        <input type="submit" class="button gray" data-txtid="txt-1173-0" data-attr="value" data-emergente="send"/>
                    	<a href="/forgot" class="link" data-txtid="txt-1184-0"></a>
                    </div> 
                    
                </form>
            </div>
              
            <?php
			$id_form="code";
			?>
    		<div class="b_form  <?php echo $_main==$id_form?'':'hide'?>" data-spage="/<?php echo $id_form?>">
                <div class="_form_tit">
                    <a href="<?php echo $_PARAMETROS["LWSERVICE"] ?>"><img class="slogo" src="<?php echo $_LOGO_SET?>" alt="<?php echo $_mt_title?>" /></a>
                </div><!--
             --><form class="form" action="/<?php echo $Launch_Dir?>/phplib/base_acc.php" method="POST"> 
                    <h1 class="t1" data-txtid="txt-1180-0"></h1>     
                    <input type="hidden" name="tp" value="502">          
                   
                    <label class="eti req" data-txtid="txt-1181-0"></label>
                    <input type="text" name="empresa" class="input"  value="" />
                   	
                    <label class="eti req" data-txtid="txt-1072-0"></label>
                    <input type="email" name="email" class="input" value="" />
                    
                    <label class="eti" data-txtid="txt-1183-0"></label>
                    <textarea name="mensaje" class="input"></textarea>
                    
                    <div class="message"></div>
                           
                    <div class="botones">                    	
                        <input type="submit" class="button gray" data-txtid="txt-1180-0" data-attr="value" data-emergente="send"/>
                    </div> 
                    <div class="pat_05">
                        <a href="/register" class="link" data-txtid="txt-1179-0"></a>
                    </div>
                </form>
            </div>

            <?php
            $id_form="recovery";
            ?>
            <div class="b_form  <?php echo $_main==$id_form?'':'hide'?>" data-spage="/<?php echo $id_form?>">
                <div class="_form_tit">
                    <a href="<?php echo $_PARAMETROS["LWSERVICE"] ?>"><img class="slogo" src="<?php echo $_LOGO_SET?>" alt="<?php echo $_mt_title?>" /></a>
                </div><!--
             --><form class="form" action="/<?php echo $Launch_Dir?>/phplib/base_acc.php" method="POST">
                    <h1 class="t1" data-txtid="txt-1185-0"></h1>     
                    <input type="hidden" name="tp" value="503">          
                   
                    <label class="eti req" data-txtid="txt-1097-0"></label>
                    <input type="password" name="password" class="input" value="" />
                    
                    <label class="eti req" data-txtid="txt-1099-0"></label>
                    <input type="password" name="vpassword" class="input" value="" />                   
                    

                    <input type="hidden" name="codrec" class="input"  value="<?php echo $_GET["code"] ?>" />  
                    
                    <div class="message"></div>
                           
                    <div class="botones">    
                        <input type="submit" class="button gray" data-txtid="txt-1182-0" data-attr="value" data-emergente="send"/>
                        <a href="/" class="link" data-txtid="txt-1173-0"></a>
                    </div>
                </form>
            </div>

            <?php
            $id_form="forgot";
            ?>
            <div class="b_form  <?php echo $_main==$id_form?'':'hide'?>" data-spage="/<?php echo $id_form?>">
                <div class="_form_tit">
                    <a href="<?php echo $_PARAMETROS["LWSERVICE"] ?>"><img class="slogo" src="<?php echo $_LOGO_SET?>" alt="<?php echo $_mt_title?>" /></a>
                </div><!--
             --><form class="form" action="/<?php echo $Launch_Dir?>/phplib/base_acc.php" method="POST">
                    <h1 class="t1" data-txtid="txt-1185-0"></h1>     
                    <input type="hidden" name="tp" value="504">          
                   
                    <label class="eti req" data-txtid="txt-1072-0"></label>
                    <input type="email" name="email" class="input" value="" /> 

                    <div class="g-recaptcha" id="catpcha_forgot" data-captcha="true"></div>
                    
                    <div class="message"></div>
                           
                    <div class="botones">
                        <input type="submit" class="button gray" data-txtid="txt-1186-0" data-attr="value" data-emergente="send" disabled/>
                        <a href="/" class="link" data-txtid="txt-1173-0"></a>
                    </div> 
                </form>
            </div>
    	</div>
    </div>
</div>

<?php
/*
><\?php echo \$_textos\[(.*)\]\[(.*)\]\?
*/
?>
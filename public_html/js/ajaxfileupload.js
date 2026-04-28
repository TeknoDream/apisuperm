
$.extend({
    

    createUploadIframe: function(id, uri){
            var myContent = '<!DOCTYPE html>'
            + '<html><head><meta name="format-detection" content="telephone=no"></head>'
            + '<body></body></html>';
            var frameId = 'jUploadFrame' + id    
            ,   src='' 
            if(window.ActiveXObject){
                if(typeof uri== 'boolean'){
                    src='javascript:false'
                    iframeHtml += ' src="' + 'javascript:false' + '"';
                }
                else if(typeof uri== 'string'){
                    src=uri
                    iframeHtml += ' src="' + uri + '"';
                }   
            }
            var newIframe = document.createElement('iframe');
            newIframe.width = 0;
            newIframe.height = 0;0
            newIframe.id=frameId
            newIframe.name=frameId
            document.body.appendChild(newIframe);
            newIframe.contentWindow.document.open('text/htmlreplace');
            newIframe.contentWindow.document.write(myContent);           
            return newIframe;       
    },
    createUploadForm: function(id, s, data){
        var $fileElementId=s.fileElementId
        ,   type=s.type==undefined?'GET':s.type
        ,   formId = 'jUploadForm' + id
        ,   fileId = 'jUploadFile' + id
        ,   form = $('<form  action="" method="'+type+'" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>')
        ,   PrintInput=function(form,name,value){
                if(typeof value == 'object'){
                    $.each(value,function(ix,dt){
                        var s_name=name+'['+ix+']'
                        ,   s_value=dt
                        if(typeof s_value == 'object')
                            PrintInput(form,s_name,s_value)
                        else                            
                            $('<input type="hidden" name="'+ s_name +'" value="' + s_value + '" />').appendTo(form);
                    })
                }
                else
                    $('<input type="hidden" name="' + name + '" value="' + value + '" />').appendTo(form);
            }

        if(data){
            for(var i in data){
                var name=i
                ,   value=data[i]                
                PrintInput(form,name,value)
            }
        }   

        var $oldElement = $fileElementId;
        if($oldElement.length){
            var iEl=0;
            $oldElement.each(function(){       
                var newElement = $(this).clone();
                $(this).attr('id', fileId+iEl.toString());
                $(this).before(newElement);
                $(this).appendTo(form);
                iEl++;
            })
        }

        form.css('position', 'absolute')
              .css('top', '-1200px')
              .css('left', '-1200px')
              .appendTo('body')

       
        return form;
    },
    ajaxFileUpload: function(s) {
        s = $.extend({}, $.ajaxSettings, s);
        var id = new Date().getTime()        
        var form = $.createUploadForm(id, s, (typeof(s.data)=='undefined'?false:s.data));
        var io = $.createUploadIframe(id, s.secureuri);
        var frameId = 'jUploadFrame' + id;
        var formId = 'jUploadForm' + id;        
        // Watch for a new set of requests
        if ( s.global && ! $.active++ ){
            $.event.trigger( "ajaxStart" );
        }            
        var requestDone = false;
        // Create the request object
        var xml = {}   
        if ( s.global )
            $.event.trigger("ajaxSend", [xml, s]);
        // Wait for a response to come back
        var uploadCallback = function(isTimeout)
        {           
            var io = document.getElementById(frameId);
            try 
            {               
                if(io.contentWindow)
                {
                     xml.responseText = io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;
                     xml.responseXML = io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;
                     
                }else if(io.contentDocument)
                {
                     xml.responseText = io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;
                    xml.responseXML = io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;
                }                       
            }catch(e)
            {
                $.handleError(s, xml, null, e);
            }
            if ( xml || isTimeout == "timeout") 
            {               
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if ( status != "error" )
                    {
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = $.uploadHttpData( xml, s.dataType );    
                        // If a local callback was specified, fire it and pass it the data
                        if ( s.success )
                            s.success( data, status );
    
                        // Fire the global callback
                        if( s.global )
                            $.event.trigger( "ajaxSuccess", [xml, s] );
                    } else
                        $.handleError(s, xml, status);
                } catch(e) 
                {
                    status = "error";
                    console.log("ERRForm: "+xml.responseText)   
                    //$.handleError(s, xml, status, e);

                }

                // The request was completed
                if( s.global )
                    $.event.trigger( "ajaxComplete", [xml, s] );

                // Handle the global AJAX counter
                if ( s.global && ! --$.active )
                    $.event.trigger( "ajaxStop" );

                // Process result
                if ( s.complete )
                    s.complete(xml, status);

                $(io).unbind()

                setTimeout(function()
                                    {   try 
                                        {
                                            $(io).remove();
                                            $(form).remove();   
                                            
                                        } catch(e) 
                                        {
                                            $.handleError(s, xml, null, e);
                                        }                                   

                                    }, 100)

                xml = null

            }
        }
        // Timeout checker
        if ( s.timeout > 0 ){
            setTimeout(function(){
                // Check to see if the request is still happening
                if( !requestDone ) uploadCallback( "timeout" );
            }, s.timeout);
        }
        try{
            var form = $('#' + formId);
            form.attr({'action':s.url,'target':frameId});
            if(form.encoding){
                form.attr('encoding', 'multipart/form-data');               
            }
            else{   
                form.attr('enctype', 'multipart/form-data');            
            }           
            form.submit();

        } catch(e) {            
            $.handleError(s, xml, null, e);
        }
        
        $('#' + frameId).load(uploadCallback    );
        return {abort: function () {}}; 

    },

    uploadHttpData: function( r, type ) {
        var data = !type;
        data = type == "xml" || data ? r.responseXML : r.responseText;
        // If the type is "script", eval it in global context
        if ( type == "script" )
            $.globalEval( data );
        // Get the JavaScript object, if JSON is used.
        if ( type == "json" ){
            //eval( "data = " + data );
            data=JSON.parse(data);
        }
        // evaluate scripts within html
        if ( type == "html" )
            $("<div>").html(data).evalScripts();

        return data;
    }
})


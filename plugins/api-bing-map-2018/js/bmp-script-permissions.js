var bmpX = jQuery.noConflict();
bmpX( function(){

    bmpX('#bmp_save_permissions').on('click', function(){
        //    var bmp_data_perms = {"editor":false,"author":false,"contributor":false};
        
        bmp_data_perms.editor = bmpX('#bmp_editor_permissions').prop('checked');
        bmp_data_perms.author = bmpX('#bmp_author_permissions').prop('checked');
        bmp_data_perms.contributor = bmpX('#bmp_contributor_permissions').prop('checked');
        bmp_data_perms.hide_api_key = bmpX('#bmp_hide_api_key').prop('checked');
        bmp_data_perms.nonce_bing_map_pro = bmpX('#nonce_bing_map_pro').val();

        var data_ajax = {
            action: 'bmp_ajax_permissions',
            type : 'POST',
            data:   bmp_data_perms,
            dataType : 'json',
            contentType : 'application/json'
        };
      
       
        bmpX.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data_ajax,
            beforeSend : function(){
                bmpX('.loaderImg').show();
            }, success : function( data ){ 
                try {
                    let data_obj = JSON.parse( data );          
                    if( typeof data_obj == 'object' && 'error' in data_obj && data_obj['error'] ){
                        alert( data_obj['message'] );           
                        bmpX('.loaderImg').hide();
                        return;
                    }
                } catch (error) {
                    
                }
            }, error : function( request, status, error ){
                bmpX('#ajaxError').show();   
                console.error( 'Request ' + request + ' - Status: ' + status + ' - Error: ' + error);    
            }, complete : function( response ){
                bmpX('.loaderImg').hide();        
            }
        });
    });
        
    
});
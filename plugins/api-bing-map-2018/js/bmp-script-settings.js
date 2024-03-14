var bmpX = jQuery.noConflict();
bmpX( function( bmpX ){

    bmp_validate_woo();

    function bmp_validate_woo(){
        if( ! bmp_settings_page.bmp_woo_activated )
            bmpX('.bmp-req-woo-installed').addClass('text-danger');
    
        if( ! bmp_settings_page.bmp_woo_valid_api )
            bmpX('.bmp-req-woo-api').addClass('text-danger');

        if( bmp_settings_page.bmp_woo_autosuggest_enabled == 1 )
            bmpX('#bmp_settings_ckb_autosuggest').bootstrapToggle( 'on')
        else
            bmpX('#bmp_settings_ckb_autosuggest').bootstrapToggle( 'off')

        /*
        if( ! ( bmp_settings_page.bmp_woo_activated  && bmp_settings_page.bmp_woo_valid_api ) )
            bmpX('#bmp_settings_ckb_autosuggest').prop('disabled', true );
        */
    }

    bmpX('#bmp_api_key_submit').click( function(e){
        e.preventDefault();
        var api_key                 = bmpX('#bmp_api_key').val();
        if( typeof api_key === 'undefined' )
            api_key = 'undefined';
    //    var disable_full_screen     = bmpX('#bmp_gs_disable_fullscreen').prop('checked');
        var disable_scroll_on_map   = bmpX('#bmp_disable_scroll_on_map').prop('checked');        
        var compact_navigation_bar  = bmpX('#bmp_compact_navigation_bar').prop('checked');
    //    var disable_street_view     = bmpX('#bmp_disable_street_view').prop('checked');
        var disable_zoom            = bmpX('#bmp_disable_zoom').prop('checked');
        var map_refresh             = bmpX('#bmp_map_refresh').prop('checked');


        let $bmp_pin_desktop_width  = bmpX('#bmp_settings_pin_desktop_width').val();
        let $bmp_pin_desktop_height = bmpX('#bmp_settings_pin_desktop_height').val();

        let $bmp_pin_tablet_width = bmpX('#bmp_settings_pin_tablet_width').val();
        let $bmp_pin_tablet_height = bmpX('#bmp_settings_pin_tablet_height').val();

        let $bmp_pin_mobile_width = bmpX('#bmp_settings_pin_mobile_width').val();
        let $bmp_pin_mobile_height = bmpX('#bmp_settings_pin_mobile_height').val();
        let $bmp_settings_ckb_autosuggest = bmpX('#bmp_settings_ckb_autosuggest').prop('checked') ? 1 : 0;
        let $bmp_restrict_suggest = bmpX.trim( bmpX('#bmp_restrict_autosuggest').val() );

        if( ($bmp_pin_desktop_width < 30 ) || ($bmp_pin_tablet_width < 30) ||  ($bmp_pin_tablet_width < 30 ) ){
            alert('Pin width cannot be less than 30px ');
            return;
        }

        if( ($bmp_pin_desktop_height < 30) ||  ($bmp_pin_tablet_height < 30) ||  ($bmp_pin_mobile_height < 30 )  ){
            alert('Pin height cannot be less than 30px ');
            return;
        }

        var data = {
            bmp_api_key :    api_key,
            bmp_dsom :      disable_scroll_on_map,
            bmp_cnb :       compact_navigation_bar,
            bmp_dz  :       disable_zoom,
            bmp_mr  :       map_refresh,
            bmp_settings_pin_desktop_width   : $bmp_pin_desktop_width,
            bmp_settings_pin_desktop_height  : $bmp_pin_desktop_height,
            bmp_settings_pin_tablet_width    : $bmp_pin_tablet_width,
            bmp_settings_pin_tablet_height   : $bmp_pin_tablet_height,
            bmp_settings_pin_mobile_width    : $bmp_pin_mobile_width,
            bmp_settings_pin_mobile_height   : $bmp_pin_mobile_height,
            bmp_woo_autosuggest_enabled      : $bmp_settings_ckb_autosuggest,
            restrict_suggest                 : $bmp_restrict_suggest,
            nonce_bing_map_pro               : bmpX('#nonce_bing_map_pro').val()
        };    

        var data_ajax = {
            action: 'bmp_general_settings',
            type : 'post',
            data:   data  ,
            dataType : 'json',
            contentType : 'application/json'
        };
    
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php      
        bmpX.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data_ajax,
            beforeSend : function(){
                bmpX('.loaderImg').show();
            }, success : function( data ){  
                var dataReceived = JSON.parse( data );

                try {
                    let data_obj = dataReceived;           
                    if( typeof data_obj == 'object' && 'error' in data_obj && data_obj['error'] ){
                        alert( data_obj['message'] );           
                        bmpX('.loaderImg').hide();
                        return;
                    }
                } catch (error) {
                    
                }


                if( parseInt(dataReceived) === 3 ){
                    bmpX('#alert_api_key').show();                  
                }else{
                    bmpX('#alert_api_key').hide();   
                }      
            }, error : function( request, status, error ){
                bmpX('#ajaxError').show();   
                console.error('Request ' + request + ' - Status: ' + status + ' - Error: ' + error);    
            }, complete : function( response ){
                bmpX('.loaderImg').hide();        
            }
        });
            
        });

});

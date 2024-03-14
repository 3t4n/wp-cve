'use strict';
  
jQuery( window ).on( "load", function(){


        jQuery(document).on('click','.shop-ready-admin-notice-remote .notice-dismiss', function(){
            jQuery(this).parent().hide();    
        });
        var wc_sr_color_options = {
       
            defaultColor: false,
            change: function(event, ui){
                
            },
            clear: function() {},
            hide: true,
        };

        jQuery( ".product_data .shop-ready-color-picker" ).wpColorPicker(wc_sr_color_options);

} );


'use strict';

(function($) {

    /**
     * WooCommerce Cart Default
     */
     if (typeof wc_cart_fragments_params != "undefined") {
    var wready_cart_page = {

        selectedShipping: false,
        timer: false,
        delay: 2200,
         // Satrt cart Frageent  Object
         
        fragment_refresh : {
            url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
            type: 'POST',

            data: {
                time: new Date().getTime()
            },
            timeout: wc_cart_fragments_params.request_timeout,
            success: function(data) {

                if (data && data.fragments) {

                    $.each(data.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });

                   
                    $(document.body).trigger('wc_fragments_refreshed');
                   
                }
            },
            error: function() {
                $(document.body).trigger('wc_fragments_ajax_error');
            }
        },
        
        $cart_table: $('.woocommerce-cart-form__contents'),
        $cart_form : $('form.woocommerce-cart-form'),
        $coupon    : $('.coupon button[name="apply_coupon"]'),

        init: function() {
           
           
            $(document.body).on('input change keydown blur keyup', '.woocommerce-cart-form__contents input.qty', this.order_qty_update);
            $(document.body).on('wc_cart_emptied', this.wc_cart_emptied);
            
        },
        
        wc_cart_emptied : function( event, fragments, cart_hash ) {
		   location.reload();
		},
      
        order_qty_update: function() {

            clearTimeout(wready_cart_page.timer);
            var $qty        = $(this);
            var key_qty     = $qty.val().toLowerCase();
            var wr_cart_key = $qty.attr('data-item_key');
            wready_cart_page.timer = setTimeout(function() {
          
                wready_cart_page.fragment_refresh.data.cart = {
                    'qty': key_qty,
                    'key': wr_cart_key,
                };
               
                $.ajax(wready_cart_page.fragment_refresh);
                //location.reload();
            }, wready_cart_page.delay );

        },

        

        submit: function() {
            var $form = $(this);

            return false;
        },

    };

    wready_cart_page.init();
    }

})(jQuery);
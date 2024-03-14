(function($, wp) {

    "use strict";
    $(document).ready(function(){
        if( typeof fbq === "function" ){
            $(this.body).on( 'added_to_cart', function( event, fragments, cart_hash, button ){
                var product_id = button.data('product_id');
                wp.ajax.post('add_to_cart_facebook_pixel', {product_id: product_id})
                    .done(function(response){
                        if( response.length > 0 ) {
                            fbq( 'track', 'AddToCart', response );
                        }
                    })
                    .fail(function (){
                        console.log( "Request Failed!" );
                    });
            } );
        }
    });

})(jQuery, wp);
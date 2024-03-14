(function($, wp) {

    "use strict";
    $(document).ready(function(){
        if( typeof gtag === "function" ){
            $(this.body).on( 'added_to_cart', function( event, fragments, cart_hash, button ){
                var product_id = button.data('product_id');
                wp.ajax.post('add_to_cart_google_remarketing', {product_id: product_id})
                    .done(function(response){
                        response = JSON.parse( response );
                        gtag( 'event', 'add_to_cart', response );
                    })
                    .fail(function (){
                        console.log( "Request Failed!" );
                    });
            } );
        }
    });

})(jQuery, wp);
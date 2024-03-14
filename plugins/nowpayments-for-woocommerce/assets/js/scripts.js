( function( $ ) {
    $( document ).ready( function() {
        $( '#woocommerce_nowpayments_single_product_icon, #woocommerce_nowpayments_products_icons' ).on( 'click', function( e ) {
            e.preventDefault();

            window.open( 'https://coderpress.co/products/nowpayments-for-woocommerce/', '_blank' )
        } );
    } );
} )( jQuery );
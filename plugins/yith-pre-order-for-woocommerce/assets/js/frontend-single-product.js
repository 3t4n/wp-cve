jQuery( function( $ ) {
    var button = $( '.single_add_to_cart_button');
    var add_to_cart_text = button.html();
    $( 'form.variations_form' )
        .on( 'show_variation', function( event, variation, purchasable ) {
            if ( 'yes' == variation.is_pre_order ) {
                // Show button label
                button.html( variation.pre_order_label );
            } else {
                button.html( add_to_cart_text );
            }
        } )
        .on( 'hide_variation', function( event ) {
            event.preventDefault();
            button.html( add_to_cart_text );
        } );

});
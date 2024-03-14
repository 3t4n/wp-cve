jQuery(function( $ ) {
    $( '#the-list' ).on( 'click', '.editinline', function() {

        var post_id = $( this ).closest( 'tr' ).prop( 'id' );

        post_id = post_id.replace( 'post-', '' );

        var $inline_data = $( '#wpla_inline_' + post_id );

        var amazon_price      = $inline_data.find( '.amazon_price' ).text(),
            listing_id      = $inline_data.find( '.amazon_listing_id' ).text();


        $( 'input[name="_amazon_price"]', '.inline-edit-row' ).val( amazon_price );

        $( "#wpla-fields" ).show();

        if ( listing_id == 0 ) {
            $( "#wpla-fields" ).hide();
        }

    });
});

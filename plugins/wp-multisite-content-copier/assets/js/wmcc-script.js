jQuery( document ).ready( function( $ ) {    
    var show_cc_data = {};
    show_cc_data.action = 'display_content_copier';
    var type = $( '#wmcc-content' ).attr( 'type' );
    var type_name = $( '#wmcc-content' ).attr( 'type-name' );
    var item_id = $( '#wmcc-content' ).attr( 'item-id' );
    if ( type && type_name && item_id ) {
        show_cc_data.type = type;
        show_cc_data.type_name = type_name;
        show_cc_data.item_id = item_id;
        $.post( wmcc_ajaxurl.ajaxurl, show_cc_data, function( response ) {
            $( '#wmcc-content' ).html( response );
        });
    }
    
    $( '#wmcc-content' ).on( 'click', '#wmcc-copy', function() {
        if ( $('#wmcc-content' ).hasClass( 'wmcc-loader' ) ) {
            // nothing
        } else {
            var sites = [];
            $( '#wmcc-sites input[type="checkbox"]' ).each( function() {
                if( $( this ).prop( 'checked' ) == true ){
                    sites.push( $( this ).val() );
                }
            });

            if ( sites.length !== 0 ) {
                $( '#wmcc-content' ).addClass( 'wmcc-loader' );
                var send_cc_data = {};
                send_cc_data.action = 'send_content_copier';
                send_cc_data.sites = sites;
                send_cc_data.type = $( '#wmcc-content' ).attr( 'type' );
                send_cc_data.type_name = $( '#wmcc-content' ).attr( 'type-name' );
                send_cc_data.item_id = $( '#wmcc-content' ).attr( 'item-id' );               
                if ( $( '#is-copy-media' ).is( ':checked' ) ) {
                    send_cc_data.copy_media = 1; 
                } else {
                    send_cc_data.copy_media = 0;
                }
                
                if ( $( '#is-copy-terms' ).is( ':checked' ) ) {
                    send_cc_data.copy_terms = 1; 
                } else {
                    send_cc_data.copy_terms = 0;
                }
                
                $.post( wmcc_ajaxurl.ajaxurl, send_cc_data, function( response ) {                     
                    $( '#wmcc-content' ).removeClass( 'wmcc-loader' );  
                    $( '#wmcc-message' ).html('<div class="notice notice-success is-dismissible"><p>Item successfully copied or updated.</p></div>'); 
                    $( '#wmcc-message .notice' ).delay(5000).fadeOut(400);
                });
            }
        }
    });
});
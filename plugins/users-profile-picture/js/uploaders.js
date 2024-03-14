/**
 * Theme Options Scripts
 */
 
jQuery( document ).ready( function() {
 
    /* WP Media Uploader */
    var _dew_media = true;
    var _orig_send_attachment = wp.media.editor.send.attachment;
 
    jQuery( '.dew-image' ).click( function() {
 
        var button = jQuery( this ),
                textbox_id = jQuery( this ).attr( 'data-id' ),
                image_id = jQuery( this ).attr( 'data-src' ),
                _dew_media = true;
 
        wp.media.editor.send.attachment = function( props, attachment ) {
 
            if ( _dew_media && ( attachment.type === 'image' ) ) {
                if ( image_id.indexOf( "," ) !== -1 ) {
                    image_id = image_id.split( "," );
                    $image_ids = '';
                    jQuery.each( image_id, function( key, value ) {
                        if ( $image_ids )
                            $image_ids = $image_ids + ',#' + value;
                        else
                            $image_ids = '#' + value;
                    } );
 
                    var current_element = jQuery( $image_ids );
                } else {
                    var current_element = jQuery( '#' + image_id );
                }
 
                jQuery( '#' + textbox_id ).val( attachment.url );
                                console.log(textbox_id)
                current_element.attr( 'src', attachment.url ).show();
            } else {
                alert( 'Please select a valid image file' );
                return false;
            }
        }
        wp.media.editor.open( button );
        return false;
    } );
} );
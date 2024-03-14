/**
 * Admin code for dismissing notifications.
 *
 */
(function( $ ) {
    'use strict';
    $( function() {
        $( '.wpla-notice' ).on( 'click', '.notice-dismiss', function( event, el ) {
            const $notice = $(this).parent('.notice.is-dismissible');
            const notice_hash = $notice.data('msg_id');

            // simple ping the dismiss URL with the message ID
            if ( notice_hash ) {
                $.get( wpla_i18n.ajax_url + "?action=wpla_dismiss_notice&id="+ notice_hash +"&_wpnonce="+ wpla_i18n.wpla_ajax_nonce );
            }

        });
    } );
})( jQuery );
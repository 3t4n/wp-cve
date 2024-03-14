jQuery( document ).ready(function() {
    jQuery('#spoiler-title').click(function() {
        if( jQuery('#spoiler-content').hasClass('closed') ) {
            jQuery('#spoiler-content').removeClass('closed');
            jQuery('#spoiler-content').addClass('open');
            jQuery('#spoiler-arrow').removeClass('down');
            jQuery('#spoiler-arrow').addClass('up');
        } else {
            jQuery('#spoiler-content').removeClass('open');
            jQuery('#spoiler-content').addClass('closed');
            jQuery('#spoiler-arrow').removeClass('up');
            jQuery('#spoiler-arrow').addClass('down');
        }
    });

    jQuery('#sendwp-enabled-checkbox').change(function() {
        if( jQuery('#sendwp-enabled-checkbox').is(':checked') ) {
            jQuery('#sendwp-enabled-status').html(sendwpAdmin.loading);
            var data = {
                action: 'sendwp_forwarding',
                sendwp_forwarding: 'enable',
                security: sendwpAdmin.ajaxNonce
            };
            jQuery.post( ajaxurl, data, function( response ){
                var response = JSON.parse( response );
                jQuery('#sendwp-enabled-status').html(sendwpAdmin.enabled);
            });
        } else {
            jQuery('#sendwp-enabled-status').html(sendwpAdmin.loading);
            var data = {
                action: 'sendwp_forwarding',
                sendwp_forwarding: 'disable',
                security: sendwpAdmin.ajaxNonce
            };
            jQuery.post( ajaxurl, data, function( response ){
                var response = JSON.parse( response );
                jQuery('#sendwp-enabled-status').html(sendwpAdmin.disabled);
            });
        }
    });
});
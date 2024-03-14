(function ($) {

    $(document).ready(function() {
        $('.close,.popup-overlay').click( function( event ) {
            event.preventDefault();
            oliverHideModal();
        });

        $('.oliver-skip-deactivate').click( function( event ) {
            event.preventDefault();
            jQuery.post(`${ oliver_pos_feedback.ajax_url }` ,{
                    'action'    : 'oliver_pos_deactivate_plugin',
                    'security'  : `${ oliver_pos_feedback.security }`,
                    'service'   : '1',
                },
                function( response ){
                    oliverHideModal();
                    window.location.href = './plugins.php';
                }
            );
        });
        //oliver pos visibility product code
        jQuery( '#oliver-pos-visibility' ).find( '.edit-oliver-pos-visibility' ).on( 'click', function() {
            if ( jQuery( '#oliver-pos-visibility-select' ).is( ':hidden' ) ) {
                jQuery( '#oliver-pos-visibility-select' ).slideDown( 'fast' );
                jQuery( this ).hide();
            }
            return false;
        });
        jQuery( '#oliver-pos-visibility' ).find( '.save-post-visibility' ).on( 'click', function() {
            jQuery( '#oliver-pos-visibility-select' ).slideUp( 'fast' );
            jQuery( '#oliver-pos-visibility' ).find( '.edit-oliver-pos-visibility' ).show();
            return false;
        });
        jQuery( '#oliver-pos-visibility' ).find( '.cancel-post-visibility' ).on( 'click', function() {
            jQuery( '#oliver-pos-visibility-select' ).slideUp( 'fast' );
            jQuery( '#oliver-pos-visibility' ).find( '.edit-oliver-pos-visibility' ).show();
            return false;
        });
    });

    $('#the-list').find('[data-slug="oliver-pos"] span.deactivate a').click( function( event ) {
        event.preventDefault();
        oliverShowModal();
    });

    function oliverShowModal() {
        jQuery(".popup-overlay, .popup-content").addClass("active");
        jQuery('.hs-form-iframe').contents().find('.hubspot-link__container').hide();
    }

    function oliverHideModal(reload = false) {
        jQuery(".popup-overlay, .popup-content").removeClass("active");
    }

})(jQuery)
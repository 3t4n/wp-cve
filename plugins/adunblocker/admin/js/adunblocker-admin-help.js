(function( $ ) {
    'use strict';

    var ajax_spinner = get_sppinner();
    var events = {
        '.clear-log': {
            'action'    : 'click',
            'callback'  : clear_log
        }
    };


    document.addEventListener('DOMContentLoaded', function() {
        refresh_debug_log();
        initEvents();
    });


    function initEvents() {
        console.log('initEvents');
        for(var selector in events) {
            $(selector).on(events[selector]['action'], events[selector]['callback']);

        }
    }

    function refresh_debug_log() {
        $.ajax( {
            url: ajaxurl,
            type: 'POST',
            dataType: 'text',
            cache: false,
            data: {
                action: 'daau_get_log',
                nonce: daau_app.nonces.get_log
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                alert( errorThrown );
            },
            success: function( data ) {
                $( '.debug-log-textarea' ).val( data );
            }
        } );
    }

    function clear_log() {
        $( '.ajax-spinner, .ajax-success-msg' ).remove();
        $( this ).after( ajax_spinner );
        $( '.debug-log-textarea' ).val( '' );
        $.ajax( {
            url: ajaxurl,
            type: 'POST',
            dataType: 'text',
            cache: false,
            data: {
                action: 'daau_clear_log',
                nonce: daau_app.nonces.clear_log
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                $( '.ajax-spinner' ).remove();
                alert( 'An error occurred when trying to clear the debug log. Please contact support.' );
            },
            success: function( data ) {
                $( '.ajax-spinner, .ajax-success-msg' ).remove();
                refresh_debug_log();
                $( '.clear-log' ).after( '<span class="ajax-success-msg">Cleared</span>' );
                $( '.ajax-success-msg' ).fadeOut( 2000, function() {
                    $( this ).remove();
                } );
            }
        } );
    }

    function get_sppinner() {
        var admin_url = ajaxurl.replace( '/admin-ajax.php', '' );
        var spinner_url = admin_url + '/images/spinner';

        if ( 2 < window.devicePixelRatio ) {
            spinner_url += '-2x';
        }
        spinner_url += '.gif';
        var ajax_spinner = '<img src="' + spinner_url + '" alt="" class="ajax-spinner general-spinner" />';

        return ajax_spinner;
    }

})( jQuery );
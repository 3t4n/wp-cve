jQuery( function( $ ) {
    $( document ).on( 'click', '#em_start_migration_process', function() {
        $( '#ep_event_migration_run_message' ).html( '' );
        $( '#ep_event_migration_run_message' ).addClass( 'spinner is-active' );
        $( '#em_start_migration_process' ).attr( 'disabled', 'disabled' );
        $( '#em_cancel_migration_process' ).attr( 'disabled', 'disabled' );

        let data = { 
            action: 'ep_eventprime_run_migration', 
            security: ep_admin_migration_settings.run_migration_nonce, 
        };
        $.ajax({
            type: 'POST', 
            url :  get_ajax_url(),
            data: data,
            success: function(data, textStatus, XMLHttpRequest) {
                $( '#ep_event_migration_run_message' ).removeClass( 'spinner is-active' );
                if( data.success ) {
                    $( '#ep_event_migration_run_message' ).addClass( 'ep-text-success' );
                } else{
                    $( '#ep_event_migration_run_message' ).addClass( 'ep-error-message' );
                }
                $( '#ep_event_migration_run_message' ).html( data.data.message );

                setTimeout( function() {
                    location.href = ep_admin_migration_settings.dashboard_url;
                }, 2000);
            }
        });
    });

    $( document ).on( 'click', '#em_cancel_migration_process', function() {
        $( '#ep_event_migration_run_message' ).html( '' );
        $( '#ep_event_migration_run_message' ).addClass( 'spinner is-active' );
        $( '#em_start_migration_process' ).attr( 'disabled', 'disabled' );
        $( '#em_cancel_migration_process' ).attr( 'disabled', 'disabled' );

        let data = { 
            action: 'ep_eventprime_cancel_migration', 
            security: ep_admin_migration_settings.run_migration_nonce, 
        };
        $.ajax({
            type: 'POST', 
            url :  get_ajax_url(),
            data: data,
            success: function(data, textStatus, XMLHttpRequest) {
                $( '#ep_event_migration_run_message' ).removeClass( 'spinner is-active' );
                $( '#ep_event_migration_run_message' ).addClass( 'ep-error-message' );
                $( '#ep_event_migration_run_message' ).html( data.data.message );
                setTimeout( function() {
                    location.href = ep_admin_migration_settings.plugin_page_url;
                }, 2000);
            }
        });
    });
});
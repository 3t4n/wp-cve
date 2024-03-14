(function( $ ) {
    'use strict';
    var googleDrive = {
        set: function() {
            $('input[name="wt_authenticate_google"]').on('click',function( e ) {
                e.preventDefault();
                googleDrive.authenticate(jQuery(this));
            });
            $('#wt_disconnect_googledrive').on('click',function( e ){
                e.preventDefault();
                googleDrive.disconnect( jQuery(this));
            });
        },
        authenticate: function( element ) {
            var formElement;
            var currentElement = element;
            formElement = currentElement.closest('form');
            formElement.find('.wt-migrator-notice').removeClass('wt-error wt-success').text('');
            formElement.find('.spinner').css({'visibility' : 'visible' });
            var client_id =  $('input[name="wt_google_client_id"]').val();
            var secret_key =  $('input[name="wt_google_client_secret"]').val();

            if( '' == client_id || '' == secret_key){
                formElement.find('.spinner').css({'visibility' : 'hidden' });
                formElement.find('.wt-migrator-notice').addClass('wt-error').text(wp_migration_duplicator_googledrive.error_messages.fields_missing );
                return false;
            } else {
                jQuery('#wt_mgdp_googledrive').submit();
            }

        },
        disconnect: function( element ) {
            var element = element; 
            element.parent().find('.spinner').css({'visibility' : 'visible' });
            var data = {
                'action'		:  'wp_mgdp_disconnect_googledrive',
                '_wpnonce'		: 	wp_migration_duplicator_googledrive.nonce
            };
            jQuery.ajax({
                url: wp_migration_duplicator_googledrive.ajax_url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    if( response.success === true ) {
                        location.reload(true);
                    }
                },
                error: function () {
                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_googledrive.error_messages.auth_error);
                }
            });
        },
        checkAuthentication: function() {
            var moduleBase = wp_migration_duplicator_googledrive.module_base;
            var tabElement = jQuery('.wt-mgdp-tab-container').find('[data-id="wt-'+moduleBase+'"]');
            var authenticateBar = tabElement.find('.wt-migrator-authenticate-bar');
            var disconnectBar = tabElement.find('.wt-migrator-disconnect-bar');
            var $class = "wt-migrator-disconnected";
            var data = {
                'action'		:  'wp_mgdp_check_googledrive_authentication',
                '_wpnonce'		: 	wp_migration_duplicator_googledrive.nonce
            };
            jQuery.ajax({
                url: wp_migration_duplicator_backups.ajax_url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    if( response.success === true ) {
                        $class = "wt-migrator-authenticated";
                        authenticateBar.addClass('wt-migrator-action-bar-hidden');
                        disconnectBar.removeClass('wt-migrator-action-bar-hidden');
                    }
                    else {
                        authenticateBar.removeClass('wt-migrator-action-bar-hidden');
                        disconnectBar.addClass('wt-migrator-action-bar-hidden');
                    }
                    tabElement.addClass($class);
                },
                error: function () {
                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_googledrive.error_messages.auth_error);
                }
            });
        }  
    }
    googleDrive.set();
    // googleDrive.checkAuthentication();
	
})( jQuery );
(function( $ ) {
    'use strict';
    var currentElement;
    var s3Bucket = {
        set: function() {
            $('input[name="wt_authenticate_s3bucket"]').on('click',function( e ) {
                e.preventDefault();
                s3Bucket.authenticate( jQuery(this) );
            });
            $('#wt_disconnect_s3bucket').on('click',function( e ){
                e.preventDefault();
                s3Bucket.disconnect( jQuery(this));
            });
            
        },
        authenticate: function( element ) { 
            var formElement;
            var currentElement = element;
            formElement = currentElement.closest('form');
            formElement.find('.wt-migrator-notice').removeClass('wt-error wt-success').text('');
            formElement.find('.spinner').css({'visibility' : 'visible' });
            var data = {
                'action'		:  'wp_mgdp_authenticate_s3bucket',
                'access_key'    :   $('input[name="wt_s3bucket_access_key"]').val(),
                'secret_key'    :   $('input[name="wt_s3bucket_secret_key"]').val(),
                's3_location'   :   $('input[name="wt_s3bucket_location"]').val(),
                '_wpnonce'		: 	wp_migration_duplicator_s3bucket.nonce
            };
            jQuery.ajax({
                url: wp_migration_duplicator_s3bucket.ajax_url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    var $class = 'wt-error';
                    var reload = false;
                    if (response.success === true) {
                        reload = true;
                        $class = 'wt-success';
                        formElement.find('.wt-migrator-authenticate-bar').addClass('wt-migrator-action-bar-hidden');
                        formElement.find('.wt-migrator-disconnect-bar').removeClass('wt-migrator-action-bar-hidden');
                    }
                    formElement = currentElement.closest('form');
                    formElement.find('.spinner').css({ 'visibility': 'hidden' });
                    formElement.find('.wt-migrator-notice').addClass($class).text(response.data);
                    if( reload === true ) {
                        location.reload();
                    }
                },
                error: function () {
                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_s3bucket.error_messages.auth_error);
                }
            });
        },
        disconnect: function( element ) {
            var element = element; 
            element.parent().find('.spinner').css({'visibility' : 'visible' });
            var data = {
                'action'		:  'wp_mgdp_disconnect_s3bucket',
                '_wpnonce'		: 	wp_migration_duplicator_s3bucket.nonce
            };
            jQuery.ajax({
                url: wp_migration_duplicator_s3bucket.ajax_url,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    if( response.success === true ) {
                        location.reload(true);
                    }
                },
                error: function () {
                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_s3bucket.error_messages.auth_error);
                }
            });
        },
        checkAuthentication: function() {
            var moduleBase = wp_migration_duplicator_s3bucket.module_base;
            var tabElement = jQuery('.wt-mgdp-tab-container').find('[data-id="wt-s3bucket"]');
            var authenticateBar = tabElement.find('.wt-migrator-authenticate-bar');
            var disconnectBar = tabElement.find('.wt-migrator-disconnect-bar');
            var $class = "wt-migrator-disconnected";
            var data = {
                'action'		:  'wp_mgdp_check_s3bucket_authentication',
                '_wpnonce'		: 	wp_migration_duplicator_s3bucket.nonce
            };
            jQuery.ajax({
                url: wp_migration_duplicator_s3bucket.ajax_url,
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
                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_s3bucket.error_messages.auth_error);
                }
            });
        }  

    }
    s3Bucket.set();
    // s3Bucket.checkAuthentication();
})( jQuery );

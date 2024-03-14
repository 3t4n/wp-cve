jQuery(function() {

    function ldrp_after_update_action( status ) {
        jQuery('#wrld-plugin-update-notice-spinner.spinner').removeClass('is-active');
        if ('yes'==jQuery('#wrld-plugin-update-notice').data('on_plugins_page')) {
            setTimeout(function(){location.reload();}, 500);
        } else {
            setTimeout(function(){window.location.href = jQuery('#wrld-plugin-update-notice').data('pluginspage');}, 500);
        }
        
    }

    function ldrp_after_update_error( status ) {
        jQuery('#wrld-plugin-update-notice-spinner.spinner').removeClass('is-active');
        jQuery('#wrld-plugin-update-notice').text('');
        jQuery('#wrld-plugin-update-notice-status').text(' ['+jQuery('#wrld-plugin-update-notice-spinner').data('failed_message') + ']');
        jQuery('#wrld-plugin-update-notice-status').trigger('focus');
    }

    jQuery('#wrld-plugin-update-notice').on('click', function() {
        let $this           = jQuery(this);
        let plugin_basename = $this.data('basename');

        jQuery('#wrld-plugin-update-notice-spinner.spinner').addClass('is-active');
        jQuery('#wrld-plugin-update-notice').text(jQuery('#wrld-plugin-update-notice-spinner').data('updating'));
        if ('yes'==$this.data('on_plugins_page')) {
            wp.updates.updatePlugin( {
                plugin:plugin_basename,
                slug:'learndash-reports-pro',
                success:ldrp_after_update_action,
                error:ldrp_after_update_error
            });
        } else {
            wp.updates.ajax('update-plugin', 
            {
                plugin:plugin_basename, 
                slug:'learndash-reports-pro', 
                success:ldrp_after_update_action, 
                error:ldrp_after_update_error
            });
        }
    });
});


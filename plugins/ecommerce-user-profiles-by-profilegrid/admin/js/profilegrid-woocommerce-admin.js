(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

function pg_update_woo_popup_setting(redirect)
{
    var data = {'action': 'pg_update_woo_popup_setting'};
    jQuery.post(pm_ajax_object.ajax_url, data, function(response) {
        if(redirect=='yes')
        {
            window.location.href = response;
        }
    });
}

function pg_install_core_plugin(redirect)
{
    jQuery('.pg-core-installation-btn').hide();
    jQuery('#pm_woocommerce_activation_loader').show();
    var data ={'action':'pg_install_profilegrid'};
    jQuery.post(pm_ajax_object.ajax_url, data, function(response) {
        jQuery('#pg_activation_response').html(response);
        if(redirect)
        {
            window.location.href = redirect;
        }
    });
}


    jQuery(document).ready(function () {
        jQuery('.pg-modal-box-close, .pg-modal-box-overlay').click(function () {
            setTimeout(function () {
                //jQuery(this).parents('.rm-modal-view').hide();
                jQuery('.pg-modal-box-main').hide();
            }, 400);
                    });

        
          
       
    });
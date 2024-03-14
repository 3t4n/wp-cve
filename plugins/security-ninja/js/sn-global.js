/* globals jQuery:true, ajaxurl:true, wf_sn:true */
/* Functions are loaded on entire WP admin */


/**
 * Stores opt-in or opt-out choice for user.
 *
 * @author	Lars Koudal
 * @since	v0.0.1
 * @version	v1.0.0	Tuesday, March 22nd, 2022.
 * @global
 * @param	mixed	element	
 * @return	void
 */
function wfsn_freemius_opt_in(element) {
  var nonce = jQuery('#wfsn-freemius-opt-nonce').val();
  var choice = jQuery(element).data('opt');
  
  jQuery.ajax({
    type: 'POST',
    url: ajaxurl,
    async: true,
    data: {
      action: 'wfsn_freemius_opt_in',
      opt_nonce: nonce,
      choice: choice
    },
    success: function (data) {
      location.reload();
    },
    error: function (xhr, textStatus, error) {
    }
  });
  
}




/**
 * Enables plugin background updates
 *
 * @author	Lars Koudal
 * @since	v0.0.1
 * @version	v1.0.0	Friday, March 18th, 2022.	
 * @version	v1.0.1	Tuesday, March 22nd, 2022.
 * @global
 * @return	void
 */
function wfsn_enable_background_updates(e) {
  jQuery(this).attr('disabled');
  jQuery('#wfsn-enable-background-updates a.button').addClass('disabled');
  jQuery('#wfsn-enable-background-updates .dismiss-this').addClass('disabled');
  jQuery('.wrap').prepend('<div class="secning-loading-popup"><p>Please wait<span class="spinner is-active"></span></p></div>');
  jQuery(".secnin-loading-popup").toggle();
  var nonce = jQuery('#wfsn-enable-background-updates-nonce').val();
  jQuery.ajax({
    type: 'POST',
    url: ajaxurl,
    data: {
      action: 'wfsn_enable_background_updates',
      nonce: nonce
    },
    success: function (data) {
      location.reload();
    },
    error: function (xhr, textStatus, error) {
      console.log(xhr.statusText);
      console.log(textStatus);
      console.log(error);
    }
  });
}



/* Resets Freemius activation via ajax call. */
jQuery(document).on('click', '.secninfs-reset-activation', function (e) {
  
  e.preventDefault();
  
  jQuery('.wrap').prepend('<div class="secning-loading-popup"><p>Please wait<span class="spinner is-active"></span></p></div>');
  
  jQuery(".secning-loading-popup").toggle();
  var nonce = jQuery('#wfsn-secninfs-reset-activation-nonce').val();

  jQuery.ajax({
    url: ajaxurl,
    type: 'POST',
    data: {
      action      :'wfsn_freemius_reset_activation',
      _ajax_nonce : nonce
    },
    success: function( response ) {
      window.location.reload();
    },
    error: function( response ) {
      window.location.reload();
    }
  });
  
});



/* Loads the latest events (if any) in the sidebar */
jQuery(document).ready(function($) {







  if (jQuery('#sn_sidebar_latest').length > 0) {
    // Add a spinner to the target DIV
    jQuery('#sn_sidebar_latest').html('<div class="spinner" style="visibility: visible;"></div>');

    jQuery.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'sn_sidebar_latest_events',
        nonce: wf_sn.nonce_latest_events
      },
      success: function(response) {
        if (response.success) {
          jQuery('#sn_sidebar_latest').html(response.data);
        } else {
          if (typeof console !== 'undefined') {
            console.error('Error:', response.data);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (typeof console !== 'undefined') {
          console.error('AJAX Error:', textStatus, errorThrown);
        }
      }
    });
  }



});


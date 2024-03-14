jQuery( document ).ready( function( $ ) {

  jQuery(document).on( 'click', '.ewd-uwcf-helper-install-notice .notice-dismiss', function( event ) {
    var data = jQuery.param({
      action: 'ewd_uwcf_hide_helper_notice',
      nonce: ewd_uwcf_helper_notice.nonce
    });

    jQuery.post( ajaxurl, data, function() {} );
  });
});
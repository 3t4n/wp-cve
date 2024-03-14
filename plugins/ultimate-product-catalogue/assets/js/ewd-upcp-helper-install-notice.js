jQuery( document ).ready( function( $ ) {

  jQuery(document).on( 'click', '.ewd-upcp-helper-install-notice .notice-dismiss', function( event ) {
    var data = jQuery.param({
      action: 'ewd_upcp_hide_helper_notice',
      nonce: ewd_upcp_helper_notice.nonce
    });

    jQuery.post( ajaxurl, data, function() {} );
  });
});
jQuery(document).ready(function ($) {

    $( document ).on( 'click', '.notice-upgrade-cf7pp', function () {
    
        var type = $( this ).data( 'notice' );
        // Make an AJAX call
        // Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax( ajaxurl,
          {
            type: 'POST',
            data: {
              action: 'cf7_popups_ajax_notice_handler',
              type: type,
              security: cf7_popups_admin.ajax_nonce,
            }
          } );
      } );

});
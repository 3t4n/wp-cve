jQuery(function($) {
  $( document ).on( 'click', '.notice-wsc-class .notice-dismiss', function () {
      var type = $( this ).closest( '.notice-wsc-class' ).data( 'notice' );
      $.ajax( ajaxurl,
        {
          type: 'POST',
          data: {
            action: 'dismissed_wsc_notice_handler',
          }
        } );
    } );
});
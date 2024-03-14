(function($){

    var SurrorNotices = {
        init: function() {
			$( document ).on( 'click', '.s-notice-close', SurrorNotices._close );
        },

        _close: function( event ) {
            event.preventDefault();
            let notice = $( this ).parents( '.s-notice' );
            let confirm_message = notice.data( 'confirm-message' ) || '';

            if( confirm_message ) {
                if( confirm( confirm_message ) ) {
                    SurrorNotices._closeConfirm( notice );
                }
            } else {
                SurrorNotices._closeConfirm( notice );
            }
        },

        _closeConfirm: function( notice ) {
            notice.slideUp( 'slow', function() {
                $.ajax({
                    url: surror_notices.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'surror_notices_dismiss',
                        notice_id: notice.data( 'notice-id' ),
                        db: notice.data( 'db' ),
                        nonce: surror_notices.nonce,
                    },
                });
            } );
        }
    };

    /**
	 * Initialize SurrorNotices
	 */
	$(function(){
		SurrorNotices.init();
	});

})(jQuery);

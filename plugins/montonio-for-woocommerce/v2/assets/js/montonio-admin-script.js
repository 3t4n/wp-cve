(function($) {
	'use strict'; 

    $(document).on('click', '.montonio-reset-email-tracking-code-text', function(e) {
        e.preventDefault();

        $('#montonio_email_tracking_code_text').val('Track your shipment:');
    });

})(jQuery);
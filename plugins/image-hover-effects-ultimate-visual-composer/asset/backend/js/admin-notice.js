
jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".oxi-flip-support-reviews", function (e) {
        e.preventDefault();

        $.ajax({
            url: oxilab_flip_notice_dissmiss.ajaxurl,
            type: 'post',
            data: {
                action: 'oxilab_flip_notice_dissmiss',
                _wpnonce: oxilab_flip_notice_dissmiss.nonce,
                notice: $(this).attr('sup-data'),
            },
            success: function (response) {
                console.log(response);
                $('.oxilab-flipbox-review-notice').remove();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
    });
})(jQuery);

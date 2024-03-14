
jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".oxi-flip-admin-recommended-dismiss", function (e) {
        e.preventDefault();
        $.ajax({
            url: oxi_flip_admin_recommended.ajaxurl,
            type: 'post',
            data: {
                action: 'oxi_flip_admin_recommended',
                _wpnonce: oxi_flip_admin_recommended.nonce,
                notice: $(this).attr('sup-data'),
            },
            success: function (response) {
                console.log(response);
                $('.oxi-addons-admin-notifications').remove();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
        return false;
    });
})(jQuery);

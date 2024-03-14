// Make notices dismissible
( function( $, window, undefined ) {
    function makeNSANoticesDismissible() {
        $('div.nsa_notification.nsa-dismissible').each(function () {
            var
                $el = $(this),
                $button = $(this).find('.dismiss_nsa_notice');


            // Ensure plain text
            $button.on('click.wp-dismiss-notice', function (event) {
                event.preventDefault();
                $el.fadeTo(100, 0, function () {
                    $el.slideUp(100, function () {
                        $el.remove();
                    });
                });

                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'nsa_' + jQuery(this).parent().attr("data-plugin_id") + '_dismiss_notification',
                        pluginid: jQuery(this).parent().attr("data-plugin_id"),
                        notificationid: jQuery(this).parent().attr("data-notification_id"),
                    }
                })

            });

            $el.append($button);
        });
    }

    $('div.nsa_notification').not('.inline, .below-h2').insertAfter($('.wrap h1, .wrap h2').first());

    makeNSANoticesDismissible();
}(jQuery, window));
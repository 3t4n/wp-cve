jQuery(document).ready(function() {
    if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i) || "1" === afsb.ismobile) {
        jQuery(".aspexi_facebook_button").click(function() {
            if(jQuery('.aspexifbsidebox .aspexi_facebook_iframe').hasClass('active')) {
                var that = jQuery('.aspexifbsidebox .aspexi_facebook_iframe'),
                    afbbox = jQuery('.aspexifbsidebox .aspexi_facebook_iframe');

                if (aspexi.browser.msie && parseInt(aspexi.browser.version) < 8) {
                    jQuery('.aspexifbsidebox .aspexi_facebook_iframe').hide();
                    jQuery('.aspexifbsidebox').find('.arrow').hide();
                    that.removeClass('active');
                    return false;
                }
                jQuery('.aspexifbsidebox').find('.arrow').stop(false, true).fadeOut(300);
                afbbox.stop(false, true).animate({'width': '0', 'opacity': '0'}, 300, function() {
                    that.removeClass('active');
                });
            } else {
                var afbbox = jQuery('.aspexifbsidebox .aspexi_facebook_iframe');
                afbbox.data('width', afsb.width);

                if (aspexi.browser.msie && parseInt(aspexi.browser.version) < 8) {
                    afbbox.show().width(afsb.width);
                    afbbox.prev().show();
                    jQuery('.aspexifbsidebox .aspexi_facebook_iframe').addClass('active');
                    return false;
                }
                jQuery('.aspexifbsidebox .aspexi_facebook_iframe').addClass('active');
                jQuery('.aspexifbsidebox .aspexi_facebook_iframe').stop(false, true).fadeIn(300);
                if (afbbox.is(':animated')) {
                    afbbox.stop().css('opacity', '1').width(afsb.width);
                } else {
                    afbbox.stop(false, true).animate({'width': afsb.width, 'opacity': 1}, 300);
                }
            }
        });
    } else {
        jQuery(".aspexifbsidebox .aspexi_facebook_button, .aspexifbsidebox .aspexi_facebook_iframe").bind('mouseover', function() {
            var afbbox = jQuery('.aspexifbsidebox .aspexi_facebook_iframe');
            afbbox.data('width', afsb.width);

            if (aspexi.browser.msie && parseInt(aspexi.browser.version) < 8) {
                afbbox.show().width(afsb.width);
                afbbox.prev().show();
                jQuery('.aspexi_facebook_iframe').addClass('active');
                return false;
            }
            jQuery('.aspexifbsidebox .aspexi_facebook_iframe').addClass('active');
            // jQuery('.aspexi_facebook_iframe').stop(false, true).fadeIn(300);
            if (afbbox.is(':animated')) {
                afbbox.stop().css('opacity', '1').width(afsb.width);
            } else {
                afbbox.stop(false, true).animate({'width': afsb.width, 'opacity': 1}, 300);
            }
        });

        jQuery(".aspexifbsidebox .aspexi_facebook_button, .aspexifbsidebox .aspexi_facebook_iframe").bind('mouseleave', function() {
            var that = jQuery('.aspexifbsidebox .aspexi_facebook_iframe'),
                afbbox = jQuery('.aspexifbsidebox .aspexi_facebook_iframe');

            if (aspexi.browser.msie && parseInt(aspexi.browser.version) < 8) {
                jQuery('.aspexifbsidebox .aspexi_facebook_iframe').hide();
                jQuery('.aspexifbsidebox').find('.arrow').hide();
                that.removeClass('active');
                return false;
            }
            jQuery('.aspexifbsidebox').find('.arrow').stop(false, true).fadeOut(300);
            afbbox.stop(false, true).animate({width: 0, opacity: 0}, 300, function() {
                that.removeClass('active');
            });
        });
    }
    var aspexi = aspexi || {};
    aspexi.browser = {};
    (function () {
        aspexi.browser.msie = false;
        aspexi.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            aspexi.browser.msie = true;
            aspexi.browser.version = RegExp.$1;
        }
    })();
});
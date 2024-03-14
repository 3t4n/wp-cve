jQuery(document).ready(function() {
    jQuery('tr[data-plugin="' + iowd_deactivation.basename + '"] span.deactivate a').on('click', function() {
        jQuery('.iowd-deactivate-popup').appendTo('body').addClass('open');
        return false;
    });
    jQuery('.iowd-button-cancel, .iowd-close-img').on('click', function() {
        jQuery('.iowd-deactivate-popup').removeClass('open');
        return false;
    });
    jQuery('.iowd_disconnect_from_page').on('click',function() {
        jQuery('.iowd-deactivate-popup').appendTo('body').addClass('open');
        return false;
    });
});
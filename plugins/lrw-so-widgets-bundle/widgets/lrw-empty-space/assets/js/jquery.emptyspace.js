/**
 * Empty Space
 * Init Empty Space
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {
    function checkWidth() {
        $('.lrw-empty-space').each(function() {
            var $$ = $(this),
                windowsize = $(window).width(),
                height = $$.data('height'),
                desktop = $$.data('break-desktop'),
                d_height = parseInt($$.data('height-desktop')),
                tablet = $$.data('break-tablet'),
                t_height = parseInt($$.data('height-tablet')),
                phone = $$.data('break-phone'),
                p_height = parseInt($$.data('height-phone'));

            $$.css({
                'height': height + 'px',
                'min-height': height + 'px'
            });

            if (window.matchMedia('(max-width:' + desktop + 'px)').matches) {
                $$.css({ 'height': d_height + 'px', 'min-height': d_height + 'px' });
            }

            if (window.matchMedia('(max-width:' + tablet + 'px)').matches) {
                $$.css({ 'height': t_height + 'px', 'min-height': t_height + 'px' });
            }

            if (window.matchMedia('(max-width:' + phone + 'px)').matches) {
                $$.css({ 'height': p_height + 'px', 'min-height': p_height + 'px' });
            }
        })
    }

    // Execute on load
    checkWidth();

    // Bind event listener
    $(window).resize(checkWidth);
});

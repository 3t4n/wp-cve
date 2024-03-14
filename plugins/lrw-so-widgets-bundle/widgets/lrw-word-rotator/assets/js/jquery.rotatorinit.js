/**
 * Word Rotator
 * Init Word Rotator
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {
    $('.lrw-word-rotator').each( function() {
        var settings    = $(this).data('settings'),
            animation   = settings.animation,
            speed       = settings.speed,
            trigger     = ( settings.trigger ? !0 : !1 ),
            parameters  = {
                animation: animation,
                speed: speed
            },
            element     = $(this).find('.lrw-rotating');

        if ( 0 == trigger ) {
            element.Morphext( parameters );
        } else {
            $(this).waypoint(function(direction) {
                element.Morphext( parameters ).css({
                    display: 'initial'
                });
            }, {
              offset: 'bottom-in-view',
              triggerOnce: !0
            });
        }
    });
});
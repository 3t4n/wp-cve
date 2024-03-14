/**
 * Count Number
 * Init Count number
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {
    $('.lrw-counter').each( function() {
        var settings = $(this).data('settings'),
            start = $(this).data('start'),
            end = $(this).data('end'),
            trigger = ( settings.trigger ? !0 : !1 ),
            options = {
                useEasing : settings.easing,
                useGrouping : settings.group,
                separator : ( settings.separator ? settings.separator  : ',' ),
                decimal : ( settings.decimal ? settings.decimal  : '.' )
            },
            duration = ( settings.duration ) ? settings.duration : 2,
            numAnim = new CountUp(this, start, end, settings.decimals, duration, options );

        if ( 0 == trigger ) {
            numAnim.start();
        } else {
            $(this).waypoint(function(direction) {
                numAnim.start();
            }, {
              offset: 'bottom-in-view',
              triggerOnce: !0
            })
        }
    });
});
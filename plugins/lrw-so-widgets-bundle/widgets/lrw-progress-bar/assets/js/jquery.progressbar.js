/**
 * Progress bar
 * Init animate progress bar
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {
    $('.lrw-progress-bar').each( function() {

        var trigger = ( $(this).data('trigger') ? !0 : !1 ),
            bar = $(this).find('.lrw-progress-bar-area');

        if ( 0 == trigger ) {
            bar.each(function() {
                var perc = $(this).data('perc');
                $(this).animate({
                    width: perc + "%"
                }, 15 * perc)
            });
        } else {
            bar.waypoint(function(direction) {
                $(this.element).each(function(){
                    perc = $(this).data('perc');
                    $(this).animate({
                        width: perc + "%"
                    }, 15 * perc );
                });
                this.destroy();
            }, {
              offset: 'bottom-in-view',
              triggerOnce: !0
            })
        }
    });
});
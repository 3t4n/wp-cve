/**
 * Pie Chart
 * Init Pie Chart
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {
	$('.lrw-pie-chart').each( function() {
		var pie = $(this),
			unit = pie.data('unit'),
			settings = pie.data('settings'),
			trigger = ( settings.trigger ? !0 : !1 );

		function initpie() {
			pie.easyPieChart({
				easing: settings.easing,
				lineCap: settings.linecap,
				lineWidth: settings.linewidth,
				size: settings.size,
				animate: settings.animate,
				rotate: ( settings.rotate ? settings.rotate : 0 ),
				scaleLength: ( settings.scalelength ? settings.scalelength : 0 ),
				trackWidth: settings.trackwidth,
				onStep: function(from, to, percent) {
					$(this.el).find('.lrw-pie-percent').text(Math.round(percent) + ' ' + unit );
				}
	        }).find('.lrw-pie-wrapper').css({ width: settings.size }).find('.lrw-pie-percent').css({
	        	width: settings.size,
	        	height: settings.size,
	        	"line-height": settings.size + "px"
            });
		}

		if ( 0 == trigger ) {
			initpie();
        } else {
            pie.waypoint(function(direction) {
                initpie();
                this.destroy();
            }, {
              offset: 'bottom-in-view',
              triggerOnce: !0
            })
        }
	});
});

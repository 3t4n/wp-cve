/**
 * To Top Customizer enhancements for a better user experience.
 *
 * Contains handlers to make To Top Customizer preview reload changes asynchronously.
 */

(function($) {

	wp.customize('catchwebtools_to_top_options[icon_opacity]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup').css({
				'opacity': parseFloat(to / 100)
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[icon_type]', function(value) {
		value.bind(function(to) {
			var icon_style = wp.customize('catchwebtools_to_top_options[style]').get();
			var new_class;
			if( 'icon' == icon_style){
				new_class = 'dashicons' + ' ' + to;

				$('#cwt_to_top_scrollup').removeClass();
				$('#cwt_to_top_scrollup').addClass(new_class);
			}
			else if( 'genericon-icon' == icon_style){
				if (  'dashicons-arrow-up' == to ) {
					new_class = 'genericon genericon-uparrow';
				}
				else if ( 'dashicons-arrow-up-alt' == to ) {
					new_class = 'genericon genericon-next genericon-rotate-270';
				}
				else {
					new_class = 'genericon genericon-collapse';
				}

				$('#cwt_to_top_scrollup').removeClass();
				$('#cwt_to_top_scrollup').addClass(new_class);
			}
			else if( 'font-awesome-icon' == icon_style){
				if (  'dashicons-arrow-up' == to ) {
					new_class = 'fa fa-caret-up';
				}
				else if ( 'dashicons-arrow-up-alt' == to ) {
					new_class = 'fa fa-arrow-up';
				}
				else {
					new_class = 'fa fa-angle-up';
				}

				$('#cwt_to_top_scrollup').removeClass();
				$('#cwt_to_top_scrollup').addClass(new_class);
			}

		});
	});

	wp.customize('catchwebtools_to_top_options[icon_bg_color]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup').css({
				'background-color': to
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[icon_color]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup').css({
				'color': to
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[icon_size]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup').css({
				'font-size': to + 'px',
				'height': to + 'px',
				'width': to + 'px'
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[border_radius]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup').css({
				'border-radius': to + '%'
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[location]', function(value) {
		value.bind(function(to) {
			var margin_x = wp.customize('catchwebtools_to_top_options[margin_x]').get();
			var margin_y = wp.customize('catchwebtools_to_top_options[margin_y]').get();

			if ('bottom-right' === to) {
				$('#cwt_to_top_scrollup').css({
					'top'	: 'auto',
					'right': margin_x + 'px',
					'bottom': margin_y + 'px',
					'left': 'auto',
				});
			} else if ('bottom-left' === to) {
				$('#cwt_to_top_scrollup').css({
					'top'	: 'auto',
					'right': 'auto',
					'bottom': margin_y + 'px',
					'left': margin_x + 'px',
				});
			} else if ('top-right' === to) {
				$('#cwt_to_top_scrollup').css({
					'top'	: margin_y + 'px',
					'right': margin_x + 'px',
					'bottom': 'auto',
					'left': 'auto',
				});
			} else if ('top-left' === to) {
				$('#cwt_to_top_scrollup').css({
					'top': margin_y + 'px',
					'right': 'auto',
					'bottom':'auto' ,
					'left': margin_x + 'px',
				});
			}

		});
	});

	wp.customize('catchwebtools_to_top_options[margin_y]', function(value) {
		value.bind(function(to) {
			var location = wp.customize('catchwebtools_to_top_options[location]').get();
			offset = location.split('-');
			offset1 = offset[0];
			if ('top' === offset1) {
				$('#cwt_to_top_scrollup').css({
					'top': to + 'px'
				});
			} else if ('bottom' === offset1) {
				$('#cwt_to_top_scrollup').css({
					'bottom': to + 'px'
				});
			}

		});
	});

	wp.customize('catchwebtools_to_top_options[margin_x]', function(value) {
		value.bind(function(to) {
			var location = wp.customize('catchwebtools_to_top_options[location]').get();
			offset = location.split('-');
			offset2 = offset[1];
			if ('right' === offset2) {
				$('#cwt_to_top_scrollup').css({
					'right': to + 'px'
				});
			} else if ('left' === offset2) {
				$('#cwt_to_top_scrollup').css({
					'left': to + 'px'
				});
			}
		});
	});

	wp.customize('catchwebtools_to_top_options[image]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup img').attr({
				'src': to
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[image_width]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup').css({
				'width': to + 'px'
			});
		});
	});

	wp.customize('catchwebtools_to_top_options[image_alt]', function(value) {
		value.bind(function(to) {
			$('#cwt_to_top_scrollup img').attr({
				'alt': to
			});
		});
	});

})(jQuery);
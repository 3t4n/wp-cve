(function($) {

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ep-hotspots.default', function($scope) {

			$('.ep-map-item').each(function() {
				var tooltipActive = $(this).data('show-tooltip');

				if ( 'yes' !== tooltipActive ) {
					return;
				}

				$(this).tipso( {
					tooltipHover: true,
					speed : 200,
				});
			});

		});
	});
})( jQuery );
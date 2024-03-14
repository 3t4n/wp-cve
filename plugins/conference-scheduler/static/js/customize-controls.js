/**
 * Scripts within the customizer controls window.
 *
 * Contextually shows the date format custom control
 */

(function() {
	wp.customize.bind( 'ready', function() {

		wp.customize( 'conf_scheduler_options[day_format]', function( setting ) {
			wp.customize.control( 'conf_scheduler_options[day_format_custom]', function( control ) {
				var visibility = function() {
					if ( 'custom' === setting.get() ) {
						control.container.slideDown( 180 );
					} else {
						control.container.slideUp( 180 );
					}
				};

				visibility();
				setting.bind( visibility );
			});
		});
	});
})( jQuery );

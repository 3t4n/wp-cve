(function ($) {
    $(function () {

		/**
         * Scroll to Section.
         */
        window.mxmtzcRunClocks = window.mxmtzcRunClocks || {

			container: '.mx-clock-live-el',

			initClock: function(container) {

				const dataAtrs = container.data();

				container.canvasClock({
					time_zone: dataAtrs.time_zone ?? 'Europe/London',
					city_name: dataAtrs.city_name ?? 'London',
					date_format: dataAtrs.date_format ?? 24,
					digital_clock: Boolean(dataAtrs.digital_clock) ?? false,
					lang: dataAtrs.lang ?? 'en',
					lang_for_date: dataAtrs.lang_for_date ?? 'en',
					show_days: Boolean(dataAtrs.show_days) ?? false,
					showSecondHand:  Boolean(dataAtrs.showsecondhand) ?? true,
					arrow_type: dataAtrs.arrow_type ?? 'classical',
					super_simple: Boolean(dataAtrs.super_simple) ?? false,
					arrows_color: dataAtrs.arrow_type ?? 'unset'
				});

			},

			prepareContainers: function() {

				const _this = this

				$(this.container).each( function() {					
					_this.initClock($(this));
				} );

			},

			init: function() {

				this.prepareContainers();

			}
		};

		mxmtzcRunClocks.init();

	});
})(jQuery);
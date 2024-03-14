(function( $ ) {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		var getSliderDataObj = $('[data-side="front"]').data('params');

		$('div[id^="wppsgs-slider-"]').each(function () {

			var _this = $(this);
			var sliderID = _this.attr('id');
			new Splide( '#'+sliderID, {
				width      : getSliderDataObj.width + getSliderDataObj.unit,
				height     : getSliderDataObj.height + getSliderDataObj.unit,
				perPage    : getSliderDataObj.perPage,
				breakpoints: {
					640: {
						perPage: getSliderDataObj.perPage,
					},
				},
				gap: Number(getSliderDataObj.gap),
				type: getSliderDataObj.type,
				speed: getSliderDataObj.speed,
				perMove: getSliderDataObj.perMove,
				arrows: getSliderDataObj.arrows,
				pagination: getSliderDataObj.pagination,
				autoplay: getSliderDataObj.autoplay,
				interval: getSliderDataObj.interval,
				pauseOnHover: getSliderDataObj.pauseOnHover,
				lazyLoad: getSliderDataObj.lazyLoad,
			}).mount();
		});
	} );

})( jQuery );

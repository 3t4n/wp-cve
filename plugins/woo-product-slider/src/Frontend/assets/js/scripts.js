jQuery(document).ready(function ($) {

	$('.wps-product-section').each(function (index) {
		var custom_id = $(this).attr('id'),
			layout_type = $('#' + custom_id).data('layout'),
			sliderData = $('#' + custom_id).data('swiper');

		if (layout_type != 'grid' && custom_id != '' && !$('#' + custom_id).hasClass('swiper-initialized')) {
			var wspSwiper = new Swiper('#' + custom_id, {
				speed: sliderData.speed,
				slidesPerView: sliderData.slidesPerView.mobile,
				loop: sliderData.infinite,
				spaceBetween: sliderData.spaceBetween,
				autoplay: sliderData.autoplay ? ({ delay: sliderData.autoplaySpeed, disableOnInteraction: false, }) : false,
				allowTouchMove: sliderData.swipe,
				simulateTouch: sliderData.draggable,
				freeMode: sliderData.freeMode,
				mousewheel: sliderData.mousewheel,
				autoHeight: sliderData.adaptiveHeight,
				keyboard: {
					enabled: sliderData.carousel_accessibility,
				},
				grabCursor: true,
				fadeEffect: { crossFade: true },
				updateOnWindowResize: true,
				// Responsive breakpoints
				breakpoints: {
					// when window width is >= 576px
					576: {
						slidesPerView: sliderData.slidesPerView.tablet,
					},
					// when window width is >= 992px
					992: {
						slidesPerView: sliderData.slidesPerView.desktop,
					},
					// when window width is >= 1200px
					1200: {
						slidesPerView: sliderData.slidesPerView.lg_desktop,
					}
				},
				// If we need pagination.
				pagination: {
					el: '#' + custom_id + ' .wpsp-pagination-dot',
					clickable: true,
				},
				// Navigation arrows.
				navigation: {
					nextEl: '#' + custom_id + ' .wpsp-nav.swiper-button-next',
					prevEl: '#' + custom_id + ' .wpsp-nav.swiper-button-prev',
				},

			});
			// On hover stop.
			if (sliderData.pauseOnHover && sliderData.autoplay) {
				$('#' + custom_id).on({
					mouseenter: function () {
						wspSwiper.autoplay.stop();
					},
					mouseleave: function () {
						wspSwiper.autoplay.start();
					}
				});
			}
		}
	});

	/**
	 * Preloader.
	 */
	$('body').find('.wps-product-section').each(function () {
		var _this = $(this),
			custom_id = $(this).attr('id'),
			preloader = _this.data('preloader');
		if ('1' == preloader) {
			var parents_class = $('#' + custom_id).parent('.wps-slider-section'),
				parents_siblings_id = parents_class.find('.wps-preloader').attr('id');
			$(document).ready(function () {
				$('#' + parents_siblings_id).animate({ opacity: 1 }, 600).hide();
				$('#' + custom_id).animate({ opacity: 1 }, 600)
			})
		}
	})
	// Add class for gutenberg block.
	$('.wps-slider-section').addClass('wps-slider-section-loaded');

});

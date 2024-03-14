function miga_category_slider_init() {
	const container = document.querySelector(".miga_category_slider__container");
	var spv = container.getAttribute("data-spv") || 4;
	var spvt = container.getAttribute("data-spvt") || 2;
	var spvp = container.getAttribute("data-spvp") || 1;
	var loop = container.getAttribute("data-loop") || false;
	var autoplayEnabled = container.getAttribute("data-autoplay") || false;
	var autoplayDelay = container.getAttribute("data-autoplay-delay") || 2000;
	var speed = container.getAttribute("data-speed") || 300;
	var spaceBetween = container.getAttribute("data-spacebetween") || 30;
	var autoplay = false;
	if (autoplayEnabled == 1 && autoplayDelay > 0) {
		autoplay = {
			delay: autoplayDelay,
			disableOnInteraction: false,
			pauseOnMouseEnter: true,
		}
	}
	const swiper = new Swiper('.miga_category_slider__container.swiper', {
		slidesPerView: spvp,
		spaceBetween: parseInt(spaceBetween),
		loop: parseInt(loop),
		autoplay: autoplay,
		speed: parseInt(speed),
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints: {
			767: {
				slidesPerView: spvt,
				spaceBetween: parseInt(spaceBetween)
			},
			1024: {
				slidesPerView: spv,
				spaceBetween: parseInt(spaceBetween)
			}
		}
	});

	if (autoplayEnabled == 1 && autoplayDelay > 0) {
		jQuery(".miga_category_slider__container").hover(function() {
			(this).swiper.autoplay.stop();
		}, function() {
			(this).swiper.autoplay.start();
		});
	}
}

jQuery(document).ready(function() {
	if (document.querySelectorAll(".miga_category_slider__container").length > 0) {
		// just start it when there is one
		miga_category_slider_init();
	}
});

(function($) {
	  var slickLoaded = setTimeout(function(){
		$(".slick-carousel2, .slider-nav, .dp-slick-carousel, .slick-carousel-nonav, .directorypress-listing-gallery").css({"height": "100%", "opacity": "1", "visibility": "visible"});
		$(".directorypress-single-listing-logo-wrap .price").css({"opacity": "1"});
		$('.ytp-button svg').remove();
		$('.ytp-button').html('<span class="new-video-button"><i class="fas fa-play"></i></span>');
	  }, 700);
	$(function() {
		directorypress_slik_init();
	});
	
	window.directorypress_slik_init = function () {
		$('.dp-slick-carousel').each( function() {
			
			var $SlickCarousel = $(this);	
			$SlickCarousel.not('.slick-initialized').slick({   
				dots: false,
				infinite: $SlickCarousel.data("loop"),
				slidesToShow: $SlickCarousel.data("items"),
				slidesToScroll: $SlickCarousel.data("slide-to-scroll"),
				speed: $SlickCarousel.data("slide-speed"),
				useTransform: true,
				autoplay: $SlickCarousel.data("autoplay"),
				autoplaySpeed: $SlickCarousel.data("autoplay-speed"),
				centerMode: $SlickCarousel.data("center"),
				centerPadding: $SlickCarousel.data("center-padding") + "%",
				//variableWidth: true,
				lazyLoad: 'progressive',
				arrows: $SlickCarousel.data("arrow"),
				rtl: directorypress_js_instance.is_rtl,
				//prevArrow: "<i class='listing-pre fas fa-angle-left'></i>",
				//nextArrow: "<i class='listing-next fas fa-angle-right'></i>",
				prevArrow: '<i class="listing-pre ' + $SlickCarousel.data("prev-arrow") + '" style="position:'+ $SlickCarousel.data("arrow-postion") +'; z-index:10;"></i>',
				nextArrow: '<i class="listing-next ' + $SlickCarousel.data("next-arrow") + '" style="position:'+ $SlickCarousel.data("arrow-postion") +'; z-index:10;"></i>',
				draggable: true,
				vertical: false,
				responsive: [
					{
						breakpoint: 858,
						settings: {
							slidesToShow: $SlickCarousel.data("items-tablet"),
						}
					},
					{
						breakpoint: 767,
						settings: {
							slidesToShow: $SlickCarousel.data("items-mobile"),
						}
					}
				]
			});
		});
	}
	
	$('.slick-carousel2').not('.slick-initialized').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: true,
	  fade: true,
	  rtl: directorypress_js_instance.is_rtl,
	  asNavFor: '.slider-nav',
	  lazyLoad: 'progressive',
	  
	});
	$('.slider-nav').not('.slick-initialized').slick({
		
	  slidesToShow: 5,
	  slidesToScroll: 1,
	  asNavFor: '.slick-carousel2',
	  dots: false,
	  arrows: false,
	  centerMode: false,
	  focusOnSelect: true,
	  lazyLoad: 'progressive',
	  lazyLoaded: slickLoaded,
	  rtl: directorypress_js_instance.is_rtl,
	  responsive: [
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 300,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});
	$('.slick-carousel-nonav').not('.slick-initialized').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: true,
	  fade: true,
	  rtl: directorypress_js_instance.is_rtl,
	  lazyLoad: 'progressive',
	  lazyLoaded: slickLoaded,
	  
	});
	// gallary
	$('.directorypress-listing-gallery').not('.slick-initialized').slick({
		
		slidesToShow: 4,
		slidesToScroll: 1,
		arrows: true,
		lazyLoad: 'progressive',
		lazyLoaded: slickLoaded,
		rtl: directorypress_js_instance.is_rtl,
		prevArrow: "<i class='listing-pre fas fa-angle-left'></i>",
		nextArrow: "<i class='listing-next fas fa-angle-right'></i>",
		responsive: [
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 300,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});
	
	// video gallery
	
	$('.directorypress-listing-video-gallery').not('.slick-initialized').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: true,
	  fade: true,
	  rtl: directorypress_js_instance.is_rtl,
	  asNavFor: '.video-slider-nav',
	  
	});
	$('.video-slider-nav').not('.slick-initialized').slick({
		
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  asNavFor: '.directorypress-listing-video-gallery',
	  dots: false,
	  arrows: false,
	  centerMode: false,
	  focusOnSelect: true,
	  rtl: directorypress_js_instance.is_rtl,
	  responsive: [
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 300,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});
} )( jQuery )

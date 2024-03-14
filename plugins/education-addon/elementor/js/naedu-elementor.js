/*
Template Name: Education Addon
Author: NicheAddon
Version: 1.0.0
Email: support@nicheaddon.com
*/

(function($){
'use strict';
/*----- ELEMENTOR LOAD SWIPER CALL ---*/
function SwiperSliderInit(slider_el){
  //Atrakt Swiper Slider Script
  let animEndEv = 'webkitAnimationEnd animationend';
  let swipermw = (slider_el.hasClass('swiper-mousewheel')) ? true : false;
  let swiperkb = (slider_el.hasClass('swiper-keyboard')) ? true : false;
  let swipercentered = (slider_el.hasClass('swiper-center')) ? true : false;
  let swiperautoplay = slider_el.data('autoplay');
  let swiperinterval = slider_el.data('interval');
  let swiperloop = slider_el.data('loop');
  let swipermousedrag = slider_el.data('mousedrag');
  let swipereffect = slider_el.data('effect');
  let swiperclikable = slider_el.data('clickpage');
  let swiperspeed = slider_el.data('speed');
  let swiperinteraction = slider_el.data('interaction');

  let swipersitems = ( slider_el.data('items') ) ? slider_el.data('items') : 1;
  let swiperstabitems = ( slider_el.data('tab-items') ) ? slider_el.data('tab-items') : 1;
  let swipersmobileitems = ( slider_el.data('mobile-items') ) ? slider_el.data('mobile-items') : 1;

  //Atrakt Swiper Slides Script
  let autoplay = swiperinterval;
  
  // Init elementor swiper
  let Swiper = elementorFrontend.utils.swiper;
  initSwiper();

  async function initSwiper() {
    let slidervar = await new Swiper( slider_el, {
      autoplayDisableOnInteraction: swiperinteraction,
      slidesPerView: swipersitems,
      effect: swipereffect,
      speed: swiperspeed,
      loop: swiperloop,
      paginationClickable: swiperclikable,
      watchSlidesProgress: true,
      autoplay: swiperautoplay,
      simulateTouch: swipermousedrag,
      breakpoints: {
        // when window width is >= 320px
        320: {
          slidesPerView: swipersmobileitems,
        },
        // when window width is >= 480px
        480: {
          slidesPerView: swipersmobileitems,
        },
        // when window width is >= 640px
        640: {
          slidesPerView: swiperstabitems,
        },
        991: {
          slidesPerView: swipersitems,
        }
      },      
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      mousewheelControl: swipermw,
      keyboardControl: swiperkb,
    });
    slidervar.on('slideChange', function (s) {
      let currentSlide = $(slidervar.slides[slidervar.activeIndex]);
      let elems = currentSlide.find('.animated')
      elems.each(function() {
        let $this = $(this);
        let animationType = $this.data('animation');
        $this.addClass(animationType, 100).on(animEndEv, function() {
          $this.removeClass(animationType);
        });
      });
    });
  }   
}
/*----- ELEMENTOR LOAD FUNTION CALL ---*/

$( window ).on( 'elementor/frontend/init', function() {

	var item_hover_class = function( selector ){
		$(selector).on({
		  mouseenter : function() {
			$(this).addClass('naedu-hover');
		  },
		  mouseleave : function() {
			$(this).removeClass('naedu-hover');
		  }
		});
	}; 

	var item_prev_class = function( selector ){
		$(selector).on({
		  mouseenter : function() {
			$(this).prevAll(selector).addClass('process-done');
		  },
		  mouseleave : function() {
			$(this).prevAll(selector).removeClass('process-done');
		  }
		});
	};

	//Education Addon Banner
	elementorFrontend.hooks.addAction( 'frontend/element_ready/naedu_basic_banner.default', function($scope, $){
    let slider_el = $scope.find(".swiper-slides");
    SwiperSliderInit(slider_el);
	} );
	
} );
})(jQuery);
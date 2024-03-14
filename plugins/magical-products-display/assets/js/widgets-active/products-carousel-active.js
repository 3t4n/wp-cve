(function ($) {
	"use strict";
    
     $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/mgpd_carousel.default", function (scope, $) {
       
            var mgpCarousel = $(scope).find(".mgpc-pcarousel");
            var mgpCarLoop = mgpCarousel.data('loop');
            let mgpCarLoopSet = mgpCarLoop ? mgpCarLoop: false;
            var mgpCarDirection = mgpCarousel.data('direction');
            var mgpCarNumber = mgpCarousel.data('number');
            var mgpCarMargin = mgpCarousel.data('margin');
            var mgpCarSpeed = mgpCarousel.data('speed');
            var mgpCarAutoplay = mgpCarousel.data('autoplay');
            let mgpCarAutoplaySet = mgpCarAutoplay ? mgpCarAutoplay: false;
            var mgpCarAutoDelay = mgpCarousel.data('auto-delay');
            var mgpCarGrabCursor = mgpCarousel.data('grab-cursor');
            let mgpCarGrabCursorSet = mgpCarGrabCursor ? mgpCarGrabCursor: false;



            if(mgpCarAutoplaySet == 'yes'){

                $( mgpCarousel ).hover(function() {
                  (this).swiper.autoplay.stop();
                }, function() {
                    (this).swiper.autoplay.start();
                });

              var autoPlayData = {
                    delay: mgpCarAutoDelay,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                  };
            }else{
              var autoPlayData = false;
            }
            // slider number set
            if(mgpCarNumber > 1){
              var breakpointsValue = {
                768: {
                  slidesPerView: 2,
                  spaceBetween: mgpCarMargin,
                },
                991: {
                  slidesPerView: mgpCarNumber,
                  spaceBetween: mgpCarMargin,
                },
              }
            }else{
              var breakpointsValue = {
                991: {
                  slidesPerView: mgpCarNumber,
                  spaceBetween: mgpCarMargin,
                },
              }
            }

            var shopCarouselSwiper = new Swiper (mgpCarousel, {
                  // Optional parameters
                  direction: mgpCarDirection, // vertical
                  slidesPerView: 1,
                  spaceBetween: 10,
                  loop: mgpCarLoopSet,
                  speed: mgpCarSpeed,
                  autoplay: autoPlayData,
                 // autoHeight: true,
                  grabCursor: mgpCarGrabCursorSet,
                  watchSlidesProgress: true,
                  watchSlidesVisibility: true,
                  breakpoints: breakpointsValue,
                  pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                  },
                  navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                  },
                })
                $( document ).ready(function() {
                    $('.mgpc-pcarousel .swiper-slide').removeClass('no-load');
                    
                 });

                
            
        });
    })

  


}(jQuery));	
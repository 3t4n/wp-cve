(function ($) {
	"use strict";
    
     $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/mg_products_slider.default", function (scope, $) {

        	
       
            var mgPdSlider = $(scope).find(".mgpd-slider");
            var mgsLoop = mgPdSlider.data('loop');
            let mgsLoopSet = mgsLoop ? mgsLoop: false;
            var mgsEffect = mgPdSlider.data('effect');
            var mgsDirection = mgPdSlider.data('direction');
            var mgsSpeed = mgPdSlider.data('speed');
            var mgsAutoplay = mgPdSlider.data('autoplay');
            var mgsAutoDelay = mgPdSlider.data('auto-delay');
            var mgsGrabCursor = mgPdSlider.data('grab-cursor');
            let mgsGrabCursorSet = mgsGrabCursor ? mgsGrabCursor: false;
            var mgsNav = mgPdSlider.data('nav');
            var mgsDots = mgPdSlider.data('dots');

            if(mgsAutoplay == true){
              var autoPlayData = {
                    delay: mgsAutoDelay,
                  };
            }else{
              var autoPlayData = false;
            }


            var mgsSwiper = new Swiper (mgPdSlider, {
                  // Optional parameters
                  direction: mgsDirection, // vertical
                  loop: mgsLoopSet,
                  effect: mgsEffect, //"slide", "fade", "cube", "coverflow" or "flip"
                  speed: mgsSpeed,
                  autoplay: autoPlayData,
                  disableOnInteraction: true,
                 // autoHeight: true,
                 // mousewheel: true,
                  grabCursor: mgsGrabCursorSet,
                  parallax: true,
                  watchSlidesProgress: true,
                  watchSlidesVisibility: true,
                  pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                  },
                  navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                  },
                });

                $( mgPdSlider ).hover(function() {
                    (this).swiper.autoplay.stop();
                }, function() {
                    (this).swiper.autoplay.start();
                });
              

            /*mgTimeline.timeline({
              forceVerticalMode: 800,
              mode: 'horizontal',
              visibleItems: 4
            });*/        
            
        });
    })
   


}(jQuery));	



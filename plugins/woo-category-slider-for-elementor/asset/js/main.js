(function($) {
    var OwlCarousel = function ($scope, $) {

    	var cat_carousel = $('.product-cat-slider');
          // var ocOptions = oc.data('carousel-options');
          cat_carousel.each(function(index){
            var carousel_opt = $(this).data('carousel-options');
                $(this).owlCarousel( {
                  dots : $(this).data("dots"),
                  nav : $(this).data("nav"),
                  loop : $(this).data("loop"),
                  autoplay : $(this).data("autoplay"),
                  autoplayTimeout : $(this).data("autoplay-timeout"),
                  mouseDrag : $(this).data("mouse-drag"),
                  touchDrag : $(this).data("touch-drag"),
                  items: $(this).data('items'),
                  autoplayHoverPause: true,
                  navText : ["<div class='slider-left-arrow'></div>","<div class='slider-right-arrow'></div>"],
                  margin: $(this).data('margin'),
                  stagePadding: $(this).data('stagePadding'),
                  rewind: $(this).data('rewind'),
                  slideBy: $(this).data('slideBy'),
                  lazyLoad: $(this).data('lazyLoad'),
                  autoplayHoverPause: $(this).data('autoplayHoverPause'),
                  smartSpeed: $(this).data('smartSpeed'),
                  fluidSpeed: $(this).data('fluidSpeed'),
                  autoplaySpeed: $(this).data('autoplaySpeed'),
                  autoHeight: true,
                  responsive: {
                    0: {
                        items: 1,
                    },
                    // breakpoint from 480 up
                    360: {
                        items: $(this).data('mobile-items'),
                        margin: $(this).data('mobile-margin')
                    },
                    // breakpoint from 768 up
                    768: {
                        items: $(this).data('tablet-items'),
                        margin: $(this).data('tablet-margin')
                    },
                    992: {
                        items: $(this).data('items'),
                    }
                }
              });
          });
    };


    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/pcsfe_category_slider_free.default', OwlCarousel);
    });




    var OwlCarouselSingle = function ($scope, $) {

      var cat_carousel = $('.main-slider');
          // var ocOptions = oc.data('carousel-options');
          cat_carousel.each(function(index){

      var getOwlPro = cat_carousel.owlCarousel( {
                  dots : $(this).data("dots"),
                  nav : $(this).data("nav"),
                  loop : $(this).data("loop"),
                  autoplay : $(this).data("autoplay"),
                  autoplayTimeout : $(this).data("autoplay-timeout"),
                  mouseDrag : $(this).data("mouse-drag"),
                  touchDrag : $(this).data("touch-drag"),
                  items: $(this).data('items'),
                  autoplayHoverPause: true,
                  navText : ["<div class='slider-left-arrow'></div>","<div class='slider-right-arrow'></div>"],
                  margin: $(this).data('margin'),
                  smartSpeed: $(this).data('smartspeed'),
                  autoplaySpeed: $(this).data('autoplaySpeed'),
                  autoHeight: true,
                  responsive: {
                    0: {
                        items: 1,
                    },
                    // breakpoint from 480 up
                    360: {
                        items: $(this).data('mobile-items'),
                        margin: $(this).data('mobile-margin')
                    },
                    // breakpoint from 768 up
                    768: {
                        items: $(this).data('tablet-items'),
                        margin: $(this).data('tablet-margin')
                    },
                    992: {
                        items: $(this).data('items'),
                    }
                }
              });
              function setAnimation(_elem, _InOut) {
            // Store all animationend event name in a string.
            // cf animate.css documentation
            var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

            _elem.each(function() {
                var $elem = $(this);
                var $animationType = 'animated ' + $elem.data('animation-' + _InOut);

                $elem.addClass($animationType).one(animationEndEvent, function() {
                    $elem.removeClass($animationType); // remove animate.css Class at the end of the animations
                });
            });
        }
                // Fired before current slide change
          getOwlPro.on('change.owl.carousel', function(event) {
              var $currentItem = $('.owl-item', getOwlPro).eq(event.item.index);
              var $elemsToanim = $currentItem.find("[data-animation-out]");
              setAnimation($elemsToanim, 'out');
          });

          // Fired after current slide has been changed
          var round = 0;
          getOwlPro.on('changed.owl.carousel', function(event) {
              var $currentItem = $('.owl-item', getOwlPro).eq(event.item.index);
              var $elemsToanim = $currentItem.find("[data-animation-in]");

              setAnimation($elemsToanim, 'in');
          });

          });

    };


    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/pcsfe_category_single_slider.default', OwlCarouselSingle);
    });


})(jQuery);
/*
Template Name: Medical Addon for Elementor
Author: NicheAddon
Version: 1.0.0
Email: support@nicheaddon.com
*/

(function($){
'use strict';

/*----- ELEMENTOR LOAD FUNTION CALL ---*/

$( window ).on( 'elementor/frontend/init', function() {
	//Owl Carousel Slider Script
	var owl_carousel = function(){
		$('.owl-carousel').each( function() {
	    var $carousel = $(this);
	    var $items = ($carousel.data('items') !== undefined) ? $carousel.data('items') : 1;
	    var $items_tablet = ($carousel.data('items-tablet') !== undefined) ? $carousel.data('items-tablet') : 1;
	    var $items_mobile_landscape = ($carousel.data('items-mobile-landscape') !== undefined) ? $carousel.data('items-mobile-landscape') : 1;
	    var $items_mobile_portrait = ($carousel.data('items-mobile-portrait') !== undefined) ? $carousel.data('items-mobile-portrait') : 1;
	    $carousel.owlCarousel ({
	      loop : ($carousel.data('loop') !== undefined) ? $carousel.data('loop') : true,
	      items : $carousel.data('items'),
	      margin : ($carousel.data('margin') !== undefined) ? $carousel.data('margin') : 0,
	      dots : ($carousel.data('dots') !== undefined) ? $carousel.data('dots') : true,
	      nav : ($carousel.data('nav') !== undefined) ? $carousel.data('nav') : false,
	      navText : ["<div class='slider-no-current'><span class='current-no'></span><span class='total-no'></span></div><span class='current-monials'></span>", "<div class='slider-no-next'></div><span class='next-monials'></span>"],
	      autoplay : ($carousel.data('autoplay') !== undefined) ? $carousel.data('autoplay') : false,
	      autoplayTimeout : ($carousel.data('autoplay-timeout') !== undefined) ? $carousel.data('autoplay-timeout') : 5000,
	      animateIn : ($carousel.data('animatein') !== undefined) ? $carousel.data('animatein') : false,
	      animateOut : ($carousel.data('animateout') !== undefined) ? $carousel.data('animateout') : false,
	      mouseDrag : ($carousel.data('mouse-drag') !== undefined) ? $carousel.data('mouse-drag') : true,
	      autoWidth : ($carousel.data('auto-width') !== undefined) ? $carousel.data('auto-width') : false,
	      autoHeight : ($carousel.data('auto-height') !== undefined) ? $carousel.data('auto-height') : false,
	      center : ($carousel.data('center') !== undefined) ? $carousel.data('center') : false,
	      responsiveClass: true,
	      dotsEachNumber: true,
	      smartSpeed: 600,
	      autoplayHoverPause: true,
	      responsive : {
	        0 : {
	          items : $items_mobile_portrait,
	        },
	        480 : {
	          items : $items_mobile_landscape,
	        },
	        768 : {
	          items : $items_tablet,
	        },
	        992 : {
	          items : $items,
	        }
	      }
	    });
	    var totLength = $('.owl-dot', $carousel).length;
	    $('.total-no', $carousel).html(totLength);
	    $('.current-no', $carousel).html(totLength);
	    $carousel.owlCarousel();
	    $('.current-no', $carousel).html(1);
	    $carousel.on('changed.owl.carousel', function(event) {
	      var total_items = event.page.count;
	      var currentNum = event.page.index + 1;
	      $('.total-no', $carousel ).html(total_items);
	      $('.current-no', $carousel).html(currentNum);
	    });
	  });
	}; // end

	//Medical Addon for Elementor Preloader Script
  $('.namep-preloader').fadeOut(500);

	var item_hover_class = function( selector ){
		$(selector).on({
		  mouseenter : function() {
			$(this).addClass('namep-hover');
		  },
		  mouseleave : function() {
			$(this).removeClass('namep-hover');
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

	//Medical Addon for Elementor Services
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_services.default', function($scope, $){
		item_hover_class('.namep-service-item');
	} );
	//Medical Addon for Elementor Blog
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_blog.default', function($scope, $){
		item_hover_class('.namep-news-item');
    $('.namep-item').matchHeight ({
      property: 'height'
    });
	} );
	//Medical Addon for Elementor Gallery
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_gallery.default', function($scope, $){
		item_hover_class('.namep-gallery-item');
		$('.masonry-wrap').each(function(i, gridContainer) {
      var $gridContainer = $(gridContainer);
      var $grid = $gridContainer.find('.namep-masonry').imagesLoaded(function() {
        $grid.isotope ({
          itemSelector: '.masonry-item',
          // layoutMode: 'packery',
          percentPosition: true,
          isFitWidth: true,
        })
      });
      $grid.packery({
	      itemSelector: '.masonry-item'
	    });
      $gridContainer.find('.masonry-filters').on('click', 'li a', function() {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope ({
          filter: filterValue,
        });
      });
    });
    $('.masonry-filters').each( function( i, buttonGroup ) {
      var $buttonGroup = $(buttonGroup);
      $buttonGroup.on( 'click', 'li a', function() {
        $buttonGroup.find('.active').removeClass('active');
        $(this).addClass('active');
      });
    });
	} );
	//Medical Addon for Elementor Contact
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_contact.default', function($scope, $){
		item_hover_class('.namep-contact-item');
	} );
	//Medical Addon for Elementor Process
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_process.default', function($scope, $){
	  item_prev_class('.namep-process-item');
	} );
	//Medical Addon for Elementor Team
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_team.default', function($scope, $){
	  item_hover_class('.namep-mate-item');
	} );
	//Medical Addon for Elementor Testimonials
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_testimonials.default', function($scope, $){
		owl_carousel();
	} );
	//Medical Addon for Elementor Video Popup
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_video.default', function($scope, $){
	  item_hover_class('.namep-video-wrap');
	} );
	//Medical Addon for Elementor History
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_history.default', function($scope, $){
	  // item_hover_class('.namep-history-item');
		owl_carousel();
		$('.namep-item').matchHeight ({
	    property: 'height'
	  });
	} );
	//Medical Addon for Elementor Slider
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_basic_slider.default', function($scope, $){
		//Fame Swiper Slider Script
    $('.swiper-slides').each(function (index) {
      //Fame Swiper Slider Script
      var animEndEv = 'webkitAnimationEnd animationend';
      var swipermw = $('.swiper-container.swiper-mousewheel').length ? true : false;
      var swiperkb = $('.swiper-container.swiper-keyboard').length ? true : false;
      var swipercentered = $('.swiper-container.swiper-center').length ? true : false;
      var swiperautoplay = $('.swiper-container').data('autoplay');
      var swiperloop = $('.swiper-container').data('loop');
      var swipermousedrag = $('.swiper-container').data('mousedrag');
      var swipereffect = $('.swiper-container').data('effect');
      var swiperclikable = $('.swiper-container').data('clickpage');
      var swiperspeed = $('.swiper-container').data('speed');

      //Fame Swiper Slides Script
      var swiper = new Swiper($(this), {
        autoplay: swiperautoplay,
        effect: swipereffect,
        speed: swiperspeed,
        loop: swiperloop,
        paginationClickable: swiperclikable,
        watchSlidesProgress: true,
        simulateTouch: swipermousedrag,
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
      swiper.on('slideChange', function (s) {
        var currentSlide = $(swiper.slides[swiper.activeIndex]);
          var elems = currentSlide.find('.animated')
          elems.each(function() {
            var $this = $(this);
            var animationType = $this.data('animation');
            $this.addClass(animationType, 100).on(animEndEv, function() {
              $this.removeClass(animationType);
            });
          });
      });
    });
	} );

	//Medical Addon for Elementor Valuable Box
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_unique_valuable.default', function($scope, $){
	  item_hover_class('.namep-valuable-item');
	  $('.namep-item').matchHeight ({
	    property: 'height'
	  });
	  // Slick Vertical Slider
	  jQuery('.slick-vertical-slider').not('.slick-initialized').slick ({
	    dots: false,
	    vertical: true,
	    slidesToShow: 3,
	    slidesToScroll: 1,
	    verticalSwiping: true,
	  });
	} );

	//Medical Addon for Elementor Stats
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_unique_stats.default', function($scope, $){
	  item_hover_class('.namep-stats-item');
	} );

	//Medical Addon for Elementor Hospitals
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_unique_hospitals.default', function($scope, $){
	  item_hover_class('.namep-hospital-item');
	} );

	//Medical Addon for Elementor Tools
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_unique_tools.default', function($scope, $){
	  item_hover_class('.namep-tool-item');
	  item_hover_class('.namep-care-item');
	} );

	//Medical Addon for Elementor Tools
	elementorFrontend.hooks.addAction( 'frontend/element_ready/namedical_unique_departments.default', function($scope, $){
	  item_hover_class('.namep-department-item');
	} );
	
} );
})(jQuery);
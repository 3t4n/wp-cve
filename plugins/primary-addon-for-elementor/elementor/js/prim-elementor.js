/*
Template Name: Primary Addon for Elementor
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
  let swiperspace = slider_el.data('space');
  let swipersitems = slider_el.data('items');

  //Atrakt Swiper Slides Script
  let autoplay = swiperinterval;
  
  // Init elementor swiper
  let Swiper = elementorFrontend.utils.swiper;
  initSwiper();

  async function initSwiper() {
    let slidervar = await new Swiper( slider_el, {
      autoplayDisableOnInteraction: swiperinteraction,
      slidesPerView: 1,
      effect: swipereffect,
      speed: swiperspeed,
      loop: swiperloop,
      paginationClickable: swiperclikable,
      watchSlidesProgress: true,
      autoplay: swiperautoplay,
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

	//Primary Addon for Elementor Preloader Script
  $('.napae-preloader').fadeOut(500);

	var item_hover_class = function( selector ){
		$(selector).on({
		  mouseenter : function() {
			$(this).addClass('napae-hover');
		  },
		  mouseleave : function() {
			$(this).removeClass('napae-hover');
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

	//Primary Addon for Elementor Services
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_services.default', function($scope, $){
		item_hover_class('.napae-service-item');
	} );
	//Primary Addon for Elementor Blog
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_blog.default', function($scope, $){
		item_hover_class('.napae-news-item');
    $('.napae-item').matchHeight ({
      property: 'height'
    });
    
    //Flickity Carousel Slider Script
	  $('.flick-carousel').each( function() {
	    var $Flick = $(this);
	    $Flick.flickity  ({
	      draggable : ($Flick.data('draggable') !== undefined) ? $Flick.data('draggable') : false,
	      freeScroll : ($Flick.data('freescroll') !== undefined) ? $Flick.data('freescroll') : false,
	      freeScrollFriction : ($Flick.data('freescrollfriction') !== undefined) ? $Flick.data('freescrollfriction') : 0.075,
	      wrapAround : ($Flick.data('wraparound') !== undefined) ? $Flick.data('wraparound') : true,
	      groupCells : ($Flick.data('groupcells') !== undefined) ? $Flick.data('groupcells') : '',
	      autoPlay : ($Flick.data('autoplay') !== undefined) ? $Flick.data('autoplay') : '',
	      pauseAutoPlayOnHover : ($Flick.data('pauseautoplayonhover') !== undefined) ? $Flick.data('pauseautoplayonhover') : false,
	      adaptiveHeight : ($Flick.data('adaptiveheight') !== undefined) ? $Flick.data('adaptiveheight') : false,
	      dragThreshold : ($Flick.data('dragthreshold') !== undefined) ? $Flick.data('dragthreshold') : '',
	      selectedAttraction : ($Flick.data('selectedattraction') !== undefined) ? $Flick.data('selectedattraction') : 0.025,
	      friction : ($Flick.data('friction') !== undefined) ? $Flick.data('friction') : 0.28,
	      initialIndex : ($Flick.data('initialindex') !== undefined) ? $Flick.data('initialindex') : '',
	      accessibility : ($Flick.data('accessibility') !== undefined) ? $Flick.data('accessibility') : true,
	      setGallerySize : ($Flick.data('setgallerysize') !== undefined) ? $Flick.data('setgallerysize') : true,
	      resize : ($Flick.data('resize') !== undefined) ? $Flick.data('resize') : true,
	      cellAlign : ($Flick.data('cellalign') !== undefined) ? $Flick.data('cellalign') : 'center',
	      contain : ($Flick.data('contain') !== undefined) ? $Flick.data('contain') : false,
	      rightToLeft : ($Flick.data('righttoleft') !== undefined) ? $Flick.data('righttoleft') : false,
	      prevNextButtons : ($Flick.data('prevnextbuttons') !== undefined) ? $Flick.data('prevnextbuttons') : false,
	      pageDots : ($Flick.data('pagedots') !== undefined) ? $Flick.data('pagedots') : false,
	    });
	  });

	} );
	//Primary Addon for Elementor Gallery
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_gallery.default', function($scope, $){
		item_hover_class('.napae-gallery-item');
		$('.masonry-wrap').each(function(i, gridContainer) {
      var $gridContainer = $(gridContainer);
      var $grid = $gridContainer.find('.napae-masonry').imagesLoaded(function() {
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
	//Primary Addon for Elementor Contact
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_contact.default', function($scope, $){
		item_hover_class('.napae-contact-item');
	} );
	//Primary Addon for Elementor Process
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_process.default', function($scope, $){
	  item_prev_class('.napae-process-item');
	} );
	//Primary Addon for Elementor Team
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_team.default', function($scope, $){
	  item_hover_class('.napae-mate-item');
	} );
	//Primary Addon for Elementor Video Popup
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_video.default', function($scope, $){
	  item_hover_class('.napae-video-wrap');
	} );
	//Primary Addon for Elementor History
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_history.default', function($scope, $){
	  // item_hover_class('.napae-history-item');
		$('.napae-item').matchHeight ({
	    property: 'height'
	  });
	} );
	
	//Primary Addon for Elementor Slider
	elementorFrontend.hooks.addAction( 'frontend/element_ready/prim_basic_slider.default', function($scope, $){
		//Primary Swiper Slider Script
		let slider_el = $scope.find(".swiper-slides");
		SwiperSliderInit(slider_el);    
	} );

	
	
} );
})(jQuery);
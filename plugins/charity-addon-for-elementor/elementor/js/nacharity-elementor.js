/*
Template Name: Charity Addon for Elementor
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

	var item_hover_class = function( selector ){
		$(selector).on({
		  mouseenter : function() {
			$(this).addClass('nacep-hover');
		  },
		  mouseleave : function() {
			$(this).removeClass('nacep-hover');
		  }
		});
	};

	var item_prev_class = function( selector ){
		$(selector).on({
		  mouseenter : function() {
			$(this).prevAll(selector).addClass('process-done');
			$(this).addClass('process-done');
		  },
		  mouseleave : function() {
			$(this).prevAll(selector).removeClass('process-done');
			$(this).removeClass('process-done');
		  }
		});
	};

	//Charity Addon for Elementor Blog
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_blog.default', function($scope, $){
		item_hover_class('.nacep-news-item');
    $('.nacep-item').matchHeight ({
      property: 'height'
    });
	} );

	//Charity Addon for Elementor Contact
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_contact.default', function($scope, $){
		item_hover_class('.nacep-contact-item');
	} );

	//Charity Addon for Elementor Gallery
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_gallery.default', function($scope, $){
		item_hover_class('.nacep-gallery-item');
		$('.masonry-wrap').each(function(i, gridContainer) {
      var $gridContainer = $(gridContainer);
      var $grid = $gridContainer.find('.nacep-masonry').imagesLoaded(function() {
        $grid.isotope ({
          itemSelector: '.masonry-item',
          layoutMode: 'packery',
          percentPosition: true,
          isFitWidth: true,
        })
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

  //Charity Addon for Elementor History
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_history.default', function($scope, $){
    // item_hover_class('.nacep-history-item');
    owl_carousel();
    $('.nacep-item').matchHeight ({
      property: 'height'
    });
  } );

	//Charity Addon for Elementor Process
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_process.default', function($scope, $){
	  item_prev_class('.nacep-process-item');
	} );

	//Charity Addon for Elementor Services
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_services.default', function($scope, $){
		item_hover_class('.nacep-service-item');
	} );

  //Charity Addon for Elementor Slider
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_slider.default', function($scope, $){
    //Charity Swiper Slider Script
    let slider_el = $scope.find(".swiper-slides");
    SwiperSliderInit(slider_el);
  } );

	//Charity Addon for Elementor Team
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_team.default', function($scope, $){
	  item_hover_class('.nacep-team-item');
	} );

	//Charity Addon for Elementor Testimonials
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_testimonials.default', function($scope, $){
	  item_hover_class('.nacep-testimonial-item');
		owl_carousel();
	} );

	//Charity Addon for Elementor Video Popup
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_basic_video.default', function($scope, $){
	  item_hover_class('.nacep-video-wrap');
	} );

	// Charity Addon for Elementor Countdown
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_countdown.default', function($scope, $){
  	//Countdown Script
    $('.nacep-countdown.static, .nacep-countdown.dynamic').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var format = $countdown.data("format");
      var count_format = format ? format : 'dHMS';
      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      var austDay = new Date();
      austDay = new Date(date);

      $countdown.countdown({
        until: austDay,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        format: count_format,
        padZeroes: true,
      });
    });

    // Fake COuntdown Script
    $('.nacep-countdown.fake').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var today = new Date();
      var newdate = new Date();
      newdate.setDate(today.getDate() + date);
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      today =  new Date(newdate);

      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      $(this).countdown({
        until: today,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        padZeroes: true,
      });
    });
	} );

	// Charity Addon for Elementor Urgent Cause
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_urgent_cause.default', function($scope, $){
  	//Countdown Script
    $('.nacep-countdown.static, .nacep-countdown.dynamic').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var format = $countdown.data("format");
      var count_format = format ? format : 'dHMS';
      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      var austDay = new Date();
      austDay = new Date(date);

      $countdown.countdown({
        until: austDay,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        format: count_format,
        padZeroes: true,
      });
    });

    // Fake COuntdown Script
    $('.nacep-countdown.fake').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var today = new Date();
      var newdate = new Date();
      newdate.setDate(today.getDate() + date);
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      today =  new Date(newdate);

      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      $(this).countdown({
        until: today,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        padZeroes: true,
      });
    });
    $('.circle-progressbar-wrap').each( function() {
      var $bar = $(this);
      var $color = ($bar.data('color') !== undefined) ? $bar.data('color') : '#55c981';
      var $fill = ($bar.data('fill') !== undefined) ? $bar.data('fill') : '#ffffff';
      var $reverse = ($bar.data('reverse') !== undefined) ? $bar.data('reverse') : true;
      var $size = ($bar.data('size') !== undefined) ? $bar.data('size') : 150;
      var $thickness = ($bar.data('thickness') !== undefined) ? $bar.data('thickness') : 10;
      $('.circle-progressbar').circleProgress ({
        size: $size,
        fill: {
          color: $color
        },
        thickness: $thickness,
        emptyFill: $fill,
        startAngle: 300,
        reverse: $reverse,
        lineCap: 'round',
        animation: {
          duration: 1800
        }
      })
      .on('circle-animation-progress', function (event, progress, stepValue) {
        $(this).find('.circle-counter').text((stepValue * 100).toFixed(0));
      });      
    });
	} );

	//Charity Addon for Elementor Needs
	elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_peoples_need.default', function($scope, $){
		owl_carousel();
	} );

	//Charity Addon for Elementor Slider
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_slider.default', function($scope, $){
    //Charity Swiper Slider Script
    let slider_el = $scope.find(".swiper-slides");
    SwiperSliderInit(slider_el);
    
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );

  //Charity Addon for Elementor Cause List
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_cause_list.default', function($scope, $){
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );

  //Charity Addon for Elementor Cause Single
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_single_cause.default', function($scope, $){
    $('.circle-progressbar-wrap').each( function() {
      var $bar = $(this);
      var $color = ($bar.data('color') !== undefined) ? $bar.data('color') : '#55c981';
      var $fill = ($bar.data('fill') !== undefined) ? $bar.data('fill') : '#ffffff';
      var $reverse = ($bar.data('reverse') !== undefined) ? $bar.data('reverse') : true;
      var $size = ($bar.data('size') !== undefined) ? $bar.data('size') : 150;
      var $thickness = ($bar.data('thickness') !== undefined) ? $bar.data('thickness') : 10;
      $('.circle-progressbar').circleProgress ({
        size: $size,
        fill: {
          color: $color
        },
        thickness: $thickness,
        emptyFill: $fill,
        startAngle: 300,
        reverse: $reverse,
        lineCap: 'round',
        animation: {
          duration: 1800
        }
      })
      .on('circle-animation-progress', function (event, progress, stepValue) {
        $(this).find('.circle-counter').text((stepValue * 100).toFixed(0));
      });      
    });
  } );

  //Charity Addon for Elementor Filter
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_cause_filter.default', function($scope, $){
    $('.masonry-wrap').each(function(i, gridContainer) {
      var $gridContainer = $(gridContainer);
      var $grid = $gridContainer.find('.nacep-masonry').imagesLoaded(function() {
        $grid.isotope ({
          itemSelector: '.masonry-item',
          layoutMode: 'packery',
          percentPosition: true,
          isFitWidth: true,
        })
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
    owl_carousel();
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );

  //Charity Addon for Elementor Cause List Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_cause_list_charitable.default', function($scope, $){
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );

  // Charity Addon for Elementor Countdown Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_countdown_charitable.default', function($scope, $){
    //Countdown Script
    $('.nacep-countdown.static, .nacep-countdown.dynamic').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var format = $countdown.data("format");
      var count_format = format ? format : 'dHMS';
      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      var austDay = new Date();
      austDay = new Date(date);

      $countdown.countdown({
        until: austDay,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        format: count_format,
        padZeroes: true,
      });
    });

    // Fake COuntdown Script
    $('.nacep-countdown.fake').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var today = new Date();
      var newdate = new Date();
      newdate.setDate(today.getDate() + date);
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      today =  new Date(newdate);

      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      $(this).countdown({
        until: today,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        padZeroes: true,
      });
    });
  } );

  // Charity Addon for Elementor Urgent Cause Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_urgent_cause_charitable.default', function($scope, $){
    //Countdown Script
    $('.nacep-countdown.static, .nacep-countdown.dynamic').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var format = $countdown.data("format");
      var count_format = format ? format : 'dHMS';
      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      var austDay = new Date();
      austDay = new Date(date);

      $countdown.countdown({
        until: austDay,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        format: count_format,
        padZeroes: true,
      });
    });

    // Fake COuntdown Script
    $('.nacep-countdown.fake').each( function() {
      var $countdown = $(this);
      var date = $countdown.data("date");
      var today = new Date();
      var newdate = new Date();
      newdate.setDate(today.getDate() + date);
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      today =  new Date(newdate);

      // Plural Labels
      var years = $countdown.data("years");
      var months = $countdown.data("months");
      var weeks = $countdown.data("weeks");
      var days = $countdown.data("days");
      var hours = $countdown.data("hours");
      var minutes = $countdown.data("minutes");
      var seconds = $countdown.data("seconds");
      // Singular Labels
      var year = $countdown.data("year");
      var month = $countdown.data("month");
      var week = $countdown.data("week");
      var day = $countdown.data("day");
      var hour = $countdown.data("hour");
      var minute = $countdown.data("minute");
      var second = $countdown.data("second");

      $(this).countdown({
        until: today,
        labels: [years,months,weeks,days,hours,minutes,seconds],
        labels1: [year,month,week,day,hour,minute,second],
        padZeroes: true,
      });
    });
    $('.circle-progressbar-wrap').each( function() {
      var $bar = $(this);
      var $color = ($bar.data('color') !== undefined) ? $bar.data('color') : '#55c981';
      var $fill = ($bar.data('fill') !== undefined) ? $bar.data('fill') : '#ffffff';
      var $reverse = ($bar.data('reverse') !== undefined) ? $bar.data('reverse') : true;
      var $size = ($bar.data('size') !== undefined) ? $bar.data('size') : 150;
      var $thickness = ($bar.data('thickness') !== undefined) ? $bar.data('thickness') : 10;
      $('.circle-progressbar').circleProgress ({
        size: $size,
        fill: {
          color: $color
        },
        thickness: $thickness,
        emptyFill: $fill,
        startAngle: 300,
        reverse: $reverse,
        lineCap: 'round',
        animation: {
          duration: 1800
        }
      })
      .on('circle-animation-progress', function (event, progress, stepValue) {
        $(this).find('.circle-counter').text((stepValue * 100).toFixed(0));
      });      
    });
  } );

  //Charity Addon for Elementor Slider Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_slider_charitable.default', function($scope, $){
    //Charity Swiper Slider Script
    let slider_el = $scope.find(".swiper-slides");
    SwiperSliderInit(slider_el);
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );
  
  //Charity Addon for Elementor Slider Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_slider_cause.default', function($scope, $){
    //Charity Swiper Slider Script
    let slider_el = $scope.find(".swiper-slides");
    SwiperSliderInit(slider_el);
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );

  //Charity Addon for Elementor Filter Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_cause_filter_charitable.default', function($scope, $){
    $('.masonry-wrap').each(function(i, gridContainer) {
      var $gridContainer = $(gridContainer);
      var $grid = $gridContainer.find('.nacep-masonry').imagesLoaded(function() {
        $grid.isotope ({
          itemSelector: '.masonry-item',
          layoutMode: 'packery',
          percentPosition: true,
          isFitWidth: true,
        })
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
    owl_carousel();
    var delay = 0;
    $('.progress-bar').each(function(i) {
      $(this).delay( delay*i ).animate ({
        width: $(this).attr('aria-valuenow') + '%'
      }, delay);
      $(this).prop('Counter',0).animate ({
        Counter: $(this).text()
      },
      {
        duration: delay,
        easing: 'swing',
      });
    });
  } );
  
  //Charity Addon for Elementor Cause Single Charitable
  elementorFrontend.hooks.addAction( 'frontend/element_ready/nacharity_unique_single_cause_charitable.default', function($scope, $){
    $('.circle-progressbar-wrap').each( function() {
      var $bar = $(this);
      var $color = ($bar.data('color') !== undefined) ? $bar.data('color') : '#55c981';
      var $fill = ($bar.data('fill') !== undefined) ? $bar.data('fill') : '#ffffff';
      var $reverse = ($bar.data('reverse') !== undefined) ? $bar.data('reverse') : true;
      var $size = ($bar.data('size') !== undefined) ? $bar.data('size') : 150;
      var $thickness = ($bar.data('thickness') !== undefined) ? $bar.data('thickness') : 10;
      $('.circle-progressbar').circleProgress ({
        size: $size,
        fill: {
          color: $color
        },
        thickness: $thickness,
        emptyFill: $fill,
        startAngle: 300,
        reverse: $reverse,
        lineCap: 'round',
        animation: {
          duration: 1800
        }
      })
      .on('circle-animation-progress', function (event, progress, stepValue) {
        $(this).find('.circle-counter').text((stepValue * 100).toFixed(0));
      });      
    });
  } );


} );

})(jQuery);
jQuery(document).ready(function($) {
  "use strict";
  
  //Naedu Owl Carousel Slider Script
  $('.owl-carousel').each( function() {
    var $carousel = $(this);
    var $items = ($carousel.data('items') !== undefined) ? $carousel.data('items') : 1;
    var $items_tablet = ($carousel.data('items') !== undefined) ? $carousel.data('items-tablet') : 1;
    var $items_mobile_landscape = ($carousel.data('items-mobile-landscape') !== undefined) ? $carousel.data('items-mobile-landscape') : 1;
    var $items_mobile_portrait = ($carousel.data('items-mobile-portrait') !== undefined) ? $carousel.data('items-mobile-portrait') : 1;
    var $stagep_tablet = ($carousel.data('stagep-tablet') !== undefined) ? $carousel.data('stagep-tablet') : 1;
    var $stagep_desktop = ($carousel.data('stagep-desktop') !== undefined) ? $carousel.data('stagep-desktop') : 1;
    $carousel.owlCarousel ({
      loop : ($carousel.data('loop') !== undefined) ? $carousel.data('loop') : true,
      items : $carousel.data('items'),
      margin : ($carousel.data('margin') !== undefined) ? $carousel.data('margin') : 0,
      stagePadding : ($carousel.data('stagepadding') !== undefined) ? $carousel.data('stagepadding') : 0,
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
          stagePadding: $stagep_tablet,
        },
        1200 : {
          items : $items,
          stagePadding: $stagep_desktop,
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

  //Naedu Countdown Script
  $('.naedu-timecircles').each( function() {
    var $TimeCC = $(this);

    var tc_animation = ($TimeCC.data('animation') !== undefined) ? $TimeCC.data('animation') : "smooth";
    var tc_angle = ($TimeCC.data('angle') !== undefined) ? $TimeCC.data('angle') : 0;
    var tc_direction = ($TimeCC.data('direction') !== undefined) ? $TimeCC.data('direction') : "Clockwise";
    var tc_bg_color = ($TimeCC.data('bg-color') !== undefined) ? $TimeCC.data('bg-color') : "#f8f9fa";
    var tc_fg_width = ($TimeCC.data('fg-width') !== undefined) ? $TimeCC.data('fg-width') : 0.03;
    var tc_bg_width = ($TimeCC.data('bg-width') !== undefined) ? $TimeCC.data('bg-width') : 1;

    var day_show = ($TimeCC.data('day-show') !== undefined) ? $TimeCC.data('day-show') : false;
    var day_text = ($TimeCC.data('day-text') !== undefined) ? $TimeCC.data('day-text') : "Days";
    var day_color = ($TimeCC.data('day-color') !== undefined) ? $TimeCC.data('day-color') : "#40407a";

    var hr_show = ($TimeCC.data('hr-show') !== undefined) ? $TimeCC.data('hr-show') : true;
    var hr_text = ($TimeCC.data('hr-text') !== undefined) ? $TimeCC.data('hr-text') : "Hours";
    var hr_color = ($TimeCC.data('hr-color') !== undefined) ? $TimeCC.data('hr-color') : "#40407a";

    var min_show = ($TimeCC.data('min-show') !== undefined) ? $TimeCC.data('min-show') : true;
    var min_text = ($TimeCC.data('min-text') !== undefined) ? $TimeCC.data('min-text') : "Minutes";
    var min_color = ($TimeCC.data('min-color') !== undefined) ? $TimeCC.data('min-color') : "#40407a";

    var sec_show = ($TimeCC.data('sec-show') !== undefined) ? $TimeCC.data('sec-show') : true;
    var sec_text = ($TimeCC.data('sec-text') !== undefined) ? $TimeCC.data('sec-text') : "Seconds";
    var sec_color = ($TimeCC.data('sec-color') !== undefined) ? $TimeCC.data('sec-color') : "#40407a";

    $TimeCC.TimeCircles ({
      animation: tc_animation,
      start_angle: tc_angle,
      direction: tc_direction,
      circle_bg_color: tc_bg_color,
      fg_width: tc_fg_width,
      bg_width: tc_bg_width,
      count_past_zero: false,
      time: {
        Days: {
          show:day_show,
          text:day_text,
          color:day_color
        },
        Hours: {
          show:hr_show,
          text:hr_text,
          color:hr_color
        },
        Minutes: {
          show:min_show,
          text:min_text,
          color:min_color
        },
        Seconds: {
          show:sec_show,
          text:sec_text,
          color:sec_color
        }
      }
    });
  });

  $(window).load(function() {
    if($('div').hasClass('swiper-slides')) {
      $('.swiper-slides').each(function (index) {
        //Naedu Swiper Slider Script
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
        var swiperitem = $('.swiper-container').data('item');
        var swiperspace = $('.swiper-container').data('space');

        //Naedu Swiper Slides Script
        var swiper = new Swiper($(this), {
          slidesPerView: swiperitem,
          effect: swipereffect,
          spaceBetween: swiperspace,
          autoplay: swiperautoplay,
          speed: swiperspeed,
          loop: swiperloop,
          paginationClickable: swiperclikable,
          simulateTouch: swipermousedrag,
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          scrollbar: {
            el: '.swiper-scrollbar',
            hide: false,
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
    }
  });

  $(document).on('click', '.naedu-service-carousel-nav .owl-prev', function(){
    $(this).parents('.naedu-service-wrapper').find('.owl-carousel .owl-prev').trigger('click');
  });

  $(document).on('click', '.naedu-service-carousel-nav .owl-next', function(){
    $(this).parents('.naedu-service-wrapper').find('.owl-carousel .owl-next').trigger('click');
  });

});
jQuery(document).ready(function($) {
  "use strict";
  
  //Nacep Hover Script
  $('.nacep-service-item, .nacep-news-item, .nacep-gallery-item, .nacep-contact-item, .nacep-team-item, .nacep-testimonial-item, .nacep-video-wrap, .nacep-history-item').hover (
    function() {
      $(this).addClass('nacep-hover');
    },
    function() {
      $(this).removeClass('nacep-hover');
    }
  );

  //Owl Carousel Slider Script
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

  // Match Height Script
  $('.nacep-item').matchHeight();

  //Nacep Masonry Script
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

  //Nacep Popup Picture Script
  $('.nacep-popup').magnificPopup ({
    delegate: 'a',
    type: 'image',
    closeOnContentClick: false,
    closeBtnInside: false,
    mainClass: 'mfp-with-zoom mfp-img-mobile',
    closeMarkup:'<div class="mfp-close" title="%title%"></div>',
    image: {
      verticalFit: true,
      titleSrc: function(item) {
        return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">image source</a>';
      }
    },
    gallery: {
      enabled: true,
      arrowMarkup:'<div title="%title%" class="mfp-arrow mfp-arrow-%dir%"></div>',
    },
    zoom: {
      enabled: true,
      duration: 300,
      opener: function(element) {
        return element.find('*');
      }
    }
  });

  //Nacep Magnific Popup Video Script
  $('.nacep-popup-video').magnificPopup ({
    mainClass: 'mfp-fade',
    type: 'iframe',
    closeMarkup:'<div class="mfp-close" title="%title%"></div>',
    iframe: {
      patterns: {
        youtube: {
          index: 'youtube.com/', 
          id: function(url) {        
            var m = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
            if ( !m || !m[1] ) return null;
            return m[1];
          },
          src: 'https://www.youtube.com/embed/%id%?autoplay=1'
        },
        vimeo: {
          index: 'vimeo.com/', 
          id: function(url) {        
            var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
            if ( !m || !m[5] ) return null;
            return m[5];
          },
          src: 'https://player.vimeo.com/video/%id%?autoplay=1'
        },
        dailymotion: {
          index: 'dailymotion.com/',
          id: function(url) {        
            var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
            if ( !m || !m[2] ) return null;
            return m[2];
          },
          src: 'https://iframespot.blogspot.com/ncr/?m=0&type=dv&url=https%3A%2F%2Fwww.dailymotion.com%2Fembed%2Fvideo%2F%id%%3Fapi%3D0%26autoplay%3D1%26info%3D0%26logo%3D0%26social%3D0%26related%3D0'
        }
      }
    }
  });
  if ($('div').hasClass('nacep-popup')) {
    $('.nacep-popup').find('a').attr("data-elementor-open-lightbox","no");
  }

  //Nacep Add Class In Previous Items
  $('.nacep-process-item').hover(function() {
    $(this).prevAll('.nacep-process-item').toggleClass('process-done');
    $(this).toggleClass('process-done');
  });

  //Nacep Countdown Script
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

  // Nacep Fake COuntdown Script
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

  $('.nacep-tab-links a').on('click', function(e) {
    var currentAttrValue = $(this).attr('href');

    // Show/Hide Tabs
    $('.nacep-tab-content ' + currentAttrValue).fadeIn(400).siblings().hide().stop(true, true);

    // Change/remove current tab to active
    $(this).parent('li').addClass('active').siblings().removeClass('active');

    e.preventDefault();
  });

  setTimeout(function() {
    $('.nacep-cta').addClass('active');
  }, 6000);

  $('.cta-close').click(function() {
    $('.nacep-cta').fadeOut('normal', function() {
      $(this).remove();
      $('.nacep-cta').removeClass('active');
    });
  });

  //Nacep Progress Bar Script
  $('.nacep-cause-bar').waypoint(function() {
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
  },
  {
    offset: '100%',
    triggerOnce: true,
  });

  //Nacep Circle Progress Bar Script
  $('.circle-progressbar-wrap').each( function() {
    var $bar = $(this);
    var $color = ($bar.data('color') !== undefined) ? $bar.data('color') : '#55c981';
    var $fill = ($bar.data('fill') !== undefined) ? $bar.data('fill') : '#ffffff';
    var $reverse = ($bar.data('reverse') !== undefined) ? $bar.data('reverse') : true;
    var $size = ($bar.data('size') !== undefined) ? $bar.data('size') : 150;
    var $thickness = ($bar.data('thickness') !== undefined) ? $bar.data('thickness') : 10;
    var $start = ($bar.data('start') !== undefined) ? $bar.data('start') : 300;
    $('.circle-progressbar').waypoint(function() {
      $('.circle-progressbar').circleProgress ({
        size: $size,
        fill: {
          color: $color
        },
        thickness: $thickness,
        emptyFill: $fill,
        startAngle: $start,
        reverse: $reverse,
        lineCap: 'round',
        animation: {
          duration: 1800
        }
      })
      .on('circle-animation-progress', function (event, progress, stepValue) {
        $(this).find('.circle-counter').text((stepValue * 100).toFixed(0));
      });
    },
    {
      offset: '100%',
      triggerOnce: true,
    });
  });

});
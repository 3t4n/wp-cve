(function ($) {
    'use strict';
    $(document).ready(function ($) {
      console.log(soc_args);
        var owl = $(".owl-carousel");
        var navigation = (soc_args.single_item == 'navigation') ? false : true;
        var items = soc_args.items;
        var single_item = (soc_args.single_item == 'false') ? false : true;
        var slide_speed = soc_args.slide_speed;
        var lazy_load = (soc_args.lazy_load == 'false') ? false : true;
        var auto_height = (soc_args.auto_height == 'false') ? false : true;
        var auto_play = (soc_args.auto_play == 'false') ? false : true;
        var autoplay_hover_pause = (soc_args.autoplay_hover_pause == 'false') ? false : true;
        var autoplay_timeout = soc_args.autoplay_timeout;
        owl.owlCarousel({
            // Most important owl features
            items: items,
            loop: true,
            singleItem: single_item,
            itemsScaleUp: true,
            // Basic Speeds
            smartSpeed: slide_speed,

            // Navigation
            navigation: navigation,
            //dots: true,

            // Lazy load
            lazyLoad : lazy_load,

            // Auto height
            autoHeight: auto_height,
            // Auto Play
            autoplay: auto_play,
            autoplayTimeout: autoplay_timeout,
            autoplayHoverPause: autoplay_hover_pause,
            responsiveClass:true,
            responsive:{
              0:{
                  items:1,
                  nav:true
              },
              600:{
                  items:2,
                  nav:false
              },
              1000:{
                  items:2,
                  nav:true,
                  loop:false
              }
          }

        });

    });

    /*$(document).ready(function($) {
              var owl = $('#owl-carousel-test');
              owl.owlCarousel({
                items: 4,
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 1000,
                autoplayHoverPause: true
              });
              $('.play').on('click', function() {
                owl.trigger('play.owl.autoplay', [1000])
              })
              $('.stop').on('click', function() {
                owl.trigger('stop.owl.autoplay')
              })
            });*/
})(jQuery);

;(function($) {
          
    /*-----------------------------
         SLICK CAROUSEL HANDLER
     ------------------------------*/
    
     var Slick_Carousel_Script_Handle = function($scope, $) {
 
         var carousel_elem = $scope.find('.element-ready-carousel-activation');
    
         if (carousel_elem.length > 0) {
 
             var settings = carousel_elem.data('settings');
             var slideid = settings['slideid'];
             var arrows = settings['arrows'];
             var arrow_prev_txt = settings['arrow_prev_txt'];
             var arrow_next_txt = settings['arrow_next_txt'];
             var dots = settings['dots'];
             var autoplay = settings['autoplay'];
             var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
             var animation_speed = parseInt(settings['animation_speed']) || 300;
             var pause_on_hover = settings['pause_on_hover'];
             var center_mode = settings['center_mode'];
             var center_padding = settings['center_padding'] ? settings['center_padding'] + 'px' : '50px';
             var rows = settings['rows'] ? parseInt(settings['rows']) : 0;
             var fade = settings['fade'];
             var focusonselect = settings['focusonselect'];
             var vertical = settings['vertical'];
             var infinite = settings['infinite'];
             var rtl = settings['rtl'];
             var display_columns = parseInt(settings['display_columns']) || 1;
             var scroll_columns = parseInt(settings['scroll_columns']) || 1;
             var tablet_width = parseInt(settings['tablet_width']) || 800;
             var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 1;
 
             var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 1;
             var mobile_width = parseInt(settings['mobile_width']) || 480;
             var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
             var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;
             var carousel_style_ck = parseInt(settings['carousel_style_ck']) || 1;
             var center_teblet_padding = settings['center_padding'] ? settings['center_padding'] + 'px' : '50px';
 
             if (settings['center_teblet_padding'] !== undefined) {
                 center_teblet_padding = settings['center_teblet_padding'] ? settings['center_teblet_padding'] + 'px' : '50px';
 
             }
 
             if (carousel_style_ck == 4) {
                 carousel_elem.slick({
                    mobileFirst: true,
                     appendArrows: '.element-ready-carousel-nav' + slideid,
                     appendDots: '.element-ready-carousel-dots' + slideid,
                     arrows: arrows,
                     prevArrow: '<div class="element-ready-carosul-prev owl-prev"><i class="' + arrow_prev_txt + '"></i></div>',
                     nextArrow: '<div class="element-ready-carosul-next owl-next"><i class="' + arrow_next_txt + '"></i></div>',
                     dots: dots,
                     customPaging: function(slick, index) {
                         var data_title = slick.$slides.eq(index).find('.element-ready-data-title').data('title');
                         return '<h6>' + data_title + '</h6>';
                     },
                     infinite: infinite,
                     autoplay: autoplay,
                     autoplaySpeed: autoplay_speed,
                     speed: animation_speed,
                     rows: rows,
                     fade: fade,
                     focusOnSelect: focusonselect,
                     vertical: vertical,
                     rtl: rtl,
                     pauseOnHover: pause_on_hover,
                     slidesToShow: display_columns,
                     slidesToScroll: scroll_columns,
                     centerMode: center_mode,
                     centerPadding: center_padding,
                     responsive: [{
                             breakpoint: tablet_width,
                             settings: {
                                 slidesToShow: tablet_display_columns,
                                 slidesToScroll: tablet_scroll_columns,
                                 centerPadding: center_template_padding,
                             }
                         },
                         {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: display_columns,
                                slidesToScroll: scroll_columns
                            }
                         },
                         {
                             breakpoint: mobile_width,
                             settings: {
                                 slidesToShow: mobile_display_columns,
                                 slidesToScroll: mobile_scroll_columns
                             }
                         }
                     ]
                 });
             } else {
              
                 carousel_elem.slick({
                    mobileFirst: true,
                     appendArrows: '.element-ready-carousel-nav' + slideid,
                     appendDots: '.element-ready-carousel-dots' + slideid,
                     arrows: arrows,
                     prevArrow: '<div class="element-ready-carosul-prev owl-prev"><i class="' + arrow_prev_txt + '"></i></div>',
                     nextArrow: '<div class="element-ready-carosul-next owl-next"><i class="' + arrow_next_txt + '"></i></div>',
                     dots: dots,
                     infinite: infinite,
                     autoplay: autoplay,
                     autoplaySpeed: autoplay_speed,
                     speed: animation_speed,
                     rows: rows,
                     fade: fade,
                     focusOnSelect: focusonselect,
                     vertical: vertical,
                     rtl: rtl,
                     pauseOnHover: pause_on_hover,
                     slidesToShow: display_columns,
                     slidesToScroll: scroll_columns,
                     centerMode: center_mode,
                     centerPadding: center_padding,
                     responsive: [{
                             breakpoint: tablet_width,
                             settings: {
                                 slidesToShow: tablet_display_columns,
                                 slidesToScroll: tablet_scroll_columns
                             }
                         },
                         {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: display_columns,
                                slidesToScroll: scroll_columns
                            }
                         },
                         {
                             breakpoint: mobile_width,
                             settings: {
                                 slidesToShow: mobile_display_columns,
                                 slidesToScroll: mobile_scroll_columns
                             }
                         }
                     ]
 
                 });
             }
         }
     }
 
     
     $(window).on('elementor/frontend/init', function() {
 
         elementorFrontend.hooks.addAction('frontend/element_ready/er_content_sldier_widget.default', Slick_Carousel_Script_Handle);

     });
 
 })(jQuery);
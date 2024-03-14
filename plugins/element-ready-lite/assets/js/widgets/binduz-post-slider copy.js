(function ($) {

    var Binduz_Er_Pro_Post_Slider = function($scope, $){ 

        var $container = $scope.find('.binduz-er-featured-slider-item');

         if($container.length){

             var $slider_controls = $container.data('slide-controls');
             var slider_enable    = Boolean($slider_controls.slider_enable);
             var autoplay         = Boolean($slider_controls.autoplay);
             var autoplaySpeed    = parseInt( $slider_controls.autoplaySpeed );
             var slide_speed      = parseInt($slider_controls.slide_speed) ;
             var show_nav         = Boolean( $slider_controls.show_nav );
             var slidesToShow     = parseInt($slider_controls.slidesToShow);
             var slide_padding     = parseInt($slider_controls.slide_padding);
             var right_icon       = $slider_controls.right_icon;
             var left_icon        = $slider_controls.left_icon;
 
             if(slider_enable){

                    $container.slick({
                    dots          : false,
                    infinite      : true,
                    autoplay      : autoplay,
                    autoplaySpeed : autoplaySpeed,
                    arrows        : show_nav,
                    prevArrow     : '<span class="prev"><i class="'+left_icon+'"></i></span>',
                    nextArrow     : '<span class="next"><i class="'+right_icon+'"></i></span>',
                    speed         : slide_speed,
                    slidesToShow  : slidesToShow,
                    centerPadding : slide_padding+'PX',
                    slidesToScroll: 1,
                    centerMode: true,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: 1,
                            }
                    },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 1,
                            }
                    },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                            }
                    },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                            }
                    }
                    ]
                }); 

            }
        } 

        var $slider_box            = $scope.find('.binduz-er-news-slider-box');
        if($slider_box.length){

             var $slider_controls = $slider_box.data('slide-controls');
             var slider_enable    = Boolean($slider_controls.slider_enable);
             var autoplay         = Boolean($slider_controls.autoplay);
             var autoplaySpeed    = parseInt( $slider_controls.autoplaySpeed );
             var slide_speed      = parseInt($slider_controls.slide_speed) ;
             var show_nav         = Boolean( $slider_controls.show_nav );
             var slidesToShow     = parseInt($slider_controls.slidesToShow);
             var right_icon       = $slider_controls.right_icon;
             var left_icon        = $slider_controls.left_icon;

                if(slider_enable){
                    var news_slider1 = $('.binduz-er-news-slider-item');
                    news_slider1.slick({
                        slidesToShow: slidesToShow,
                        slidesToScroll: 1,
                        speed         : slide_speed,
                        slidesToShow  : slidesToShow,
                        arrows: show_nav,
                        prevArrow: '<span class="prev"><i class="'+left_icon+'"></i></span>',
                        nextArrow: '<span class="next"><i class="'+right_icon+'"></i></span>',
                        fade: true,
                        centerMode: true,
                        asNavFor: '.binduz-er-news-slider-content-slider'
                    });
                    var news_slider2 = $('.binduz-er-news-slider-content-slider');
                    news_slider2.slick({
                        slidesToShow: slidesToShow,
                        slidesToScroll: 1,
                        asNavFor: '.binduz-er-news-slider-item',
                        dots: false,
                        centerMode: true,
                        arrows: false,
                        prevArrow: '<span class="prev"><i class="'+left_icon+'"></i> Prev</span>',
                        nextArrow: '<span class="next">Next <i class="'+right_icon+'"></i></span>',
                        centerPadding: "0",
                        focusOnSelect: true,
            
                    });
                }
        }

            //===== Service Active slick slider        
       var $most_viewed_box            = $scope.find('.binduz-er-news-viewed-most-slide');

       if($most_viewed_box.length){
            
             
             var $slider_controlsbox = $most_viewed_box.data('slide-controls');
             var slider_enable            = Boolean($slider_controlsbox.slider_enable);
             var autoplay            = Boolean($slider_controlsbox.autoplay);
             var autoplaySpeed       = parseInt( $slider_controlsbox.autoplaySpeed );
             var slide_speed         = parseInt($slider_controlsbox.slide_speed) ;
             var show_nav            = Boolean( $slider_controlsbox.show_nav );
             var slidesToShow        = parseInt($slider_controlsbox.slidesToShow);
             var right_icon          = $slider_controlsbox.right_icon;
             var left_icon           = $slider_controlsbox.left_icon;
             
             if(slider_enable){
                    $most_viewed_box.slick({
                    dots: false,
                    infinite: true,
                    autoplay: autoplay,
                    autoplaySpeed: autoplaySpeed,
                    arrows: show_nav,
                    prevArrow: '<span class="prev"><i class="'+left_icon+'"></i></span>',
                    nextArrow: '<span class="next"><i class="'+right_icon+'"></i></span>',
                    speed: slide_speed,
                    slidesToShow: slidesToShow,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                slidesToShow: slidesToShow,
                                arrows: false,
                            }
                    }
                    ]

                });
            }
       }

       //===== Service Active slick slider        
       var $topbar_headline = $scope.find('.binduz-er-news-slider-2-item');

       if($topbar_headline.length){
                 
         var $slider_csheadline = $topbar_headline.data('slide-controls');
         
         var slider_enable            = Boolean($slider_csheadline.slider_enable);
         var autoplay            = Boolean($slider_csheadline.autoplay);
         var autoplaySpeed       = parseInt( $slider_csheadline.autoplaySpeed );
         var slide_speed         = parseInt($slider_csheadline.slide_speed) ;
         var show_nav            = Boolean( $slider_csheadline.show_nav );
         var slidesToShow        = parseInt($slider_csheadline.slidesToShow);
         var slide_padding        = parseInt($slider_csheadline.slide_padding);
         var right_icon          = $slider_csheadline.right_icon;
         var left_icon           = $slider_csheadline.left_icon;
         
         if(slider_enable){
   
            $topbar_headline.slick({
                dots          : false,
                infinite      : true,
                autoplay      : autoplay,
                autoplaySpeed : autoplaySpeed,
                arrows        : show_nav,
                prevArrow     : '<span class="prev"><i class="'+left_icon+'"></i></span>',
                nextArrow     : '<span class="next"><i class="'+right_icon+'"></i></span>',
                speed         : slide_speed,
                slidesToShow  : slidesToShow,
                slidesToScroll: 1,
                centerMode    : true,
                centerPadding : slide_padding+'PX',
                responsive: [
                    {
                        breakpoint: 1201,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                        }
                },
                    {
                        breakpoint: 992,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                        }
                },
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                            centerPadding: "30px",
                        }
                },
                    {
                        breakpoint: 576,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                            centerPadding: "0px",
                        }
                },
            ]

            });
         }
   
       }

        
       //=====  Active slick slider        
       var $featured_slider = $scope.find('.binduz-er-featured-slider-2');
       if($featured_slider.length){

            var $slider_csheadline = $featured_slider.data('slide-controls');
            var autoplay            = Boolean($slider_csheadline.autoplay);
            var slider_enable       = Boolean($slider_csheadline.slider_enable);
            var autoplaySpeed       = parseInt( $slider_csheadline.autoplaySpeed );
            var slide_speed         = parseInt($slider_csheadline.slide_speed) ;
            var show_nav            = Boolean( $slider_csheadline.show_nav );
            var slidesToShow        = parseInt($slider_csheadline.slidesToShow);
            var slide_padding        = parseInt($slider_csheadline.slide_padding);
            var right_icon          = $slider_csheadline.right_icon;
            var left_icon           = $slider_csheadline.left_icon;
            
            if(slider_enable){

                $featured_slider.slick({
                    dots: false,
                    infinite: true,
                    autoplay: autoplay,
                    autoplaySpeed: autoplaySpeed,
                    arrows: show_nav,
                    prevArrow: '<span class="prev"><i class="'+left_icon+'"></i></span>',
                    nextArrow: '<span class="next"><i class="'+right_icon+'"></i></span>',
                    speed: slide_speed,
                    slidesToShow: slidesToShow,
                    slidesToScroll: 1,
                    responsive: [
                        {
                            breakpoint: 1201,
                            settings: {
                                arrows: false,
                            }
                    }
                ]

                });
                
            }

        }  

    }

	$(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/element-ready-pro-binduz-er-slider-post.default', Binduz_Er_Pro_Post_Slider );
       
    });
})(jQuery);
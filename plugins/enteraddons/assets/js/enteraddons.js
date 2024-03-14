/*---------------------------------------------
package:  Enteraddons
Version:        1.0.3
Author:         ThemeLooks
Author url:     http://themelooks.com

----------------------------------------------*/

(function ($, w ) {
    "use strict";

    let $window = $(w);

    /*========================
        Accordion
    ==========================*/
    let enteraddonsAccordion = function( $scope, $ ) {
        
        /* 02: Accordion
        ==============================================*/
        let accordionToggle = $scope.find('[data-accordion-tab="toggle"]');
            accordionToggle.each(function () {
                // Initially hide all content sections
                $(this).children('.enteraddons-faq-content').hide();
                
                // Event listener for click on each accordion header
                $(this).on('click', function () {
                    let content = $(this).children('.enteraddons-faq-content');
                    
                    if ($(this).hasClass('active')) {
                        content.slideUp(500);
                        $(this).removeClass('active');
                    } else {
                        accordionToggle.children('.enteraddons-faq-content').slideUp(500);
                        accordionToggle.removeClass('active');
                        content.slideDown(500);
                        $(this).addClass('active');
                    }
                });

                if ($(this).hasClass('active')) {
                    $(this).children('.enteraddons-faq-content').show();
                }
            });
        
        /* 02: Accordion
        ==============================================*/

        let $menuParent = $scope.find('.menu-item-has-children > a');
        $menuParent.append('<i class="fas fa-angle-down"></i>');

    }

    /*========================
        isotope
    ==========================*/
    let enteraddonsIsotope = function( $scope, $ ) {
            
        let $selector = $scope.find('.grid');
        $(window).on('load', function () {

        $selector.isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            animationOptions: {
                duration: 750,
                easing: "linear",
                queue: false
            },
            masonry: {
                columnWidth: '.grid-item'
            }
        });

        });

    }

    /*========================
       Mailchimp Ajax
    ==========================*/
    let enteraddonsMailchimp = function( $scope, $ ) {
        
        let $formSubmit = $scope.find('.enteraddons-newsletter-form');

        $formSubmit.on( 'submit', function(e) {

            e.preventDefault();

            let $t          = $(e.target),
                $getEmail   = $t.find('.newsletter-email-input').val(),
                $listId     = $t.data('list-id');

            $.ajax({
                
                type: 'POST',
                url: enteraddonsMainObject.ajax_url,
                data: {
                  email: $getEmail,
                  list_id: $listId,
                  action: 'mailchimp_action_fire'
                },
                success: function( data ){
                    let $r = JSON.parse( data );

                    $(".enteraddons-newsletter-response").append('<div class="alert alert-'+$r.type+'">'+$r.msg+'</div>');
                    $(".enteraddons-newsletter-response .alert").delay(3000).fadeOut('300',function() {
                        $(this).remove();
                    });
                }
            });
          
            return false;


        } )

    }

    let enteraddonsVideoButtonPrettyPhoto = function( $scope, $ ) {

        let magnificPopup = $scope.find("a[rel^='magnificPopup']");
            magnificPopup.magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });
    }

    /*========================
        Counter Up
    ==========================*/
    let enteraddonsCounterUp = function( $scope, $ ) {
        
        let $counter = $scope.find('.enteraddons-count');
        
        $counter.counterUp({
            delay: 10,
            time: 1000
        });

    }

    /*========================
    XX: Processbar
    ==========================*/
    let enteraddonsProcessbar = function( $scope, $ ) {

        let processBarWrapper = $scope.find('.enteraddons-process-bar-wrapper');

        if (processBarWrapper.length) {

            function processControl() {
                processBarWrapper.each(function () {

                    let processBarOffset = $(this).offset().top - $(window).height(),
                        processTime = '2.25s', 
                        processBarWidth = $(this).children('[data-process-width]').data('process-width');

                    if ($(window).scrollTop() > processBarOffset) {
                        if (processBarWidth > 100) {
                            $(this).children('.process-bar').css({
                                'width': '100%',
                                'transition': processTime
                            });
                        } else {
                            $(this).children('.process-bar').css({
                                'width': processBarWidth + '%',
                                'transition': processTime
                            });
                        }
                    }
                })
            }
         
            processControl();
            
            $(window).on( 'scroll', function () {
                processControl();
            });
        }
    }

    /*========================
    XX: owl Carousel
    ==========================*/

    let checkData = function (data, value) {
        return typeof data === 'undefined' ? value : data;
    };

    let nextImgNav =  function( $scope, $ ) {
        var a = $scope.find('.next-img-nav'),
            b = a.find('.owl-item.active').prev().find('.enteraddons-image-nav img').attr('src'),
            c = a.find('.owl-item.active').next().find('.enteraddons-image-nav img').attr('src');

        a.find('.owl-nav button').append('<img src=""/>');

        a.find('.owl-nav button.owl-prev img').attr('src', b);
        a.find('.owl-nav button.owl-next img').attr('src', c);

        a.on('translated.owl.carousel', function(e) {
            var b = $(e.currentTarget).find('.owl-item.active').prev().find('.enteraddons-image-nav img').attr('src'),
            c = $(e.currentTarget).find('.owl-item.active').next().find('.enteraddons-image-nav img').attr('src');

            $(e.currentTarget).find('.owl-nav button.owl-prev img').attr('src', b);
            $(e.currentTarget).find('.owl-nav button.owl-next img').attr('src', c);
        })
    };


    let enteraddonsTestimonial = function( $scope, $ ) {
        
        let $owlCarousel = $scope.find('.owl-carousel');

        $owlCarousel.each( function () {
            let $t = $(this),
                $settings = $t.data('slidersettings'),
                $imgDots = $t.find('.imgDots'),
                $isImgDots = '';

                if( $imgDots.length ) {
                    $isImgDots = '.imgDots';
                }
                
            $t.owlCarousel({
                items: checkData( $settings.items, 1),
                margin: checkData($settings.margin, 0),
                loop: checkData($settings.loop, true),
                smartSpeed: checkData($settings.smartSpeed, 450),
                autoplay: checkData($settings.autoplay, true),
                autoplayTimeout: checkData($settings.autoplayTimeout, 8000),
                center: checkData( $settings.center, false),
                animateIn: checkData($settings.animateIn, false),
                animateOut: checkData($settings.animateOut, false),
                nav: checkData($settings.nav, false),
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                dots: checkData($settings.dots, false),
                mouseDrag: checkData($settings.mouseDrag, true),
                responsive: checkData($settings.responsive, {}),
                autoWidth: checkData($settings.autoWidth, false),
                dotsContainer: $isImgDots
            });
        });


        //

        let etestiOne = $scope.find('.enteraddons-testimonial-slider.slider-style--one'),
        ethumbs = $scope.find('.enteraddons-testimonial-thumb-slider');

        // Testimonial
        etestiOne.on('changed.owl.carousel', function (e) {

            var tIndx = $(e.target).find('.owl-item').eq(e.item.index).children('.enteraddons-single-testimonial.testimonial-style--one').data('id'), tIndx = tIndx - 1;

            ethumbs.trigger( 'to.owl.carousel', [tIndx, 500, true] );
        });

        // Thumbnail
        ethumbs.on('click', '.owl-item', function (e) {
            var tIndx = $(e.currentTarget).children('.single-thumb').data('id') - 1;

            etestiOne.trigger('to.owl.carousel', [tIndx, 500, true]);
        }).on('changed.owl.carousel', function (e) {
            var tIndx = $(e.target).find('.owl-item').eq(e.item.index).children('.single-thumb').data('id'), tIndx = tIndx - 1;
            etestiOne.trigger('to.owl.carousel', [tIndx, 500, true]);
        });

        
        /*========================
        XX: Nav Dynamic Image
        ==========================*/

        nextImgNav( $scope, $ );

        //Image Dots Slider
        $scope.find('.imgDotsTrigger').on('click', '.img_dot', function (e) {
            $scope.find('.imgDotsSliderTarget').trigger('to.owl.carousel', [$(this).index(), 300]);
        });


    }

    let enteraddonsImageCompare = function( $scope, $ ) {

        $(function(){
        
            let s = $scope.find(".cd-image-container"),
                o = s.data('orientation'),
                ot = s.data('original-text'),
                mt = s.data('modified-text');

            s.twentytwenty({
                orientation: o, // Orientation of the before and after images ('horizontal' or 'vertical')
                before_label: ot, // Set a custom before label
                after_label: mt, // Set a custom after label
            });

        });

    }

    /*========================
        Pricing Table Tab
    ==========================*/

    let enteraddonsPricingTableTab = function( $scope, $ ) {

        let $yearly = $scope.find( '.yearly-price .single-price' ),
            $monthly = $scope.find( '.monthly-price .single-price' ),
            $yearlyPrice = $scope.find( '.yearly-price' ),
            $monthlyPrice = $scope.find( '.monthly-price' ),
            $switchWrap = $scope.find( '.switch-wrap' ),
            $switchSingleTab = $scope.find( '.switch-single-tab' );

        $yearly.on('mouseenter', function() {
            $yearly.removeClass('active');
            $(this).addClass('active');
        });

        $monthly.on('mouseenter', function() {
            $monthly.removeClass('active');
            $(this).addClass('active');
        });

        $switchWrap.on('click', function() {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                $monthlyPrice.removeClass('show');
                $yearlyPrice.addClass('show');
            } else {
                $yearlyPrice.removeClass('show');
                $monthlyPrice.addClass('show');
            }
        });
        //
        $switchSingleTab.on('click', function() {
            $scope.find('.single-active').removeClass('single-active');
            $(this).addClass('single-active');
        });

    }

    /*========================
        Countdown Timer
    ==========================*/

    let enteraddonsCountdownTimer = function( $scope, $ ) {

        let s = $scope.find('.star-countdown-timer'),
            dateTime = s.data('date-time'),
            $textDays = s.data('days'),
            $textHour  = s.data('hour'),
            $textMin  = s.data('min'),
            $textSec  = s.data('sec');

        // Set the date we're counting down to
        var countDownDate = new Date(dateTime).getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

          // Get today's date and time
          var now = new Date().getTime();

          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations for days, hours, minutes and seconds
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));

          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Display the result in the element with id="demo"
          let innerHTML = '<span class="clock-single-item"><span class="number">'+days + '</span><span class="clock-text">'+$textDays+' </span></span><span class="clock-single-item"><span class="number">' + hours + '</span><span class="clock-text">'+$textHour+'</span> </span><span class="clock-single-item"><span class="number">'
          + minutes + '</span><span class="clock-text">'+$textMin+' </span></span><span class="clock-single-item"><span class="number">' + seconds + '</span><span class="clock-text">'+$textSec+'</span></span> ';

          s.html(innerHTML);

          // If the count down is finished, write some text
          if (distance < 0) {
            clearInterval(x);
            s.html("EXPIRED");
          }
        }, 1000);

    }

    /*========================
        Google Map
    ==========================*/
    let enteraddonsGoogleMap = function( $scope, $ ) {

        let $map = $scope.find('[data-trigger="ea-g-map"]');

            if ($map.length) {
                
                // Map Initialization
                window.initMap = function () {
                    $map.each( function () {

                        let $t = $(this), map, lat, lng, zoom;

                        let $mapOps = $t.data('options'),
                        $mapmarker = $mapOps.marker;
                        lat  = parseFloat( $mapOps.latitude );
                        lng  = parseFloat( $mapOps.longitude );
                        zoom = parseFloat( $mapOps.zoom );


                        map = new google.maps.Map($t[0], {
                            center: { lat: lat, lng: lng },
                            zoom: zoom,
                            scrollwheel: false,
                            disableDefaultUI: true,
                            zoomControl: true,
                            styles: [
                                {
                                    "featureType": "water",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#e9e9e9"
                                        },
                                        {
                                            "lightness": 17
                                        }
                                    ]
                                },
                                {
                                    "featureType": "landscape",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#f5f5f5"
                                        },
                                        {
                                            "lightness": 20
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.highway",
                                    "elementType": "geometry.fill",
                                    "stylers": [
                                        {
                                            "color": "#ffffff"
                                        },
                                        {
                                            "lightness": 17
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.highway",
                                    "elementType": "geometry.stroke",
                                    "stylers": [
                                        {
                                            "color": "#ffffff"
                                        },
                                        {
                                            "lightness": 29
                                        },
                                        {
                                            "weight": 0.2
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.arterial",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#ffffff"
                                        },
                                        {
                                            "lightness": 18
                                        }
                                    ]
                                },
                                {
                                    "featureType": "road.local",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#ffffff"
                                        },
                                        {
                                            "lightness": 16
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#f5f5f5"
                                        },
                                        {
                                            "lightness": 21
                                        }
                                    ]
                                },
                                {
                                    "featureType": "poi.park",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#dedede"
                                        },
                                        {
                                            "lightness": 21
                                        }
                                    ]
                                },
                                {
                                    "elementType": "labels.text.stroke",
                                    "stylers": [
                                        {
                                            "visibility": "on"
                                        },
                                        {
                                            "color": "#ffffff"
                                        },
                                        {
                                            "lightness": 16
                                        }
                                    ]
                                },
                                {
                                    "elementType": "labels.text.fill",
                                    "stylers": [
                                        {
                                            "saturation": 36
                                        },
                                        {
                                            "color": "#333333"
                                        },
                                        {
                                            "lightness": 40
                                        }
                                    ]
                                },
                                {
                                    "elementType": "labels.icon",
                                    "stylers": [
                                        {
                                            "visibility": "off"
                                        }
                                    ]
                                },
                                {
                                    "featureType": "transit",
                                    "elementType": "geometry",
                                    "stylers": [
                                        {
                                            "color": "#f2f2f2"
                                        },
                                        {
                                            "lightness": 19
                                        }
                                    ]
                                },
                                {
                                    "featureType": "administrative",
                                    "elementType": "geometry.fill",
                                    "stylers": [
                                        {
                                            "color": "#fefefe"
                                        },
                                        {
                                            "lightness": 20
                                        }
                                    ]
                                },
                                {
                                    "featureType": "administrative",
                                    "elementType": "geometry.stroke",
                                    "stylers": [
                                        {
                                            "color": "#fefefe"
                                        },
                                        {
                                            "lightness": 17
                                        },
                                        {
                                            "weight": 1.2
                                        }
                                    ]
                                }
                            ]
                        });

                        map = new google.maps.Marker({
                            position: { lat: lat, lng: lng },
                            map: map,
                            animation: google.maps.Animation.DROP,
                            draggable: false,
                            icon: $mapmarker
                        });

                    });
                };
                initMap();
            } // End if
    }

    /*========================
        Image Zoom Magnifier
    ==========================*/
    let enteraddonsImageMagnifier = function ( $scope, $ ) {
        $scope.find(".ea-img-zoom").BUP(null, 1);
    };

    /*========================
      Content Ticker
    ==========================*/

    let enteraddonsContentTicker = function ( $scope, $ ) {
        
        let $settings = $scope.find("[data-tickersettings]").data("tickersettings");

        $scope.find(".enteraddons-news-ticker").AcmeTicker({
          type: checkData($settings.type, "typewriter"),
          direction: checkData($settings.direction, "right"),
          speed: checkData($settings.speed, 50),
          autoplay: checkData($settings.autoplay, 2000),
          pauseOnFocus: checkData($settings.focus, true),
          pauseOnHover: checkData($settings.hover, true),
          controls: {
            prev: $(".enteraddons-news-ticker-prev"),
            toggle: $(".enteraddons-news-ticker-pause"),
            next: $(".enteraddons-news-ticker-next"),
          },
        });


    };

    /*========================
       Title Reveal Animation
    ==========================*/
    let enteraddonsRevealAnimation = function ($scope, $) {
        $scope.find(".ea-reveal-animation").each(function (index) {
          $(this)
            .delay($(this).data("delay"))
            .queue(function () {
              $(this).addClass("eaatanimate-in");
            });
        });
    };


    /*========================
      Photo Reveal Animation
    ==========================*/
    let enteraddonsPhotoRevealAnimation = function ($scope, $) {
        $scope.find(".eaam-has-animation").each(function (index) {
          $(this)
            .delay($(this).data("delay"))
            .queue(function () {
              $(this).addClass("eaam-animate-in");
            });
        });
    };

    /*========================
     Image Hotspot
    ==========================*/

    let enteraddonsImageHotspot = function ($scope, $) {
        let $settings = $scope.find("[data-hotspotsettings]").data("hotspotsettings");
        $scope.find('.ea-image-hotspot').hotSpot({
            bindselector: checkData($settings.type,'click'),
        });
    };

    /*=========================
        Vertical Testimonial
    ===========================*/

    let enteraddonsVerticalTestimonial = function( $scope, $ ) {

        let $id = $scope.find( "[data-slider-id]" ).data( "slider-id" );

        let $mainSelector = $scope.find('#'+$id),
            $getSettings = $mainSelector.data('vertical-slider-settings');

        new Swiper( $mainSelector, {

            slideToClickedSlide: checkData( $getSettings.clicked_slide, true ),
            direction: 'vertical',
            centeredSlides: checkData( $getSettings.centered_slides, true ),
            slidesPerView: checkData( $getSettings.slides_to_show, true ),
            mousewheel: checkData( $getSettings.mousewheel_control, true ),
            autoplay:checkData( $getSettings.autoplay, true ),
            speed: checkData( $getSettings.auto_play_speed, 5000 ),
            loop: checkData( $getSettings.loop, true ),
            navigation: {
                nextEl: '.ea--swiper-button-next',
                prevEl: '.ea--swiper-button-prev',
            },
            breakpoints: {

                991: {
                    autoHeight: true,
                    grabCursor: true,
                    slidesPerView: 1
                },

                767: {
                    autoHeight: true,
                    grabCursor: true,
                    slidesPerView: 1,
                },

                575: {
                    autoHeight: true,
                    grabCursor: true,
                    slidesPerView: 1
                }

            }

        });

    }

    /*=========================
        Nav Menu Hamburger
    ===========================*/

    let enteraddonsNavMenuHamburger = function( $scope, $ ) {

        //hamburger-menu
        $scope.find( '.ea-hamburger-menu' ).on("click", function (e) {
            e.preventDefault();

            let $t = $(this);
            $t.toggleClass('hamburger-active');
            $t.closest(".ea-nav-menu-wrapper").find('.ea-hamburger-nav-wrap').slideToggle('fast').toggleClass("ea-open");

        });

        // Hide mobile menu On window resize
        $(window).on("resize", function() {
            if( $(window).width() > 1199 ) {
                $('.hamburger-active').removeClass('hamburger-active');
                $scope.find('.ea-hamburger-nav-wrap').hide().removeClass("ea-open");
            } 

        })

        //hamburger sub menu
        $scope.find(".menu-item-has-children").prepend('<span class="ea-submenu-button"></span>');
        $scope.find(".ea-hamburger-nav-wrap .ea-submenu-button").on("click", function () {
            let $t = $(this);
            $t.toggleClass("ea-submenu-opened");
            $t.siblings("ul").slideToggle().toggleClass("ea-open");
        });

    }

    /*=========================
        Nav Search
    ===========================*/

    let enteraddonsNavSearch = function( $scope, $ ) {

        $scope.find("[open-modal]").on( "click", function () {
          var id = $(this).attr("open-modal");
          $scope.find(".ea-search-modal#" + id).addClass("active");
        });

        $scope.find("[close-modal]").on("click", function () {
          $(this).parents(".ea-search-modal").removeClass("active");
        });

        $scope.find(".ea-search-modal").on("click", function (e) {
          if (e.target !== this) {
            return;
          }
          $(this).removeClass("active");

        });

    }

    /*=========================
        Coupon Code
    ===========================*/

    let enteraddonsCouponCode = function( $scope, $ ) {

        $scope.find(".ea-get-code").on( "click", function () {
            let $t = $(this),
                $e = $t.data('target');
                
                //
                if( $e == '4' ) {
                    let $c = $t.data('code');
                    // Add active class
                    $t.addClass('active');
                    // Copy the text inside the text field
                    navigator.clipboard.writeText($c);
                    $t.find('.ea-coupon--code').text( $t.data('copied') );

                    setTimeout(function() {
                        $t.find('.ea-coupon--code').text( $t.data('code') );
                    }, 2000);

                } else {
                    let $c = $t.closest('.ea-coupon-code-inner').find('.ea-coupon-code').text();
                    $t.text( $t.data('copied') );
                    // Copy the text inside the text field
                    navigator.clipboard.writeText($c);
                }

        });

    }

    /*=========================
        Color Scheme
    ===========================*/

    let enteraddonsColorScheme = function( $scope, $ ) {

        $scope.find(".ea-bg-color").on( "click", function () {
            let $t = $(this),
                $codeSelector = $t.find('.ea-color-value'),
                $code = $codeSelector.text(),
                $copiedText = $t.find('.copied-text');
                // Copy the text inside the text field
                navigator.clipboard.writeText($code);
                //
                $copiedText.fadeIn();
                
                setTimeout( function() {
                   $copiedText.fadeOut();
                }, 4000);

        });
        
    }

    /*========================
        Accordion Gallery
    ==========================*/

    let enteraddonsAccordionGallery = function ( $scope, $ ) {

      $scope.find('.ea-gallery-toggle').hide();
        $scope.find('.ea-accordion-gallery .ea-gallery-item').each(function() {
          $(this).find('.ea-vg-btn').click(function(e) {
            e.preventDefault();
            $('.ea-gallery-toggle').slideUp('normal');
            if($(this).next().is(':hidden') == true) {
              $(this).addClass('on');
              $(this).next().slideDown('normal');
            } 
          }); 
        });
     
    };

    /*========================
        Travel Gallery
    ==========================*/

    let enteraddonsTravelGallery = function ( $scope, $ ) {

        let $slides, $btnArr;

        function onClick(e) {
          let $target = $(e.target);
          if ($target.hasClass('ea-tg-slide') && !$target.hasClass('active') && !$target.siblings().hasClass('active')) {
            $target.removeClass('ea-tg-anim-in last-viewed').addClass('active')
            $target.siblings().removeClass('ea-tg-anim-in last-viewed').addClass('anim-out');
          }
        }
        //
        function closeSlide(e) {
          let $slide = $(e.target).parent();
          $slide.removeClass('active ea-tg-anim-in').addClass('last-viewed');
          $slide.siblings().removeClass('anim-out').addClass('ea-tg-anim-in');
        }

        $slides = $('.ea-tg-slide');
        $btnArr = $slides.find('.ea-tg-btn-close');
        $slides.on('click', onClick);
        $btnArr.on('click', closeSlide);

    };

    /*========================
       Typing Animation
    ==========================*/

    let enteraddonsTypingAnimation = function ( $scope, $ ) {
      let $settings = $scope.find( "[data-typing-settings]" ).data( "typing-settings" );

        $scope.find( ".ea-typed" ).typed( {
          strings:  checkData( $settings.animationText,'' ) ,
          typeSpeed:  checkData( $settings.TypeSpeed,100 ),
          startDelay: checkData( $settings.StartDelay,0 ),
          backSpeed: checkData( $settings.BackSpeed,60 ),
          backDelay: checkData( $settings.BackDelay,2000 ),
          loop: checkData( $settings.Loop,true ),
          cursorChar: "|",
          contentType: 'html'
        });
       
    };

    /*========================
      Offcanvas Nav
    ==========================*/
    let enteraddonsNavOffcanvas = function ( $scope, $ ) {
        
        $scope.find(".menu-item-has-children").prepend('<span class="ea-submenu-button fa"></span>');
        $scope.find('[data-toggle="offCanvas"]').on('click', function(e) {
            var target = '#' + $(this).data('target');

            if($(target).hasClass('open')) {
                closeOffCanvas(target);
            } else if (openOffCanvas(target)) {
                e.preventDefault();
            }

        })

        $scope.find('.offcanvas-close').on('click', function (e) {
            e.preventDefault();
            closeOffCanvas(this);
        });
        
        $scope.find('.menu-item-has-children > .ea-submenu-button').on("click", function (e) {
           
            let $t = $(this);
            $t.toggleClass("ea-submenu-opened");
            $t.siblings("ul").slideToggle().toggleClass("ea-open");
        });
        
    };

    // Open OffCanvas
    function openOffCanvas(target) {

        var $target = $(target);

        if (!$target.length) {
            return false;
        }

        $target.fadeIn();
        $target.addClass('open');

        $(document.body).addClass('offcanvas-opened ' + $target.attr('id') + '-opened');

        return true;
    }

    // Close OffCanvas
    function closeOffCanvas(target) {

        if (!target) {
            $('.ea-offcanvas-nav-panel').each( function () {
                var $panel = $(this);

                if ( !$panel.hasClass('open') ) {
                    return;
                }

                $panel.removeClass('open').fadeOut();
                $(document.body).removeClass($panel.attr('id') + '-opened');
            });
        } else {
            target = $(target).closest('.ea-offcanvas-nav-panel');
            target.removeClass('open').fadeOut();

            $(document.body).removeClass(target.attr('id') + '-opened');
        }

        $(document.body).removeClass('offcanvas-opened');
    }



    /*========================
      Advanced Tab
    ==========================*/
    let enteraddonsAdvancedTab = function ($scope, $) {

        $scope.find('.ea-atab-tab-link').on("click",function() {
          let tab_id = $(this).attr('data-tab');
          $scope.find('.current').removeClass('current');
          $(this).addClass('current');
          $("#"+tab_id).addClass('current');
          
        })
      
    };

    /*=============================
      Advanced Animation Title
    ==============================*/
    let enteraddonsAdvancedAnimationTitle = function ($scope, $) {

        let $settings = $scope.find( "[data-titleanimation-settings]" ).data( "titleanimation-settings" );
        let id = $scope.find( "[data-title-id]" ).data( "title-id" );

        var textWrapper = $scope.find('.ea-aat-text');
        textWrapper.attr('id', 'ea-aat-text-' + id); // Add a unique ID
        textWrapper.html(textWrapper.text().replace(/\S/g, "<span class='ea-aat-letter'>$&</span>"));
        
        let $t;
        switch($settings.animation) {
        case '1':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            scale: [4,1],
            opacity: [0,1],
            translateZ: 0,
            easing: "easeOutExpo",
            duration: 950,
            delay: (el, i) => 70*i
            }
            break;
        case '2':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            opacity: [0,1],
            easing: "easeInOutQuad",
            duration: 2250,
            delay: (el, i) => 150 * (i+1)
            }
            break;
        case '3':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            translateY: ["1.1em", 0],
            translateZ: 0,
            duration: 750,
            delay: (el, i) => 50 * i
            }
            break;
        case '4':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            translateY: ["1.1em", 0],
            translateX: ["0.55em", 0],
            translateZ: 0,
            rotateZ: [180, 0],
            duration: 750,
            easing: "easeOutExpo",
            delay: (el, i) => 50 * i
            }
            break;
        case '5':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            rotateY: [-90, 0],
            duration: 1300,
            delay: (el, i) => 45 * i
            }
            break;
        case '6':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            scale: [14,1],
            opacity: [0,1],
            easing: "easeOutCirc",
            duration: 100,
            delay: (el, i) => 100 * i
            }
            break;
        case '7':
            $t = {
            targets: '#ea-aat-text-' + id + ' .ea-aat-letter',
            translateY: [-100,0],
            easing: "easeOutExpo",
            duration: 1400,
            delay: (el, i) => 30 * i
            }
            break;
        }
        anime.timeline( {loop: true} )
        .add( $t ).add( {
            targets: '#ea-aat-text-' + id,
            opacity: 0,
            duration: 1000,
            easing: "easeOutExpo",
            delay: 1000
        } );
    };


    /*========================
        Elementor Hooked
    ==========================*/

    let elementTypeSkin = {
        'enteraddons-accordion.default' : enteraddonsAccordion,
        'enteraddons-testimonial.default' : enteraddonsTestimonial,
        'enteraddons-logo-carousel.default' : enteraddonsTestimonial,
        'enteraddons-image-slider.default' : enteraddonsTestimonial,
        'enteraddons-card-carousel.default' : enteraddonsTestimonial,
        'enteraddons-product-carousel.default' : enteraddonsTestimonial,
        'enteraddons-nav-menu-offcanvas.default' : enteraddonsNavOffcanvas,
        'enteraddons-video-button.default' : enteraddonsVideoButtonPrettyPhoto,
        'enteraddons-progressbar.default' : enteraddonsProcessbar,
        'enteraddons-image-compare.default' : enteraddonsImageCompare,
        'enteraddons-pricing-table-tab.default' : enteraddonsPricingTableTab,
        'enteraddons-counter.default' : enteraddonsCounterUp,
        'enteraddons-newsletter.default' : enteraddonsMailchimp,
        'enteraddons-image-gallery.default' : enteraddonsIsotope,
        'enteraddons-countdown-timer.default' : enteraddonsCountdownTimer,
        'enteraddons-google-api-map.default' : enteraddonsGoogleMap,
        "enteraddons-image-zoom-magnifier.default": enteraddonsImageMagnifier,
        "enteraddons-content-ticker.default": enteraddonsContentTicker,
        "enteraddons-title-reveal-animation.default": enteraddonsRevealAnimation,
        "enteraddons-photo-reveal-animation.default": enteraddonsPhotoRevealAnimation,
        "enteraddons-image-hotspot.default": enteraddonsImageHotspot,
        "enteraddons-vertical-testimonial.default": enteraddonsVerticalTestimonial,
        "enteraddons-nav-menu.default": enteraddonsNavMenuHamburger,
        "enteraddons-nav-search.default": enteraddonsNavSearch,
        "enteraddons-coupon-code.default": enteraddonsCouponCode,
        "enteraddons-color-scheme.default": enteraddonsColorScheme,
        "enteraddons-accordion-gallery.default": enteraddonsAccordionGallery,
        "enteraddons-travel-gallery.default": enteraddonsTravelGallery,
        "enteraddons-typing-animation.default": enteraddonsTypingAnimation,
        "enteraddons-advanced-tabs.default": enteraddonsAdvancedTab,
        "enteraddons-advanced-animation-title.default": enteraddonsAdvancedAnimationTitle
    }

    // Assign call back function in prototype
    $.prototype.getEaElementTypeSkin = {
        'carousel':enteraddonsTestimonial,
        'accordion':enteraddonsAccordion,
        'checkData': checkData
    };

    // Frontend Init Hook
    $window.on('elementor/frontend/init', function () {

        let $EF = elementorFrontend,
            $containerType = $EF.config.experimentalFeatures.container ? 'container': 'section' ;

        // Hooked widgets
        $.each( elementTypeSkin, function( widgetName, fuHandler ) {
            $EF.hooks.addAction('frontend/element_ready/'+widgetName, fuHandler );
        } )



        // Nav Sticky 
        $EF.hooks.addAction( 'frontend/element_ready/'+$containerType, function( $scope ) {

            /*******************
             * Sticky Nav
             * *****************/
            let $header = $scope.closest('.ea-sticky-menu-section'),
                $offset = undefined != $header.data( 'settings' ) ?  $header.data( 'settings' ).sticky_menu_offset.size : 50;

            if( $header.length > 0 ) {
                
                let sticky = $header.height() + $offset;

                $( window ).scroll( function() {
                    
                    if ( window.pageYOffset > sticky ) {
                        $header.addClass("active-sticky");
                    } else {
                        $header.removeClass("active-sticky");
                    }

                } )
            }
            
        } );

        /******************************
         * Equal Height Module
         * **************************/

        let equalHeightModules = elementorModules.frontend.handlers.Base.extend({

            isEnable: function() {
                return this.getElementSettings('ea_enable_equal_height') ? true : false;
            },

            applyTo: function( sectionSelector ) {

                let applyTo = this.getElementSettings('apply_to'),
                    applyToCustomSelector = this.getElementSettings('apply_on_custom_selector');

                switch( applyTo ) {

                    case 'column' :
                        return $(sectionSelector).find('.elementor-column');
                    break;
                    case 'widget' :
                        return $(sectionSelector).find('.elementor-widget-container');
                    break;
                    case 'wc_1' :
                        return $(sectionSelector).find('.elementor-widget-container').children();
                    break;
                    case 'wc_2' :
                        return $(sectionSelector).find('.elementor-widget-container').children().children();
                    break;
                    case 'wc_3' :
                        return $(sectionSelector).find('.elementor-widget-container').children().children().children();
                    break;
                    case 'custom' :
                        return $(sectionSelector).find( applyToCustomSelector );
                    break;

                }
                
            },

            run: function () {

                if( this.isEnable() ) {
                    let $sectionSelector = this.$element.closest('.ea-equal-height-yes');
                    let slider_height = 0;
                    let $slider_slide = this.applyTo( $sectionSelector );
                    $slider_slide.each( function() {
                        let $height = $(this).outerHeight(true);
                        if( slider_height < $height ) {
                           slider_height = $height;
                        }
                    });
                    
                    $slider_slide.css( 'min-height', slider_height );
                }

            },

            onInit: function() {
                this.run();
            },


        })
        // Hooking equalHeightModules 
        $EF.hooks.addAction( 'frontend/element_ready/'+$containerType, function( $scope ) {
            
            $EF.elementsHandler.addHandler(equalHeightModules, {
                $element: $scope
            });

        } );

        /******************************
         * Floating Effect
         * **************************/

        let floatingEffectModules = elementorModules.frontend.handlers.Base.extend({

            isEnable: function() {
                return this.getElementSettings('ea_enable_floating_effects') ? true : false;
            },

            applyTo: function( sectionSelector ) {

            },
            onElementChange: function() {
                this.run();
            },

            animeConfig: function() {

                let config = {
                    targets: this.findElement('.elementor-widget-container').get(0),
                    loop: true,
                    direction: 'alternate',
                    easing: 'easeInOutSine',
                };
                /******* translate **********/
                if( this.getElementSettings( 'floating-translate-popover' ) ) {
                    // translate x
                    if( this.getElementSettings( 'ea_fe_translate_x' ) ) {

                        let $translateX = this.getElementSettings( 'ea_fe_translate_x' ),
                            $translateDuration = this.getElementSettings( 'ea_fe_translate_duration' ),
                            translateDelay = this.getElementSettings( 'ea_fe_translate_delay' );

                        config.translateX = {
                            value: [$translateX.sizes.to, $translateX.sizes.from], // from 100 to 250
                            duration: $translateDuration.size,
                            delay: translateDelay.size
                        }
                    }
                    // translate y
                    if( this.getElementSettings( 'ea_fe_translate_y' ) ) {

                        let $translateY = this.getElementSettings( 'ea_fe_translate_y' ),
                            $translateDuration = this.getElementSettings( 'ea_fe_translate_duration' ),
                            translateDelay = this.getElementSettings( 'ea_fe_translate_delay' );
                        config.translateY = {
                            value: [$translateY.sizes.to, $translateY.sizes.from], // from 100 to 250
                            duration: $translateDuration.size ,
                            delay: translateDelay.size
                        }
                    }
                }
                /******* scale **********/
                if( this.getElementSettings( 'floating-scale-popover' ) ) {
                    // Scale x
                    if( this.getElementSettings( 'ea_fe_scale_x' ) ) {

                        let $scaleX = this.getElementSettings( 'ea_fe_scale_x' ),
                            $scaleDuration = this.getElementSettings( 'ea_fe_scale_duration' ),
                            $scaleDelay = this.getElementSettings( 'ea_fe_scale_delay' );
                        config.scaleX = {
                            value: [$scaleX.sizes.to, $scaleX.sizes.from], // from 100 to 250
                            duration: $scaleDuration.size ,
                            delay: $scaleDelay.size
                        }
                    }
                    // Scale y
                    if( this.getElementSettings( 'ea_fe_scale_y' ) ) {

                        let $scaleY = this.getElementSettings( 'ea_fe_scale_y' ),
                            $scaleDuration = this.getElementSettings( 'ea_fe_scale_duration' ),
                            $scaleDelay = this.getElementSettings( 'ea_fe_scale_delay' );
                        config.scaleY = {
                            value: [$scaleY.sizes.to, $scaleY.sizes.from], // from 100 to 250
                            duration: $scaleDuration.size ,
                            delay: $scaleDelay.size
                        }
                    }
                }
                /******* rotate **********/
                if( this.getElementSettings( 'floating-rotate-popover' ) ) {
                    // rotate X
                    if( this.getElementSettings( 'ea_fe_rotate_x' ) ) {

                        let $rotateX = this.getElementSettings( 'ea_fe_rotate_x' ),
                            $rotateDuration = this.getElementSettings( 'ea_fe_rotate_duration' ),
                            $rotateDelay = this.getElementSettings( 'ea_fe_rotate_delay' );
                        config.rotateX = {
                            value: [$rotateX.sizes.to, $rotateX.sizes.from], // from 100 to 250
                            duration: $rotateDuration.size ,
                            delay: $rotateDelay.size
                        }
                    }
                    // rotate Y
                    if( this.getElementSettings( 'ea_fe_rotate_y' ) ) {

                        let $rotateY = this.getElementSettings( 'ea_fe_rotate_y' ),
                            $rotateDuration = this.getElementSettings( 'ea_fe_rotate_duration' ),
                            $rotateDelay = this.getElementSettings( 'ea_fe_rotate_delay' );
                        config.rotateY = {
                            value: [$rotateY.sizes.to, $rotateY.sizes.from], // from 100 to 250
                            duration: $rotateDuration.size ,
                            delay: $rotateDelay.size
                        }
                    }
                    // rotate z
                    if( this.getElementSettings( 'ea_fe_rotate_z' ) ) {

                        let $rotateZ = this.getElementSettings( 'ea_fe_rotate_z' ),
                            $rotateDuration = this.getElementSettings( 'ea_fe_rotate_duration' ),
                            $rotateDelay = this.getElementSettings( 'ea_fe_rotate_delay' );
                        config.rotateZ = {
                            value: [$rotateZ.sizes.to, $rotateZ.sizes.from], // from 100 to 250
                            duration: $rotateDuration.size ,
                            delay: $rotateDelay.size
                        }
                    }
                }
                /******* skew **********/
                if( this.getElementSettings( 'floating-skew-popover' ) ) {
                    // skew x
                    if( this.getElementSettings( 'ea_fe_skew_x' ) ) {

                        let $skewX = this.getElementSettings( 'ea_fe_skew_x' ),
                            $skewDuration = this.getElementSettings( 'ea_fe_skew_duration' ),
                            $skewDelay = this.getElementSettings( 'ea_fe_skew_delay' );
                        config.skewX = {
                            value: [$skewX.sizes.to, $skewX.sizes.from], // from 100 to 250
                            duration: $skewDuration.size ,
                            delay: $skewDelay.size
                        }
                    }
                    // skew y
                    if( this.getElementSettings( 'ea_fe_skew_y' ) ) {

                        let $skewY = this.getElementSettings( 'ea_fe_skew_y' ),
                            $skewDuration = this.getElementSettings( 'ea_fe_skew_duration' ),
                            $skewDelay = this.getElementSettings( 'ea_fe_skew_delay' );
                        config.skewY = {
                            value: [$skewY.sizes.to, $skewY.sizes.from], // from 100 to 250
                            duration: $skewDuration.size ,
                            delay: $skewDelay.size
                        }
                    }

                    
                }

                return config;
            },
            run: function () {
                let config = this.animeConfig();
                anime(config);
            },

            onInit: function() {
                if( this.isEnable() ) {
                    this.run();
                }
            },


        })

        // Hooking Floating Effect
        $EF.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {

            $EF.elementsHandler.addHandler(floatingEffectModules, {
                $element: $scope
            });

        } );
        
    }); //




})(jQuery,window);

'use strict';


/*
 * Elementor Hooks
 */

(function($) {

    /*
     * Helper Function
     */
  
    let woo_ready_get_slick_options = function ( $container, $prevArrow='', $nextArrow='', $asNavFor = '' ) {

        var SlidesToShow = $container.data("slides-to-show") !== undefined ? $container.data("slides-to-show") : 3 ,
            Speed = $container.data("speed") !== undefined ? $container.data("speed") : 400,
            Infinite = $container.data("loop") !== undefined ? $container.data("loop") : false,
            Autoplay = $container.data("autoplay") !== undefined ? $container.data("autoplay") : false,
            AutoplaySpeed = $container.data("autoplay-speed") !== undefined ? $container.data("autoplay-speed") : 9999,
            Dots = $container.data("dots") !== undefined ? $container.data("dots") : null,
            Arrows = $container.data("arrows") !== undefined ? $container.data("arrows") : null,
            GrabCursor = $container.data("grab-cursor") !== undefined ? $container.data("grab-cursor") : false,
            PauseOnHover = $container.data("pause-on-hover") !== undefined ? $container.data("pause-on-hover") : null,
            PauseOnFocus = $container.data("pause-on-focus") !== undefined ? $container.data("pause-on-focus") : null,
            Center = $container.data("center") !== undefined ? $container.data("center") : null,
            CenterPadding = $container.data("center-padding") !== undefined ? $container.data("center-padding") : null,
            Fade = $container.data("fade") !== undefined ? $container.data("fade") : null,
            Vertical = $container.data("vertical") !== undefined ? $container.data("vertical") : null,
            VerticalSwiping = $container.data("vertical-swiping") !== undefined ? $container.data("vertical-swiping") : null,
            FocusOnSelect = $container.data("focus-on-select") !== undefined ? $container.data("focus-on-select") : null,
            $prevArrow = ( $prevArrow !== null && $prevArrow !== '') ? $prevArrow : '<span class="prev"><i class="fa fa-arrow-left"></i></span>',
            $nextArrow = ( $nextArrow !== null && $nextArrow !== '') ? $nextArrow : '<span class="next"><i class="fa fa-arrow-right"></i></span>';

        const SlideOptions = {
            slidesToShow: SlidesToShow,
           
            speed: Speed,
            infinite: !!Infinite,
            autoplay: !!Autoplay,
            autoplaySpeed: AutoplaySpeed,
            dots: !!Dots,
            arrows: !!Arrows,
            grabCursor: !!GrabCursor,
            pauseOnHover: !!PauseOnHover,
            pauseOnFocus: !!PauseOnFocus,
            centerMode: !!Center,
            centerPadding: CenterPadding + 'px' ,
            fade: !!Fade,
            vertical: !!Vertical,
            verticalSwiping: !!VerticalSwiping,
            prevArrow: $prevArrow,
            nextArrow: $nextArrow,
            focusOnSelect: !!FocusOnSelect,
            asNavFor: $asNavFor,
        };

        return SlideOptions;
    };

    let woo_ready_get_owl_carousol_options = function ( $container ) {

        var $items =$container.data("items") !== undefined ? $container.data("items") : 3,
            $items_tablet = $container.data("items-tablet") !== undefined ? $container.data("items-tablet") : 3,
            $items_mobile = $container.data("items-mobile") !== undefined ? $container.data("items-mobile") : 3,
            $margin = $container.data("margin") !== undefined ? $container.data("margin") : 10,
            $margin_tablet = $container.data("margin-tablet") !== undefined ? $container.data("margin-tablet") : 10,
            $margin_mobile = $container.data("margin-mobile") !== undefined ? $container.data("margin-mobile") : 0,
            $smartSpeed = $container.data("smartSpeed") !== undefined ? $container.data("smartSpeed") : 700,
            $loop = $container.data("loop") !== undefined ? $container.data("loop") : 0,
            $center = $container.data("center") !== undefined ? $container.data("center") : 0,
            $dots = $container.data("dots") !== undefined ? $container.data("dots") : 0,
            $nav = $container.data("nav") !== undefined ? $container.data("nav") : 0,
            $responsiveClass = $container.data("responsiveClass") !== undefined ? $container.data("responsiveClass") : 0,
            $grab_cursor = $container.data("grab-cursor") !== undefined ? $container.data("grab-cursor") : 0,
            $autoplayHoverPause = $container.data("pause-on-hover") !== undefined ? $container.data("pause-on-hover") : "",
            $autoplay = $container.data("autoplay") !== undefined ? $container.data("autoplay") : 99999,
            $icon_prev = $container.data("icon-prev") !== undefined ? $container.data("icon-prev") : null,
            $icon_next = $container.data("icon-next") !== undefined ? $container.data("icon-next") : null;


            let $carouselOptions = {
                loop: $loop,
                margin: $margin,
                responsiveClass:$responsiveClass,
                dots: $dots,
                autoplay: $autoplay,
                smartSpeed: $smartSpeed,
                center: $center,
                autoplayHoverPause: $autoplayHoverPause,
                grabCursor: $grab_cursor,
                nav: $nav,
                navText: [ $icon_prev , $icon_next ],
                autoHeight: false,
                items: $items,
                responsive : {
                    1024: {
                        items: $items,
                        margin: $margin
                    },
                    768: {
                        items: $items_tablet,
                        margin: $margin_tablet
                    },
                    320: {
                        items: $items_mobile,
                        margin: $margin_mobile
                    }
                },
            };

        return $carouselOptions;
    };

   
    var woo_ready_nifty_popup = function($scope) {

        var $user_interface = $scope.find('.woo-ready-user-interface');
        
        $(document).on('click', '.wready-md-close', function() {
            $scope.find('#shop-ready-pro-minipopup-popup-modal').nifty('hide');
        });

        $user_interface.on('click', function(event) {
            $scope.find('#shop-ready-pro-minipopup-popup-modal').nifty('show');
        });
    };

    var woo_ready_cart_count_popup = function($scope,$) {
       
        let $header_pop_content = $scope.find('.woo-ready-sub-content');
        $(document).on('click','.woo-ready-cart-popup', function(event) {
              
            if($header_pop_content.hasClass('open')){

                $header_pop_content.removeClass('open');
              
            }else{
                $header_pop_content.addClass('open')
                
            }
          

        });

        $scope.find('.shop-ready-cart-count-close-btn').on('click', function(event) {
            $header_pop_content.removeClass('open')
           
        });
 
    };

    var woo_ready_popup = function($scope) {
    
        let $popup = $scope.find('.woo-ready-user-interface');
        let $header_pop_content = $scope.find('.woo-ready-sub-content');
        $popup.on('click', function(event) {
          
            if($header_pop_content.hasClass('open')){
                
                $header_pop_content.removeClass('open');
              
            }else{
                $header_pop_content.addClass('open')
                
            }
          

        });

        $scope.find('.shop-ready-cart-count-close-btn').on('click', function(event) {
            $header_pop_content.removeClass('open')
           
        });
 
    };

    var product_thumbnail_zoom = function($scope) {

        var news_slider2 = $scope.find('.wooready_product_details_thumb_1');
        news_slider2.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: false,
            arrows: false,
            fade: true,
            asNavFor: '.wooready_product_details_small_item'
        });
        var news_slider3 = $scope.find('.wooready_product_details_small_item');
        news_slider3.slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            asNavFor: '.wooready_product_details_thumb_1',
            dots: false,
            arrows: true,
            prevArrow: '<span class="prev"><i class="fa fa-angle-left"></i></span>',
            nextArrow: '<span class="next"><i class="fa fa-angle-right"></i></span>',
            centerMode: true,
            centerPadding: "0",
            focusOnSelect: true,

        });

        // vertical slider
        var news_slider4 = $scope.find('.wooready_product_details_thumb_2');
        news_slider4.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: false,
            arrows: false,
            fade: true,
            asNavFor: '.wooready_product_details_small_item_2'
        });
        var news_slider5 = $scope.find('.wooready_product_details_small_item_2');
        news_slider5.slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            asNavFor: '.wooready_product_details_thumb_2',
            dots: false,
            arrows: true,
            prevArrow: '<span class="prev"><i class="fa fa-angle-up"></i></span>',
            nextArrow: '<span class="next"><i class="fa fa-angle-down"></i></span>',
            centerMode: true,
            centerPadding: "0",
            focusOnSelect: true,
            vertical: true,
            verticalSwiping: true,

        });

    };

    var shop_sidebar_price_filter = function($scope) {

        $('input#min_price, input#max_price').hide();
        $('.price_slider, .price_label').show();

        var min_price = $('.price_slider_amount #min_price').data('min'),
            max_price = $('.price_slider_amount #max_price').data('max'),
            step = $('.price_slider_amount').data('step') || 1,
            current_min_price = $('.price_slider_amount #min_price').val(),
            current_max_price = $('.price_slider_amount #max_price').val();

        $('.price_slider:not(.ui-slider)').slider({
            range: true,
            animate: true,
            min: min_price,
            max: max_price,
            step: step,
            values: [current_min_price, current_max_price],
            create: function() {

                $('.price_slider_amount #min_price').val(current_min_price);
                $('.price_slider_amount #max_price').val(current_max_price);

                $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
            },
            slide: function(event, ui) {

                $('input#min_price').val(ui.values[0]);
                $('input#max_price').val(ui.values[1]);

                $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
            },
            change: function(event, ui) {
           
                $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
               
            }
        });

    };

    var shop_sidebar_search_form = function($scope) {

        $scope.find('.wooready_nice_select select').niceSelect();
       
        var $autocomplete = $scope.find('.shop-ready-pro-search-autocomplete');
        var $input = $scope.find('.wooready_input_box input');

        if( $autocomplete.length ){
            var $content_area = $scope.find('.shopready_search_auto_complate_box');
            $input.on('keyup',function(){

                var search_term = $(this).val();

                if(search_term.length > 2){
               
                    $.ajax({
                        type    : 'post',
                        dataType: 'json',
                        url     : wp.ajax.settings.url,
                        data    : { action: "shop_ready_search_filter_srv", sr_search_terms : search_term },
                        success : function(content) {
                         
                            if(content.success){
                                $content_area.html( content.data.html );
                            }else{
                                $content_area.html( '' );
                            }
                           
                           
                        }
                    }); 

                }else{
                    $content_area.html( '' );
                }
            
               
            }); 
         

        }

    };

    var general_countdown_banner = function($scope) {

            var $target = $scope.find('.wooready_countdown');
            let date    = $target.data('date');
            let time    = $target.data('time');

            var countDownDate = new Date(date +' '+ time).getTime();
            let label_days  = 'days';
            let label_hours = 'hours';
            let label_min   = 'min';
            let label_sec   = 'sec';
            
            var x = setInterval(function() {
            var now      = new Date().getTime();
            var distance = countDownDate - now;
            var days     = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours    = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes  = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds  = Math.floor((distance % (1000 * 60)) / 1000);

            $target.find('.day .num').text(days);
            $target.find('.hour .num').text(hours);
            $target.find('.min .num').text(minutes);
            $target.find('.sec .num').text(seconds);

            $target.find('.day .word').text(label_days);
            $target.find('.hour .word').text(label_hours);
            $target.find('.min .word').text(label_min);
            $target.find('.sec .word').text(label_sec);
           
                if (distance < 0) {
                  
                    clearInterval(x);
                    $target.find('.day .num').text(0);
                    $target.find('.hour .num').text(0);
                    $target.find('.min .num').text(0);
                    $target.find('.sec .num').text(0);
                }
            }, 1000);
           

    };

    var general_slider_banner = function($scope) {

        var currentTab = $scope.find('.woo-ready-banner-slider'),
        currentTabId = '#' + currentTab.attr('id').toString(),
        sliderTab = $(currentTabId);

        var slider_banner_main_slide = sliderTab.find('.wooready_product_banner_slider_active'),
            slider_banner_sub_slide = sliderTab.find('.wooready_product_banner_slider_sub'),

            prev = '<span class="prev"><i class="fa fa-arrow-left"></i></span>',
            next = '<span class="next"><i class="fa fa-arrow-right"></i></span>',

            mainSlideOptions = woo_ready_get_slick_options(sliderTab, prev, next, slider_banner_sub_slide ),
            subSlideOptions = woo_ready_get_slick_options(slider_banner_sub_slide, prev, next, slider_banner_main_slide);

        slider_banner_main_slide.slick(mainSlideOptions);

        slider_banner_sub_slide.slick(subSlideOptions);

    };

    var general_category_slider = function($scope) {


        var sliderTab = $scope.find('.woo-ready-product-category-slider'),
            prev = '<span class="prev"><i class="fa fa-angle-left"></i></span>',
            next = '<span class="next"><i class="fa fa-angle-right"></i></span>',
            SlideOptions = woo_ready_get_slick_options(sliderTab, prev, next),
            Slidesmobile = sliderTab.data("mobile-slides-to-show") !== undefined ? sliderTab.data("mobile-slides-to-show") : 1 ,
            Slidesipad   = sliderTab.data("ipad-slides-to-show") !== undefined ? sliderTab.data("ipad-slides-to-show") : 2 ;
            SlideOptions.responsive = [

                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: Slidesipad,
                    slidesToScroll: Slidesipad,
                    infinite: true,
                    dots: true
                  }
                },

                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: Slidesmobile,
                    slidesToScroll: Slidesmobile
                  }
                }
            ];
            
   
            sliderTab.slick( SlideOptions );

    };

    var general_product_slider = function($scope) {

        var currentTab = $scope.find('.wooready-product-slider'),
            SlideOptions = woo_ready_get_owl_carousol_options( currentTab );
            currentTab.owlCarousel( SlideOptions );

    }
  
    var general_floating_cart = function($scope,$){

        var cartCircle = $scope.find('.woo-ready-cart-circle'),
            cartboxToggle = $scope.find('.woo-ready-cart-box-toggle'),
            cartbox = $scope.find('.woo-ready-cart-box');

        $( cartCircle ).click(function () {
            $(this).toggle('scale');  
            $( cartbox ).toggle('scale');
        });

        $( cartboxToggle ).click(function () {
            $( cartCircle ).toggle('scale');  
            $( cartbox ).toggle('scale');
        });
    
    };

    var product_vertical_menu = function($scope,$){

        var currentTab = $scope.find('.woo-ready-product-vertical-menu'),
        currentTabId = '#' + currentTab.attr('id').toString(),
        currentLayout = currentTab.data('layout');

        if ( currentLayout == "always-open") {
            return;
        }

        $( currentTabId + ' .wooready-header-box .widget-title').click(function () {
            $(this).toggleClass('show');
            $( currentTabId + ' .wooready-vertical-menu').slideToggle();
        });
    };
    
    var checkout_coupon = function($scope,$){
       
        $scope.on('click', 'a.showcoupon', function(){
            $scope.find('.woocommerce-form-coupon').toggle('slow');
            return false;
        });
    };

    var checkout_login_form = function($scope,$){
      
        $scope.on('click', '.woo-ready-show-login', function(){
            $scope.find('.woocommerce-form-login').toggle('slow');
           
            return false;
        });

    };

    var shop_sidebar_product_attribute = function( $scope, $ ){
         
        if( typeof shop_ready_shop_filter_obj !== 'undefined' && shop_ready_shop_filter_obj.active){
            return;   
        }
      
        if(!elementorFrontend.config.environmentMode.edit){
            $scope.on('click', '.shop-ready-filter-attribute-single', function(){
      
                var filter_value = $(this).val();
                var current_url  = window.location.href;
                var url          = new URL(current_url);
                url.searchParams.append('attribute_filter', filter_value);
                url.searchParams.set('attribute_filter', filter_value);
               
                window.location.href = url;
            }); 
        }
      
    };
    
    var variable_product_add_to_cart = function( $scope, $ ){

       var $container = $scope.find('.select select');
       $container.niceSelect();            
       
      
    };
   

    $(window).on('elementor/frontend/init', function() {

      

        elementorFrontend.hooks.addAction('frontend/element_ready/general_cart_count.default', woo_ready_cart_count_popup);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_popup.default', woo_ready_popup);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_popup.default', woo_ready_nifty_popup);
        elementorFrontend.hooks.addAction('frontend/element_ready/shop_sidebar_price_filter.default', shop_sidebar_price_filter);
        elementorFrontend.hooks.addAction('frontend/element_ready/shop_sidebar_search_form.default', shop_sidebar_search_form);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_currency_swatcher.default', shop_sidebar_search_form);
        elementorFrontend.hooks.addAction('frontend/element_ready/product_thumbnail_zoom.default', product_thumbnail_zoom);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_countdown.default', general_countdown_banner);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_countdown_banner.default', general_countdown_banner);
        elementorFrontend.hooks.addAction('frontend/element_ready/product_comming_soon.default', general_countdown_banner);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_slider_banner.default', general_slider_banner);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_category_slider.default', general_category_slider);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_product_slider.default', general_product_slider);
        elementorFrontend.hooks.addAction('frontend/element_ready/general_floating_cart.default', general_floating_cart);
     
        elementorFrontend.hooks.addAction('frontend/element_ready/product_vertical_menu.default', product_vertical_menu);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/checkout_coupon.default', checkout_coupon );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/checkout_login_form.default', checkout_login_form );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/shop_sidebar_product_attribute_filter.default', shop_sidebar_product_attribute );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/product_add_to_cart.default', variable_product_add_to_cart );
    
    });

})(jQuery);
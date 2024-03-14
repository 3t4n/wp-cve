'use strict';

/*
 * Elementor Hooks
 */

(function($) {

    /*
     * Helper Function
     */
  
    var Shop_Ready_Tabs_Script = function($scope ,$){

        var tabs_area      = $scope.find( '.tabs__area' );
        var get_id         = tabs_area.attr( 'id' );
        var tabs_id        = $( '#' + get_id );
        var tab_active     = tabs_id.find( '.tab__nav a' );
        var tab_active_nav = tabs_id.find( '.tab__nav li' );
        var tab_items      = tabs_id.find( '.single__tab__item' );
        
        tab_active.on( 'click', function (event) {

            $( tab_active_nav ).removeClass( 'active' );
            $(this).parent().addClass( 'active' );
            tab_items.hide();
            tab_items.removeClass('active');
            $( $(this).attr( 'href' ) ).fadeIn( 700 );
            $( $(this).attr( 'href' ) ).addClass( 'active' );
            event.preventDefault();
        });

    }

    var Animate_Headline_Script = function($scope,$){
        
        var headline_content = $scope.find('.woo__ready__animate__heading__activation').eq(0);
        var settings         = headline_content.data('settings');
        var wrap_id          = headline_content.attr('id');
        var active_wrap      = $('#'+ wrap_id);
        var random_id        = settings['random_id'];
     
        var animate_type     = settings['animate_type'];
        active_wrap.animatedHeadline({
            animationType: animate_type
        });
    };
   
    var Adv_Accordion_Script_Handle = function($scope, $) {
       
        var $advanceAccordion     = $scope.find( ".woo__ready__adv__accordion" ),
            $accordionHeader      = $scope.find( ".woo__ready__accordion__header" ),
            $accordionType        = $advanceAccordion.data( "accordion-type" ),
            $accordionSpeed       = $advanceAccordion.data( "toogle-speed" );
           
        /*--------------------------------
            OPEN DEFAULT ACTIVED TAB
        ----------------------------------*/
        $accordionHeader.each(function() {
           
            if ($(this).hasClass("active-default")) {
                $(this).addClass("show active");
                $(this).next().slideDown($accordionSpeed);
            }
        });

        /*--------------------------------------------------
            REMOVE MULTIPLE CLICK EVENT FOR NESTED ACCORDION
        ----------------------------------------------------*/
        $accordionHeader.unbind("click");
        $accordionHeader.click(function(e) {
            e.preventDefault();
            var $this = $(this);

            if ($accordionType === "accordion") {
                if ($this.hasClass("show")) {
                    $this.removeClass("show active");
                    $this.next().slideUp($accordionSpeed);
                }else{
                    $this.parent().parent().find(".woo__ready__accordion__header").removeClass("show active");
                    $this.parent().parent().find(".woo__ready__accordion__content").slideUp($accordionSpeed);
                    $this.toggleClass("show active");
                    $this.next().slideToggle($accordionSpeed);
                }
            }else{
                /*-------------------------------
                    FOR ACCCORDION TYPE 'TOGGLE'
                --------------------------------*/
                if ($this.hasClass("show")) {
                    $this.removeClass("show active");
                    $this.next().slideUp($accordionSpeed);
                } else {
                    $this.addClass("show active");
                    $this.next().slideDown($accordionSpeed);
                }
            }
        });
    };

      /*-----------------------------------
        TIMELINE ROADMAP HANDALAR
    ------------------------------------*/
    var Timeline_Roadmap_Script_Handle_Data = function ( $scope, $ ){
      
        var roadmap_content = $scope.find('.woo__ready__prgoressbar__activation');
        var settings        = roadmap_content.data('settings');

        var content         = settings['content'];
    
        var eventsPerSlide  = settings['eventsperslide'] ? parseInt(settings['eventsperslide']) : 4 ;
        var slide           = settings['slide'] ? parseInt(settings['slide']) : 1 ;
        var prevArrow       = settings['prevArrow'] ? settings['prevArrow'] : '<i class="ti ti-left"></i>' ;
        var nextArrow       = settings['nextArrow'] ? settings['nextArrow'] : '<i class="ti ti-right"></i>' ;
        var orientation     = settings['orientation'] ? settings['orientation'] : 'auto' ;

        $scope.find( '.woo__ready__prgoressbar__activation' ).roadmap(content, {
            eventsPerSlide: eventsPerSlide,
            slide         : slide,
            prevArrow     : prevArrow,
            nextArrow     : nextArrow,
            orientation   : orientation,
            eventTemplate: '<div class="event display:flex">' +
			'<div class="event__date">####DATE###</div>' +
			'<div class="event__content">####CONTENT###</div>' +
		'</div>'
        });
    };

    
    /*-------------------------------
        MAILCHIMP HANDLER
    --------------------------------*/
    var MailChimp_Subscribe_Form_Script_Handle = function ($scope, $) {

        var mailchimp_data = $scope.find('.mailchimp_from__box').eq(0);
        var settings       = mailchimp_data.data('value');/*Data Value Also can get by attr().*/
        var random_id      = settings['random_id'];
        var post_url       = settings['post_url'];
        
        $( "#mc__form__" + random_id ).ajaxChimp({
            url     : ''+ post_url +'',
            callback: function (resp) {
                if (resp.result === "success") {
                    $("#mc__form__" + random_id + " input" ).hide();
                    $("#mc__form__" + random_id + " button" ).hide();
                }
            }
        });
    };

      /*-----------------------------
        SLICK CAROUSEL HANDLER
    ------------------------------*/
    var Slick_Carousel_Script_Handle = function ($scope, $) {

        var carousel_elem = $scope.find( '.woo-ready-carousel-activation' ).eq(0);

        if ( carousel_elem.length > 0 ) {

            var settings               = carousel_elem.data('settings');
            var slideid                = settings['slideid'];
            var arrows                 = settings['arrows'];
            var arrow_prev_txt         = settings['arrow_prev_txt'];
            var arrow_next_txt         = settings['arrow_next_txt'];
            var dots                   = settings['dots'];
            var autoplay               = settings['autoplay'];
            var autoplay_speed         = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed        = parseInt(settings['animation_speed']) || 300;
            var pause_on_hover         = settings['pause_on_hover'];
            var center_mode            = settings['center_mode'];
            var center_padding         = settings['center_padding'] ? settings['center_padding']+'px' : '50px';
            var rows                   = settings['rows'] ? parseInt(settings['rows']) : 0;
            var fade                   = settings['fade'];
            var focusonselect          = settings['focusonselect'];
            var vertical               = settings['vertical'];
            var infinite               = settings['infinite'];
            var rtl                    = settings['rtl'];
            var display_columns        = parseInt(settings['display_columns']) || 1;
            var scroll_columns         = parseInt(settings['scroll_columns']) || 1;
            var tablet_width           = parseInt(settings['tablet_width']) || 800;
            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 1;
           
            var tablet_scroll_columns  = parseInt(settings['tablet_scroll_columns']) || 1;
            var mobile_width           = parseInt(settings['mobile_width']) || 480;
            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
            var mobile_scroll_columns  = parseInt(settings['mobile_scroll_columns']) || 1;
            var carousel_style_ck      = parseInt( settings['carousel_style_ck'] ) || 1;
            var center_teblet_padding  = settings['center_padding'] ? settings['center_padding']+'px' : '50px';
            
            if(settings['center_teblet_padding'] !== undefined){
                center_teblet_padding = settings['center_teblet_padding'] ? settings['center_teblet_padding']+'px' : '50px';
               
            }

            if( carousel_style_ck == 4 ){
                carousel_elem.slick({
                    appendArrows: '.woo-ready-carousel-nav'+slideid,
                    appendDots  : '.woo-ready-carousel-dots'+slideid,
                    arrows      : arrows,
                    prevArrow   : '<div class="woo-ready-carosul-prev owl-prev"><i class="'+arrow_prev_txt+'"></i></div>',
                    nextArrow   : '<div class="woo-ready-carosul-next owl-next"><i class="'+arrow_next_txt+'"></i></div>',
                    dots        : dots,
                    customPaging: function( slick,index ) {
                        var data_title = slick.$slides.eq(index).find('.woo-ready-data-title').data('title');
                        return '<h6>'+data_title+'</h6>';
                    },
                    infinite      : infinite,
                    autoplay      : autoplay,
                    autoplaySpeed : autoplay_speed,
                    speed         : animation_speed,
                    rows          : rows,
                    fade          : fade,
                    focusOnSelect : focusonselect,
                    vertical      : vertical,
                    rtl           : rtl,
                    pauseOnHover  : pause_on_hover,
                    slidesToShow  : display_columns,
                    slidesToScroll: scroll_columns,
                    centerMode    : center_mode,
                    centerPadding : center_padding,
                    responsive    : [
                        {
                            breakpoint: tablet_width,
                            settings  : {
                                slidesToShow  : tablet_display_columns,
                                slidesToScroll: tablet_scroll_columns,
                                centerPadding : center_template_padding,
                            }
                        },
                        {
                            breakpoint: mobile_width,
                            settings  : {
                                slidesToShow  : mobile_display_columns,
                                slidesToScroll: mobile_scroll_columns
                            }
                        }
                    ]
                });
            }else{
                carousel_elem.slick({
                    appendArrows  : '.woo-ready-carousel-nav'+slideid,
                    appendDots    : '.woo-ready-carousel-dots'+slideid,
                    arrows        : arrows,
                    prevArrow     : '<div class="woo-ready-carosul-prev owl-prev"><i class="'+arrow_prev_txt+'"></i></div>',
                    nextArrow     : '<div class="woo-ready-carosul-next owl-next"><i class="'+arrow_next_txt+'"></i></div>',
                    dots          : dots,
                    infinite      : infinite,
                    autoplay      : autoplay,
                    autoplaySpeed : autoplay_speed,
                    speed         : animation_speed,
                    rows          : rows,
                    fade          : fade,
                    focusOnSelect : focusonselect,
                    vertical      : vertical,
                    rtl           : rtl,
                    pauseOnHover  : pause_on_hover,
                    slidesToShow  : display_columns,
                    slidesToScroll: scroll_columns,
                    centerMode    : center_mode,
                    centerPadding : center_padding,
                    responsive    : [
                        {
                            breakpoint: tablet_width,
                            settings  : {
                                slidesToShow  : tablet_display_columns,
                                slidesToScroll: tablet_scroll_columns
                            }
                        },
                        {
                            breakpoint: mobile_width,
                            settings  : {
                                slidesToShow  : mobile_display_columns,
                                slidesToScroll: mobile_scroll_columns
                            }
                        }
                    ]
                    
                });
            }
        }
    };

    var Woo_Ready_Add_Span_To_First_Word = function($scope, $){
      
        
        $(".post__meta li").html(function(){
            var text= $(this).text().trim().split(" ");
            var first = text.shift();
            return (text.length > 0 ? "<span class='first__word'>"+ first + "</span> " : first) + text.join(" ");
        });
    }

    var Counter_Box = function( $scope , $ ){
        
        var $container = $scope.find('.elementor-counter-number');
        var options = [];
        
        var easing  = $container.data('animation-type') || 'linear';
        var duration  = $container.data('duration') || 2000;
        var delimiter = $container.data('delimiter') || '';
        var toValue   = $container.data('to-value');
        options = {

            'easing'   : easing,
            'duration' : duration,
            'delimiter': delimiter,
            'toValue'  : toValue
     
        }
 
        $scope.find('.elementor-counter-number').numerator( options )
    };

    var offcanvas_menu_script_Handle = function ( $scope, $){

        $scope.find('.wooready-offcanvas-toggler .wooready-offcanvas-navbar-toggler').click(function(){
            $scope.find('.wooready-offcanvas-box-content .wooready-content-info').addClass('active');
            $scope.find('.wooready-offcanvas-box-content .wooready-offcanvas-bg-overlay').addClass('active');
            $('html').addClass('wooready-overflowHidden');
        });
        $scope.find('.wooready-offcanvas-box-content .remove').click(function(){
            $scope.find('.wooready-offcanvas-box-content .wooready-content-info').removeClass('active');
            $scope.find('.wooready-offcanvas-box-content .wooready-offcanvas-bg-overlay').removeClass('active');
            $('html').removeClass('wooready-overflowHidden');
        });
        if("$('.navbar-container').addClass('active');"){
            $scope.find('.wooready-offcanvas-box-content .wooready-offcanvas-bg-overlay').click(function() {
                $('.remove').trigger('click');
            });
        }

    };

    var Woo_Ready_Mobile_Menu_Offcanvas = function($scope, $){
       
        var $container = $scope.find( '.woo-ready-mobile-menu-wr' );
        let icon_class = $container.data('indicator')?$container.data('indicator'):'fa fa-angle-down';
        var $offcanvasNav = $scope.find( '.woo-ready-offcanvas-main-menu' );

        var $offcanvasNavSubMenu = $offcanvasNav.find('.woo-ready-sub-menu');
      
        $offcanvasNavSubMenu.parent().prepend(`<span class="woo-ready-menu-expand"><i class="${icon_class}"></i></span>`);
        $offcanvasNavSubMenu.slideUp();

        $offcanvasNav.on('click', 'li a, li .woo-ready-menu-expand', function (e) {

            var $this = $(this);
            if (($this.parent().attr('class').match(/\b(woo-ready-menu-item-has-children|has-children|has-sub-menu|woo-ready-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('woo-ready-menu-expand'))) {
                e.preventDefault();
                if ($this.siblings('ul:visible').length) {
                    $this.siblings('ul').slideUp('slow');
                } else {
                    $this.closest('li').siblings('li').find('ul:visible').slideUp('slow');
                    $this.siblings('ul').slideDown('slow');
                }
            }
            if ($this.is('a') || $this.is('span') || $this.attr('clas').match(/\b(woo-ready-menu-expand)\b/)) {
                $this.parent().toggleClass('menu-open');
            } else if ($this.is('li') && $this.attr('class').match(/\b('woo-ready-menu-item-has-children')\b/)) {
                $this.toggleClass('menu-open');
            }
        });
    } 

     /*---------------------------------
        OWL CAROUSEL HANDLER
    ---------------------------------*/
	var Owl_Carousel_Script_Handle = function ($scope, $){

		var carousel_elem = $scope.find('.woo-ready-carousel-active').eq(0);
		var settings      = carousel_elem.data('settings');

        if ( typeof settings !== 'undefined' ) {

            var item_on_large     = settings['item_on_large'] ? settings['item_on_large'] : 1;
            var item_on_medium    = settings['item_on_medium'] ? settings['item_on_medium'] : 1;
            var item_on_tablet    = settings['item_on_tablet'] ? settings['item_on_tablet'] : 1;
            var item_on_mobile    = settings['item_on_mobile'] ? settings['item_on_mobile'] : 1;
            var stage_padding     = settings['stage_padding'] ? settings['stage_padding'] : 0;
            var item_margin       = settings['item_margin'] ? settings['item_margin'] : 0;
            var autoplay          = settings['autoplay'] ? settings['autoplay']: true;
            var autoplaytimeout   = settings['autoplaytimeout'] ? settings['autoplaytimeout'] : 3000;
            var slide_speed       = settings['slide_speed'] ? settings['slide_speed'] : 1000;
            var slide_animation   = settings['slide_animation'] ? settings['slide_animation'] : false;
            var slide_animate_in  = settings['slide_animate_in'] ? settings['slide_animate_in'] :'fadeIn';
            var slide_animate_out = settings['slide_animate_out'] ? settings['slide_animate_out'] : 'fadeOut';
            var nav               = settings['nav'] ? settings['nav'] : false;
            var nav_position      = settings['nav_position'] ? settings['nav_position'] : 'outside_vertical_center_nav';
            var next_icon         = ( settings['next_icon'] ) ? settings['next_icon'] : 'fa fa-angle-right';
            var prev_icon         = ( settings['prev_icon'] ) ? settings['prev_icon'] : 'fa fa-angle-left';
            var dots              = settings['dots'] ? settings['dots'] : false;
            var loop              = settings['loop'] ? settings['loop'] : true;
            var hover_pause       = settings['hover_pause'] ? settings['hover_pause'] : false;
            var center            = settings['center'] ? settings['center'] : false;
            var rtl               = settings['rtl'] ? settings['rtl'] : false;

            if ( 'yes' == slide_animation ) {
                var animateIn  = slide_animate_in;
                var animateOut = slide_animate_out;
            }else{
                var animateIn  = '';
                var animateOut = '';
            }

            if ( carousel_elem.length > 0 ) {
                carousel_elem.owlCarousel({
                    merge             : true,
                    smartSpeed        : slide_speed,
                    loop              : loop,
                    nav               : nav,
                    dots              : dots,
                    autoplayHoverPause: hover_pause,
                    center            : center,
                    rtl               : rtl,
                    navText           : ['<i class ="' + prev_icon + '"></i>', '<i class="' + next_icon + '"></i>'],
                    autoplay          : autoplay,
                    autoplayTimeout   : autoplaytimeout,
                    stagePadding      : stage_padding,
                    margin            : item_margin,
                    animateIn         : ''+ animateIn +'',
                    animateOut        : ''+ animateOut +'',
                    responsiveClass   : true,
                    responsive        : {
                        0: {
                            items: item_on_mobile
                        },
                        600: {
                            items: item_on_tablet
                        },
                        1000: {
                            items: item_on_medium
                        },
                        1200: {
                            items: item_on_medium
                        },
                        1900: {
                            items: item_on_large
                        }
                    }
                });
            }

            var thumbs_slide = $('.testmonial__thumb__content__slider');
            if ( thumbs_slide.length > 0 ) {
                /*--------------------------
                    THUMB CAROUSEL ACTIVE
                ---------------------------*/
                var thumbs_slide = $('.testmonial__thumb__content__slider');
                var duration     = 300;
                var thumbs       = 3;

                /*--------------------------
                    MAIN CAROUSEL TRIGGER
                ---------------------------*/
                carousel_elem.on('click', '.owl-next', function () {
                    thumbs_slide.trigger('next.owl.carousel')
                });
                carousel_elem.on('click', '.owl-prev', function () {
                    thumbs_slide.trigger('prev.owl.carousel')
                });
                carousel_elem.on('dragged.owl.carousel', function (e) {
                    if (e.relatedTarget.state.direction == 'left') {
                        thumbs_slide.trigger('next.owl.carousel')
                    } else {
                        thumbs_slide.trigger('prev.owl.carousel')
                    }
                });

                /*--------------------------
                    THUMBS CAROUSEL TRIGGER
                ----------------------------*/
                thumbs_slide.on('click', '.owl-next', function () {
                    carousel_elem.trigger('next.owl.carousel')
                });
                thumbs_slide.on('click', '.owl-prev', function () {
                    carousel_elem.trigger('prev.owl.carousel')
                });
                thumbs_slide.on('dragged.owl.carousel', function (e) {
                    if (e.relatedTarget.state.direction == 'left') {
                        carousel_elem.trigger('next.owl.carousel')
                    } else {
                        carousel_elem.trigger('prev.owl.carousel')
                    }
                });

                /*--------------------------
                    THUMB CAROUSEL ACTIVE
                ----------------------------*/
                thumbs_slide.owlCarousel({
                    loop              : loop,
                    items             : thumbs,
                    margin            : 10,
                    cente             : true,
                    autoplay          : autoplay,
                    autoplayTimeout   : autoplaytimeout,
                    autoplayHoverPause: hover_pause,
                    smartSpeed        : slide_speed,
                    nav               : false,
                    responsive        : {
                        0: {
                            items: 3
                        },
                        768: {
                            items: 3
                        }
                    }
                }).on('click', '.owl-item', function () {
                    var i = $(this).index() - (thumbs + 1);
                    thumbs_slide.trigger('to.owl.carousel', [i, slide_speed, true]);
                    carousel_elem.trigger('to.owl.carousel', [i, slide_speed, true]);
                });
            }
        }
    }

    var Deals_Products_Counter = function( $scope ,$ ){

            var $container  = $scope.find('.shopready-deals-slider');
            var responsiveclass = $container.data('responsiveclass') == 'undefined'? false : true;
            var dots = Boolean( $container.data('dots') == 'undefined'? false : $container.data('dots') );
            var center = Boolean( $container.data('center') == 'undefined'? false : $container.data('center') );
            var nav = Boolean( $container.data('nav') == 'undefined'? false : $container.data('nav') );
            var loop = Boolean( $container.data('loop') == 'undefined'? false : $container.data('loop') );
            var isautoplay = Boolean( $container.data('isautoplay') == 'undefined'? false : $container.data('isautoplay') );
            var autoplay_speed = $container.data('smartspeed') == 'undefined'? 1000 : parseInt( $container.data('smartspeed') );
            var icon_next = $container.data('icon-next') == 'undefined'? '<i class="fa fa-chevron-right"></i>' : $container.data('icon-next');
            var icon_prev = $container.data('icon-prev') == 'undefined'? '<i class="fa fa-chevron-left"></i>' : $container.data('icon-prev');
     
            if($container.length > 0){
                    
                var owl = $container.owlCarousel({
                     loop           : loop,
                     margin         : parseInt( $container.data('margin') ) || 0,
                     responsiveClass: responsiveclass,
                     dots           : dots,
                     dotsData:      true,
                     autoplay       : isautoplay,
                     smartSpeed     : autoplay_speed,
                     center         : center,
                     nav            : nav,
                     navText        : [icon_prev, icon_next],
                     items          : parseInt( $container.data('items') ) || 1,
                     responsive: {
                        0:{
                          items: parseInt( $container.data('items-mobile') ) || 1
                        },
                        768:{
                          items: parseInt( $container.data('items-tablet') ) || 1
                        },
                        991:{
                          items: parseInt( $container.data('items') ) || 1
                        }
                    }
                 });

                 $container.find('.owl-dot').click(function() {
                    owl.trigger('to.owl.carousel', [$(this).index(), 1000]);
                });

                $container.find('.shopready-carousel-item .sr-shop-gl-imgs img').on('click',function(){
                   var imge_src = $(this).data('src');  
                   $container.find('.shopready-carousel-item .ps_img img').attr('src',imge_src)
                   
                });

            }

            var $counter_labels  = $scope.find('.shop-ready-product-deal-modifier');

            var $counter = $scope.find('.shopready_countdown_wrapper .shopready_countdown');
          
            $.each( $counter, function( key, value ) {

                 let loop_target   = $(this);
                 let date          = $(this).data('date');
                 let time          = $(this).data('time');
                 var countDownDate = new Date(date +' '+ time).getTime();
                 let label_days    = $counter_labels.data('days');
                 let label_hours   = $counter_labels.data('hours');
                 let label_min     = $counter_labels.data('min');
                 let label_sec     = $counter_labels.data('sec');
        
                   var x = setInterval(function() {
                      
                     var now      = new Date().getTime();
                     var distance = countDownDate - now;
                     var days     = Math.floor(distance / (1000 * 60 * 60 * 24));
                     var hours    = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                     var minutes  = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                     var seconds  = Math.floor((distance % (1000 * 60)) / 1000);

                     loop_target.find('.day .num').text(days);
                     loop_target.find('.hour .num').text(hours);
                     loop_target.find('.min .num').text(minutes);
                     loop_target.find('.sec .num').text(seconds);

                     loop_target.find('.day .word').text(label_days);
                     loop_target.find('.hour .word').text(label_hours);
                     loop_target.find('.min .word').text(label_min);
                     loop_target.find('.sec .word').text(label_sec);
                
                        if (distance < 0) {
                        
                            clearInterval(x);
                            loop_target.find('.day .num').text(0);
                            loop_target.find('.hour .num').text(0);
                            loop_target.find('.min .num').text(0);
                            loop_target.find('.sec .num').text(0);

                        }

                    }, 1000);
            });
          
    }

    var Shop_Ready_WC_Side_Cart_PopUp = function( $scope ,$ ){
  
     //===== Shopping Cart 
       
        var $container  = $scope.find('.element-ready-shopping-cart-open');
       
        $container.on('click', function () {
            $('.element-ready-shopping-cart-canvas').addClass('open')
            $('.overlay').addClass('open')
        });

        $('.element-ready-shopping-cart-close').on('click', function () {
            $('.element-ready-shopping-cart-canvas').removeClass('open')
            $('.overlay').removeClass('open')
        });
        $('.overlay').on('click', function () {
            $('.element-ready-shopping-cart-canvas').removeClass('open')
            $('.overlay').removeClass('open')
        });

        // remove product from cart
     
       $('body').on('click', '.element-ready-cart-item-remove', function () {
            
             let self =  $(this);
             var product_key  = self.attr('data-product');
             var parent = self.parents('.element-ready-single-shopping-cart');
            
             parent.addClass('sidebar-cart-overlay');
              $.ajax({
                type : "post",
                url: shop_ready_obj.ajax_url,
                data : {action: "shop_ready_wc_cart_item_remove", cart_product_key : product_key},
                success: function(response,status) {
                    parent.removeClass('sidebar-cart-overlay');
                   if(status == "success") {
                   
                     self.parents('li').remove();
                     $('.element-ready-wc-shopping-total-amount').text(response.data.total_amount);
                     $container.find('.element-ready-interface-cart-count').text(response.data.total_items);
                     $container.find('.element-ready-interface-cart-sub-total').text(response.data.total_amount);
                     
                   }
        
                }
                
             })   
          
        });
        
         // add product to cart 
        $('.ajax_add_to_cart').on('click',function(e){

            e.preventDefault();
            
            let self       = $(this);
            let product_id = self.data('product_id');
            let quantity   = self.data('quantity');
            
            $.ajax({
                type : "post",
                url: shop_ready_obj.ajax_url,
                data : {action: "shop_ready_wc_cart_item_add", product_id: product_id,product_quantity: quantity},
                success: function(response,status) {
 
                   var template = wp.template( 'shop-ready-add-shopping-cart-item' );
                   if(response.success == true) {

                    var element_ready_cart_data = response.data;
                    var cart_total = element_ready_cart_data.cart_total;
                   
                    delete element_ready_cart_data.cart_total; 
                    delete element_ready_cart_data.cart_items; 

                    var item_content =  template( element_ready_cart_data );
                    $('.element-ready-shopping_cart-list-items ul').html(item_content);
                    $('.element-ready-wc-shopping-total-amount').text(cart_total);
                    $('.element-ready-shopping-cart-canvas').addClass('open');
                    $container.find('.element-ready-interface-cart-count').text(element_ready_cart_data.cart_items);
                    $container.find('.element-ready-interface-cart-sub-total').text( cart_total);
                  
                   } // response
        
                } // success
                
             })
       });

    }

    $(window).on('elementor/frontend/init', function() {

        elementorFrontend.hooks.addAction( 'frontend/element_ready/tab_shop_ready_advanced.default' , Shop_Ready_Tabs_Script);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/heading_woo_animate_headline.default' , Animate_Headline_Script);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/accordion_woo_ready_adv.default' , Adv_Accordion_Script_Handle );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/subscription_woo_ready_mailchimps.default' , MailChimp_Subscribe_Form_Script_Handle );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/progressbar_woo_ready_progress_roadmap.default' , Timeline_Roadmap_Script_Handle_Data );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/counter_woo_ready_counter.default' , Counter_Box );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/posts_woo_ready_post_carousel.default' , Slick_Carousel_Script_Handle );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/posts_woo_ready_post_carousel.default' , Woo_Ready_Add_Span_To_First_Word );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/testimonial_woo_ready_testmonial.default' , Owl_Carousel_Script_Handle );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/navigation_wr_offcanvas.default' , offcanvas_menu_script_Handle );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/navigation_woo_mobile_menu.default' , Woo_Ready_Mobile_Menu_Offcanvas );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/shop_shop_ready_sidebar_cart.default', Shop_Ready_WC_Side_Cart_PopUp );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/shop_sr_deals_products_counter.default', Deals_Products_Counter );
    });


})(jQuery);
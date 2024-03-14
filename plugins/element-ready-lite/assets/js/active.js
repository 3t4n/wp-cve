(function($) {
    var LP_Grid_Course = function($scope, $) {

        var layout = $scope.find('.widget-layout').data('layout');
        var widget_id = '.shafull-container-' + $scope.data('id');
        var shaf_item = '.shaf-item-' + $scope.data('id');
        var shaf_filter = '.shaf-filter-' + $scope.data('id') + ' li';

        if (layout != 'undefined' && layout == 'tabs') {

            $(window).on('load', function() {

                if ($(widget_id).length > 0) {
                    let $suffle_grid;

                    $suffle_grid = $(widget_id);

                    $suffle_grid.shuffle({
                        itemSelector: shaf_item,
                        sizer: '.shaf-sizer'
                    });

                    /* reshuffle when user clicks a filter item */
                    $(shaf_filter).on('click', function() {
                        // set active class
                        $(shaf_filter).removeClass('active');
                        $(this).addClass('active');
                        // get group name from clicked item
                        var groupName = $(this).attr('data-group');
                        // reshuffle grid
                        $suffle_grid.shuffle('shuffle', groupName);
                    });
                }

            });
        }


    };

    var Element_Ready_Menu_SigninUp = function($scope, $) {

        /* TOGGLE */
        $(document).on('click', '.header-control .close', function() {
            $(this).closest('.element-ready-dropdown').removeClass('open');
        });

        $(document).on('click', function(event) {

            var _target = $(event.target).closest('.element-ready-dropdown');
            var _allparent = $('.element-ready-dropdown');

            if (_target.length > 0) {
                _allparent.not(_target).removeClass('open');
                if (
                    $(event.target).is('[data-gnash="gnash-dropdown"]') ||
                    $(event.target).closest('[data-gnash="gnash-dropdown"]').length > 0
                ) {
                    _target.toggleClass('open');
                    return false;
                }
            } else {
                $('.element-ready-dropdown').removeClass('open');
            }

        });

    
    };

     var Element_Ready_Menu_Mini_PopUp =  function($scope, $) {

        /* TOGGLE */
        let $header_pop_content = $scope.find('.element-ready-submenu');
        let $header_pop_close = $scope.find('.er-ready-count-close-btn');
      
        let $er_pop = $scope.find('.er-ready-cart-popup');
       
        $er_pop.on('click', function(event) {
             
            if($header_pop_content.hasClass('open')){

                $header_pop_content.removeClass('open');
              
            }else{
                $header_pop_content.addClass('open')
                
            }
      
        });

        $header_pop_close.on('click',function(){
            $header_pop_content.removeClass('open');
           
        }); 
  
    
    }; 

    var Element_Ready_WC_Cart_PopUp = function($scope, $) {


        //===== Shopping Cart 

        var $container = $scope.find('.element-ready-shopping-cart-open');

        $container.on('click', function() {
            $('.element-ready-shopping-cart-canvas').addClass('open')
            $('.overlay').addClass('open')
        });

        $('.element-ready-shopping-cart-close').on('click', function() {
            $('.element-ready-shopping-cart-canvas').removeClass('open')
            $('.overlay').removeClass('open')
        });
        $('.overlay').on('click', function() {
            $('.element-ready-shopping-cart-canvas').removeClass('open')
            $('.overlay').removeClass('open')
        });

        // remove product from cart

        $('body').on('click', '.element-ready-cart-item-remove', function() {

            let self = $(this);
            var product_key = self.attr('data-product');

            $.ajax({
                type: "post",
                url: element_ready_obj.ajax_url,
                data: { action: "element_ready_wc_cart_item_remove", cart_product_key: product_key },
                success: function(response, status) {
                 
                    if (response.success == true)  {

                        self.parents('li').remove();
                        $('.element-ready-wc-shopping-total-amount').text(response.data.total);
                        $('.element-ready-interface-cart-count span').text(response.data.count);
                    }

                }

            })

        });
        // add product to cart 
        $('.ajax_add_to_cart').on('click', function() {

            let self = $(this);
            let product_id = self.data('product_id');
            let quantity = self.data('quantity');
            
            setTimeout(function(){
                $.ajax({
                    type: "post",
                    url: element_ready_obj.ajax_url,
                    data: { action: "element_ready_wc_cart_item_add", product_id: product_id, product_quantity: quantity },
                    success: function(response, status) {
                          
                            var template = wp.template('element-ready-add-shopping-cart-item');
                            if (response.success == true) {
    
                                var element_ready_cart_data = response.data.items;
                                var element_ready_cart_count = parseInt( response.data.count );
                                var cart_total = element_ready_cart_data.cart_total;
                                delete element_ready_cart_data.cart_total;
                                var item_content = template(element_ready_cart_data);
                                $('.element-ready-shopping_cart-list-items ul').html(item_content);
                                $('.element-ready-interface-cart-count span').text(element_ready_cart_count);
                                $('.element-ready-wc-shopping-total-amount').text(cart_total);
                                $('.element-ready-shopping-cart-canvas').addClass('open');
    
                            } // response
    
                        } // success
    
                })
            },1000);
           
        });

    };

  

    var Element_Ready_Menu_Search = function($scope, $) {
        //===== Search 
     
        let $search_opened = $scope.find(".element-ready-search-close");
        let $search_       = $scope.find(".element-ready-search-open");
        let $search_box    = $scope.find(".element-ready-search-box");

        $search_.on('click', function() {
            $search_box.addClass('open')
        });
        
        $search_opened.on('click', function() {
            $search_box.removeClass('open')
        });

    };

    var News_Grid_Post = function($scope, $item) {
        var $settings_data = $scope.find(".element-ready-post-grid");

    };

    var Element_Ready_Adv_Accordion_Script_Handle = function($scope, $) {


        var $advanceAccordion = $scope.find(".element__ready__adv__accordion"),
            $accordionHeader = $scope.find(".element__ready__accordion__header"),
            $accordionType = $advanceAccordion.data("accordion-type"),
            $accordionSpeed = $advanceAccordion.data("toogle-speed");

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
                } else {
                    $this.parent().parent().find(".element__ready__accordion__header").removeClass("show active");
                    $this.parent().parent().find(".element__ready__accordion__content").slideUp($accordionSpeed);
                    $this.toggleClass("show active");
                    $this.next().slideToggle($accordionSpeed);
                }
            } else {
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
  
    /*--------------------------------
        TABS ACTIVE
    ----------------------------------*/
    var Element_Ready_Tabs_Script = function($scope, $) {

        var tabs_area = $scope.find('.tabs__area');
        var get_id = tabs_area.attr('id');
        var tabs_id = $('#' + get_id);
        var tab_active = tabs_id.find('.tab__nav a');
        var tab_active_nav = tabs_id.find('.tab__nav li');
        var tab_items = tabs_id.find('.single__tab__item');

        tab_active.on('click', function(event) {

            $(tab_active_nav).removeClass('active');
            $(this).parent().addClass('active');
            tab_items.hide();
            tab_items.removeClass('active');
            $($(this).attr('href')).fadeIn(700);
            $($(this).attr('href')).addClass('active');
            event.preventDefault();
        });

    }

    var Element_Ready_ER_Tabs_Script = function($scope, $) {

        var tabs_area = $scope.find('.tabs__area');
        var get_id = tabs_area.attr('id');
        var tabs_id = $('#' + get_id);
        var tab_active = tabs_id.find('.tab__nav a');
        var tab_active_nav = tabs_id.find('.tab__nav li');
        var tab_items = tabs_id.find('.single__tab__item');

        tab_active.on('click', function(event) {

            $(tab_active_nav).removeClass('active');
            $(this).parent().addClass('active');
            tab_items.hide();
            tab_items.removeClass('active');
            $($(this).attr('href')).fadeIn(700);
            $($(this).attr('href')).addClass('active');
            event.preventDefault();
        });

    }

    /*--------------------------------
      Price  TABS ACTIVE
    ----------------------------------*/
    var Element_Ready_Price_Tabs_Script = function($scope, $) {

        var tabs_area = $scope.find('.tabs__area');
        var get_id = tabs_area.attr('id');
        var tabs_id = $('#' + get_id);
        var tab_active = tabs_id.find('.tab__nav a');
        var tab_active_nav = tabs_id.find('.tab__nav li');
        var tab_items = tabs_id.find('.single__tab__item');

        tab_active.on('click', function(event) {

            $(tab_active_nav).removeClass('active');
            $(this).parent().addClass('active');
            tab_items.hide();
            tab_items.removeClass('active');
            $($(this).attr('href')).fadeIn(700);
            $($(this).attr('href')).addClass('active');
            event.preventDefault();
        });

    }

    /*---------------------------------
        OWL CAROUSEL HANDLER
    ---------------------------------*/
    var Owl_Carousel_Script_Handle = function($scope, $) {

        var carousel_elem = $scope.find('.element-ready-carousel-active').eq(0);
        var settings = carousel_elem.data('settings');

        if (typeof settings !== 'undefined') {

            var item_on_large = settings['item_on_large'] ? settings['item_on_large'] : 1;
            var item_on_medium = settings['item_on_medium'] ? settings['item_on_medium'] : 1;
            var item_on_tablet = settings['item_on_tablet'] ? settings['item_on_tablet'] : 1;
            var item_on_mobile = settings['item_on_mobile'] ? settings['item_on_mobile'] : 1;
            var stage_padding = settings['stage_padding'] ? settings['stage_padding'] : 0;
            var item_margin = settings['item_margin'] ? settings['item_margin'] : 0;
            var autoplay = settings['autoplay'] ? settings['autoplay'] : true;
            var autoplaytimeout = settings['autoplaytimeout'] ? settings['autoplaytimeout'] : 3000;
            var slide_speed = settings['slide_speed'] ? settings['slide_speed'] : 1000;
            var slide_animation = settings['slide_animation'] ? settings['slide_animation'] : false;
            var slide_animate_in = settings['slide_animate_in'] ? settings['slide_animate_in'] : 'fadeIn';
            var slide_animate_out = settings['slide_animate_out'] ? settings['slide_animate_out'] : 'fadeOut';
            var nav = settings['nav'] ? settings['nav'] : false;
            var nav_position = settings['nav_position'] ? settings['nav_position'] : 'outside_vertical_center_nav';
            var next_icon = (settings['next_icon']) ? settings['next_icon'] : 'fa fa-angle-right';
            var prev_icon = (settings['prev_icon']) ? settings['prev_icon'] : 'fa fa-angle-left';
            var dots = settings['dots'] ? settings['dots'] : false;
            var loop = settings['loop'] ? settings['loop'] : true;
            var hover_pause = settings['hover_pause'] ? settings['hover_pause'] : false;
            var center = settings['center'] ? settings['center'] : false;
            var rtl = settings['rtl'] ? settings['rtl'] : false;

            if ('yes' == slide_animation) {
                var animateIn = slide_animate_in;
                var animateOut = slide_animate_out;
            } else {
                var animateIn = '';
                var animateOut = '';
            }

            if (carousel_elem.length > 0) {
                carousel_elem.owlCarousel({
                    merge: true,
                    smartSpeed: slide_speed,
                    loop: loop,
                    nav: nav,
                    dots: dots,
                    autoplayHoverPause: hover_pause,
                    center: center,
                    rtl: rtl,
                    navText: ['<i class ="' + prev_icon + '"></i>', '<i class="' + next_icon + '"></i>'],
                    autoplay: autoplay,
                    autoplayTimeout: autoplaytimeout,
                    stagePadding: stage_padding,
                    margin: item_margin,
                    animateIn: '' + animateIn + '',
                    animateOut: '' + animateOut + '',
                    responsiveClass: true,
                    responsive: {
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
            if (thumbs_slide.length > 0) {
                /*--------------------------
                    THUMB CAROUSEL ACTIVE
                ---------------------------*/
                var thumbs_slide = $('.testmonial__thumb__content__slider');
                var duration = 300;
                var thumbs = 3;

                /*--------------------------
                    MAIN CAROUSEL TRIGGER
                ---------------------------*/
                carousel_elem.on('click', '.owl-next', function() {
                    thumbs_slide.trigger('next.owl.carousel')
                });
                carousel_elem.on('click', '.owl-prev', function() {
                    thumbs_slide.trigger('prev.owl.carousel')
                });
                carousel_elem.on('dragged.owl.carousel', function(e) {
                    if (e.relatedTarget.state.direction == 'left') {
                        thumbs_slide.trigger('next.owl.carousel')
                    } else {
                        thumbs_slide.trigger('prev.owl.carousel')
                    }
                });

                /*--------------------------
                    THUMBS CAROUSEL TRIGGER
                ----------------------------*/
                thumbs_slide.on('click', '.owl-next', function() {
                    carousel_elem.trigger('next.owl.carousel')
                });
                thumbs_slide.on('click', '.owl-prev', function() {
                    carousel_elem.trigger('prev.owl.carousel')
                });
                thumbs_slide.on('dragged.owl.carousel', function(e) {
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
                    loop: loop,
                    items: thumbs,
                    margin: 10,
                    cente: true,
                    autoplay: autoplay,
                    autoplayTimeout: autoplaytimeout,
                    autoplayHoverPause: hover_pause,
                    smartSpeed: slide_speed,
                    nav: false,
                    responsive: {
                        0: {
                            items: 3
                        },
                        768: {
                            items: 3
                        }
                    }
                }).on('click', '.owl-item', function() {
                    var i = $(this).index() - (thumbs + 1);
                    thumbs_slide.trigger('to.owl.carousel', [i, slide_speed, true]);
                    carousel_elem.trigger('to.owl.carousel', [i, slide_speed, true]);
                });
            }
        }
    }

    /*-----------------------------
        SLICK CAROUSEL HANDLER
    ------------------------------*/
    var Slick_Carousel_Script_Handle = function($scope, $) {

        var carousel_elem = $scope.find('.element-ready-carousel-activation').eq(0);

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

    /*-------------------------------
        MAILCHIMP HANDLER
    --------------------------------*/
    var MailChimp_Subscribe_Form_Script_Handle = function($scope, $) {

        var mailchimp_data = $scope.find('.mailchimp_from__box').eq(0);
        var settings = mailchimp_data.data('value'); /*Data Value Also can get by attr().*/
        var random_id = settings['random_id'];
        var post_url = settings['post_url'];

        $("#mc__form__" + random_id).ajaxChimp({
            url: '' + post_url + '',
            callback: function(resp) {
                if (resp.result === "success") {
                    $("#mc__form__" + random_id + " input").hide();
                    $("#mc__form__" + random_id + " button").hide();
                }
            }
        });
    }


    /*------------------------------
        SLICK CAROUSEL HANDLER
    -------------------------------*/
    var Swiper_Carousel_Script_Handle = function($scope, $) {

        var carousel_elem = $scope.find('.element-ready-carousel-activation').eq(0);

        var settings = carousel_elem.data('settings');
        var slideid = settings['slideid'];
        var slide_item_margin = parseInt(settings['slide_item_margin']);
        var autoplay = settings['autoplay'];
        var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
        var animation_speed = parseInt(settings['animation_speed']) || 300;
        var center_mode = settings['center_mode'];
        var rows = settings['rows'] ? parseInt(settings['rows']) : 1;
        var focusonselect = settings['focusonselect'];
        var vertical = settings['vertical'];
        var infinite = settings['infinite'];

        var desktop_item = parseInt(settings['desktop_item']) || 1;
        var desktop_item_scroll = parseInt(settings['desktop_item_scroll']) || 1;

        var medium_item = parseInt(settings['medium_item']) || 1;
        var medium_item_margin = parseInt(settings['medium_item_margin']) || 800;
        var medium_item_scroll = parseInt(settings['medium_item_scroll']) || 1;

        var tablet_item = parseInt(settings['tablet_item']) || 1;
        var tablet_item_margin = parseInt(settings['tablet_item_margin']) || 800;
        var tablet_item_scroll = parseInt(settings['tablet_item_scroll']) || 1;

        var mobile_item = parseInt(settings['mobile_item']) || 1;
        var mobile_item_margin = parseInt(settings['mobile_item_margin']) || 480;
        var mobile_item_scroll = parseInt(settings['mobile_item_scroll']) || 1;

        /* ARROW */
        var arrows = settings['arrows'];
        if (arrows === true) {
            var navigation = {
                nextEl: '.element-ready-carosul-next' + slideid,
                prevEl: '.element-ready-carosul-prev' + slideid,
            };
        } else {
            var navigation = '';
        }

        /* DOTS */
        var dots = settings['dots'];
        var dots_type = settings['dots_type']
        var dynamic_dots = settings['dynamic_dots']
        if (dots === true) {
            var dots_type
            var pagination = {
                el: '.element-ready-carousel-dots' + slideid,
                type: dots_type,
                /* String with type of pagination. Can be "bullets", "fraction", "progressbar" or "custom" */
                dynamicBullets: dynamic_dots,
                dynamicMainBullets: 1,
                clickable: true,
                bulletElement: 'div',
            };
        } else {
            var pagination = '';
        }

        /* SCROLL BAR */
        var slide_scrollbar = settings['slide_scrollbar'];
        var slide_scrollbar_dragable = settings['slide_scrollbar_dragable'];
        var slide_scrollbar_hide = settings['slide_scrollbar_hide'];
        if (slide_scrollbar === true) {
            var scrollbar = {
                el: '.swiper-scrollbar' + slideid,
                draggable: slide_scrollbar_dragable,
                hide: slide_scrollbar_hide,
            };
        } else {
            var scrollbar = '';
        }

        /* SLIDE STYLE */
        var slide_style = settings['slide_style'];

        /* FADE */
        var cross_fade = settings['cross_fade'];

        /* CUBE */
        var cube_shadow = settings['cube_shadow'];
        var cube_item_shadow = settings['cube_item_shadow'];
        var cube_shadow_offset = parseInt(settings['cube_shadow_offset']);
        var cube_shadow_scale = parseInt(settings['cube_shadow_scale']);

        /* COVERFLOW */
        var coverflow_rotate = parseInt(settings['coverflow_rotate']) || 0;
        var coverflow_stretch = parseInt(settings['coverflow_stretch']) || 80;
        var coverflow_depth = parseInt(settings['coverflow_depth']) || 200;
        var coverflow_modifier = parseInt(settings['coverflow_modifier']) || 1;
        var coverflow_shadow = settings['coverflow_shadow'];

        /* FLIP */
        var flip_rotate = parseInt(settings['flip_rotate']);
        var flip_shadow = settings['flip_shadow'];

        if ('slide' === slide_style) {
            var effect = 'slide';
        } else if ('fade' === slide_style) {
            var effect = 'fade';
            var fadeEffect = {
                crossFade: cross_fade,
            };
        } else if ('cube' === slide_style) {
            var effect = 'cube';
            var cubeEffect = {
                shadow: cube_shadow,
                slideShadows: cube_item_shadow,
                shadowOffset: cube_shadow_offset,
                shadowScale: cube_shadow_scale,
            };
        } else if ('coverflow' === slide_style) {
            var effect = 'coverflow';
            var coverflowEffect = {
                rotate: coverflow_rotate,
                stretch: coverflow_stretch,
                depth: coverflow_depth,
                modifier: coverflow_modifier,
                slideShadows: coverflow_shadow,
            };
        } else if ('flip' === slide_style) {
            var effect = 'flip';
            var flipEffect = {
                rotate: flip_rotate,
                slideShadows: flip_shadow,
            };
        } else {
            var effect = 'slide';
            var fadeEffect = '';
            var cubeEffect = '';
            var coverflowEffect = '';
            var flipEffect = '';
        }

        if (vertical === true) {
            var direction = 'vertical';
        } else {
            var direction = 'horizontal';
        }

        if (autoplay === true) {
            var autoplay = {
                delay: autoplay_speed,
            };
        } else {
            var autoplay = '';
        }


        var swipeSlide = new Swiper(carousel_elem, {
            /*breakpointsInverse:true,
            reverseDirection   : true,
            mousewheelControl  : true*/
            navigation: navigation,
            pagination: pagination,
            scrollbar: scrollbar,
            loop: infinite,
            autoplay: autoplay,
            speed: animation_speed,
            slideToClickedSlide: focusonselect,
            freeModeSticky: true,
            direction: direction,
            grabCursor: true,
            freeMode: false,
            centeredSlides: center_mode,
            effect: effect,
            coverflowEffect: coverflowEffect,
            fadeEffect: fadeEffect,
            flipEffect: flipEffect,
            cubeEffect: cubeEffect,
            slidesPerColumn: rows,
            slidesPerGroup: desktop_item_scroll,
            slidesPerView: desktop_item,
            spaceBetween: slide_item_margin,
            breakpoints: {
                1024: {
                    slidesPerView: medium_item,
                    spaceBetween: medium_item_margin,
                    slidesPerGroup: medium_item_scroll,
                },
                768: {
                    slidesPerView: tablet_item,
                    spaceBetween: tablet_item_margin,
                    slidesPerGroup: tablet_item_scroll,
                },
                640: {
                    slidesPerView: mobile_item,
                    spaceBetween: mobile_item_margin,
                    slidesPerGroup: mobile_item_scroll,
                }
            },
        });
    }

    /*-------------------------------
        VIDEO POPUP HANDLER
    --------------------------------*/
    var Video_Popup_Button_Script_Handle = function($scope, $) {

        var video_popup = $scope.find('.video__popup__button').eq(0);
        var settings = video_popup.data('value');
        var random_id = parseInt(settings['random_id']);
        var channel_type = settings['channel_type'];
        var videoModal = $("#video__popup__button" + random_id);
        videoModal.modalVideo({
            channel: channel_type
        });
    }

    /*--------------------------------
        CIRCLE PROGRESSBAR
    ---------------------------------*/
    var Element_Ready_Counter_Circle_Widget_Script_Handle = function($scope, $) {

        var circle_progressbar = $scope.find('.element__ready__progress__counter').eq(0);
        var settings = circle_progressbar.data('settings');
        var random_id = parseInt(settings['random_id']);
        var figure_display = settings['figure_display'];
        var end_fill = settings['end_fill'] ? parseInt(settings['end_fill']) : 50;
        var unit_output_text = settings['unit_output_text'] ? settings['unit_output_text'] : '%';
        var main_background = settings['main_background'];
        var fill_color = settings['fill_color'];
        var progress_fill_color = settings['progress_fill_color'];
        var progress_width = settings['progress_width'] ? parseInt(settings['progress_width']) : 10;
        var empty_fill_opacity = settings['empty_fill_opacity'] ? settings['empty_fill_opacity'] : 0.3;
        var animation_duration = settings['animation_duration'] ? parseInt(settings['animation_duration']) : 2;

        var active_progressbar = $("#element__ready__progress__counter__" + random_id + "");
        active_progressbar.svgprogress({
            figure: figure_display,
            endFill: end_fill,
            unitsOutput: "<span class='suffix__unit__text'>" + unit_output_text + "</span>",
            emptyFill: fill_color,
            progressFill: progress_fill_color,
            progressWidth: progress_width,
            background: main_background,
            emptyFillOpacity: empty_fill_opacity,
            animationDuration: animation_duration,
        });
        active_progressbar.each(function() {
            active_progressbar.appear(function() {
                active_progressbar.trigger('redraw');
            });
        });
    }

    /*---------------------------------
        COUNTDOWN CIRCLE TIMER
    ----------------------------------*/
    var Element_Ready_Countdown_Circle_Timer_Script = function($scope, $) {

        var countdown_time_circle = $scope.find('.element__ready__circle__countdown').eq(0);
        var settings = countdown_time_circle.data('settings');
        var random_id = parseInt(settings['random_id']);
        var animation = settings['animation'];
        var start_angle = parseInt(settings['start_angle']);
        var circle_bg_color = settings['circle_bg_color'];
        var counter_width = settings['counter_width'];
        var bg_width = settings['bg_width'];
        var days_circle_color = settings['days_circle_color'];
        var hours_circle_color = settings['hours_circle_color'];
        var minutes_circle_color = settings['minutes_circle_color'];
        var seconds_circle_color = settings['seconds_circle_color'];
        var circle_days_title = settings['circle_days_title'];
        var circle_days_show = settings['circle_days_show'] == 'yes' ? true : false;
        var circle_hours_title = settings['circle_hours_title'];
        var circle_hours_show = settings['circle_hours_show'] == 'yes' ? true : false;
        var circle_mins_title = settings['circle_mins_title'];
        var circle_mins_show = settings['circle_mins_show'] == 'yes' ? true : false;
        var circle_sec_title = settings['circle_sec_title'];
        var circle_sec_show = settings['circle_sec_show'] == 'yes' ? true : false;

        var countdown = $("#element__ready__circle__countdown__" + random_id + "");

        createTimeCicles();

        $(window).on("resize", windowSize);

        function windowSize() {
            countdown.TimeCircles().destroy();
            createTimeCicles();
            countdown.on("webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd", function() {
                countdown.removeClass("animated fadeIn");
            });
        }

        function createTimeCicles() {
            countdown.addClass("animated fadeIn");
            countdown.TimeCircles({
                animation: "" + animation + "",
                /*smooth , ticks*/
                circle_bg_color: "" + circle_bg_color + "",
                use_background: true,
                fg_width: counter_width,
                /*0.01 to 0.15*/
                bg_width: bg_width,
                start_angle: start_angle,
                time: {
                    Days: { color: "" + days_circle_color + "", text: circle_days_title.replace(/-/g, " "), show: circle_days_show },
                    Hours: { color: "" + hours_circle_color + "", text: circle_hours_title.replace(/-/g, " "), show: circle_hours_show },
                    Minutes: { color: "" + minutes_circle_color + "", text: circle_mins_title.replace(/-/g, " "), show: circle_mins_show },
                    Seconds: { color: "" + seconds_circle_color + "", text: circle_sec_title.replace(/-/g, " "), show: circle_sec_show },
                }
            });
            countdown.on("webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd", function() {
                countdown.removeClass("animated fadeIn");
            });
        }
    }

    /*--------------------------------
        GIVE DONATION CAMPAIGN
    ---------------------------------*/
    var Element_Ready_Give_Campains_Widget_Script = function() {
        $('.campain__prgressbar').each(function() {
            $(this).appear(function() {
                $(this).find('.count__bar').animate({
                    width: $(this).attr('data-percent')
                }, 1000);
                var percent = $(this).attr('data-percent');
                $(this).find('.count').html('<span>' + percent + '</span>');
            });
        });
    }

    /*---------------------------------
        TIMELINE SCRIPT HANDLE
    ----------------------------------*/
    var Timeline_Script_Handle_Data = function($scope, $) {

        var timeline_content = $scope.find('.element__ready__timeline__activation').eq(0);
        var settings = timeline_content.data('settings');
        var timeline_id = settings['timeline_id'];
        var mode = settings['mode'];
        var horizontal_start_postion = settings['horizontal_start_postion'];
        var vertical_start_postion = settings['vertical_start_postion'];
        var force_vartical_in = settings['force_vartical_in'] ? parseInt(settings['force_vartical_in']) : 700;
        var move_item = settings['move_item'] ? parseInt(settings['move_item']) : 1;
        var start_index = settings['start_index'] ? parseInt(settings['start_index']) : 0;
        var vartical_trigger = settings['vartical_trigger'] ? settings['vartical_trigger'] : "15%";
        var show_item = settings['show_item'] ? parseInt(settings['show_item']) : 3;

        try {
            $('#element__ready__timeline__' + timeline_id + ' .timeline').timeline({
                forceVerticalMode: force_vartical_in,
                horizontalStartPosition: horizontal_start_postion,
                mode: mode,
                moveItems: move_item,
                startIndex: start_index,
                verticalStartPosition: vertical_start_postion,
                verticalTrigger: vartical_trigger,
                visibleItems: show_item,
            });
        } catch (err) {
            $('#element__ready__timeline__' + timeline_id + ' .timeline').timeline({
                forceVerticalMode: force_vartical_in,
                horizontalStartPosition: horizontal_start_postion,
                moveItems: move_item,
                startIndex: start_index,
                verticalStartPosition: vertical_start_postion,
                verticalTrigger: vartical_trigger,
                visibleItems: show_item,
            });
        }
    }

    /*-----------------------------------
        TIMELINE ROADMAP HANDALAR
    ------------------------------------*/
    var Timeline_Roadmap_Script_Handle_Data = function($scope, $) {
        var roadmap_content = $scope.find('.element__ready__roadmap__activation').eq(0);
        var settings = roadmap_content.data('settings');
        var random_id = settings['random_id']
        var content = settings['content'];
        var eventsPerSlide = settings['eventsPerSlide'] ? parseInt(settings['eventsPerSlide']) : 4;
        var slide = settings['slide'] ? parseInt(settings['slide']) : 1;
        var prevArrow = settings['prevArrow'] ? settings['prevArrow'] : '<i class="ti ti-left"></i>';
        var nextArrow = settings['nextArrow'] ? settings['nextArrow'] : '<i class="ti ti-right"></i>';
        var orientation = settings['orientation'] ? settings['orientation'] : 'auto';

        $('#element__ready__roadmap__timeline__' + random_id).roadmap(content, {
            eventsPerSlide: eventsPerSlide,
            slide: slide,
            prevArrow: prevArrow,
            nextArrow: nextArrow,
            orientation: orientation,
            eventTemplate: '<div class="single__roadmap__event event">' + '<div class="roadmap__event__icon">####ICON###</div>' + '<div class="roadmap__event__title">####TITLE###</div>' + '<div class="roadmap__event__date">####DATE###</div>' + '<div class="roadmap__event__content">####CONTENT###</div>' + '</div>'
        });
    }

    /*------------------------------------
        PROGRESSBAR HANDALER
    -------------------------------------*/
    var Element_Ready_Progressbar_Script = function() {
        $('.element__ready__prgressbar').each(function() {
            $(this).appear(function() {
                $(this).find('.count__bar').animate({
                    width: $(this).attr('data-percent')
                }, 1000);
                var percent = $(this).attr('data-percent');
                $(this).find('.count').html('<span>' + percent + '</span>');
            });
        });
    }

    /*------------------------------------
        ISOTOPE FILTER ACTIVATION
    -------------------------------------*/
    var Element_Ready_Masonry_Filter_Script = function($scope, $) {

        var grid_elem = $scope.find('.element-ready-filter-activation').eq(0);
        var elem_id = grid_elem.attr('id');
        var grid_activation_id = $('#' + elem_id);
        var settings = grid_elem.data('settings');


        if ('slider' != settings['gallery_type'] || 'genaral' != settings['gallery_type']) {

            var gallery_id = settings['gallery_id'] ? settings['gallery_id'] : 1234;
            var item_selector = '.element__ready__grid__item__' + gallery_id;
            var filter_menu = $('#filter__menu__' + gallery_id + ' li');
            var active_menu_category = settings['active_menu_category'] ? settings['active_menu_category'] : '';


            var layout_mode = settings['layout_mode'];
            if ('masonry' === layout_mode) {
                var layoutMode = 'masonry';
                var layoutOption = {
                    columnWidth: item_selector,
                    gutter: 0,
                };
                var option_cat = layoutMode;
            } else if ('fitRows' === layout_mode) {
                var layoutMode = 'fitRows';
                var layoutOption = {
                    gutter: 0,
                };
                var option_cat = layoutMode;
            } else if ('masonryHorizontal' === layout_mode) {

                var layoutMode = 'masonryHorizontal';
                var layoutOption = {
                    rowHeight: item_selector,
                };
                var option_cat = layoutMode;
            } else if ('fitColumns' === layout_mode) {
                var layoutMode = 'fitColumns';
            } else if ('cellsByColumn' === layout_mode) {
                var layoutMode = 'cellsByColumn';
                var layoutOption = {
                    columnWidth: item_selector,
                    rowHeight: item_selector,
                };
                var option_cat = layoutMode;
            } else {
                var layoutMode = 'fitRows';
                var layoutOption = {
                    gutter: 0,
                };
                var option_cat = layoutMode;
            }

            if (active_menu_category != '') {
                var active_filter = '.' + active_menu_category.toLowerCase();
                filter_menu.removeClass('active');
                $('.filter__menu li[data-filter="' + active_filter + '"]').addClass('active');
            } else {
                var active_filter = '*';
            }

            /*--------------------------------
                ISOTOPE ACTIVE ELEMENT
            ---------------------------------*/

            $(window).on('load', function() {
                if (typeof imagesLoaded === 'function') {
                    imagesLoaded(grid_activation_id, function() {
                        setTimeout(function() {
                            grid_activation_id.isotope({
                                itemSelector: item_selector,
                                resizesContainer: true,
                                filter: active_filter,
                                layoutMode: layoutMode,
                                option_cat: layoutOption,
                            });
                        }, 500);
                    });
                };
            });

            /* --------------------------------
                FILTER MENU SET ACTIVE CLASS 
            ----------------------------------*/
            $(window).on('load', function() {
                filter_menu.on('click', function(event) {
                    $(this).siblings('.active').removeClass('active');
                    $(this).addClass('active');
                    event.preventDefault();
                });
            });

            /*------------------------------
                FILTERING ACTIVE
            -------------------------------- */
            $(window).on('load', function() {
                filter_menu.on('click', function() {
                    var filterValue = $(this).attr('data-filter');
                    grid_activation_id.isotope({
                        filter: filterValue,
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false,
                        }
                    });
                    return false;
                });
            });
        }
    }

    /*-------------------------------
        Masonry
    ---------------------------------*/
    /*var Widget_Image_Masonary_Handler = function ($scope, $) {
        var masonry_elem = $scope.find('.element-ready-masonry-activation').eq(0);
        masonry_elem.imagesLoaded(function () {
            var $grid = $('.masonry-wrap').isotope({
                itemSelector      : '.masonary-item',
                percentPosition   : true,
                transitionDuration: '0.7s',
                masonry           : {
                    columnWidth: '.masonary-item',
                }
            });
        });
    }*/

    /*---------------------------------------
        PRODUCT SLIDER & FILTER & TABS
    -----------------------------------------*/
    /* var Element_Ready_Product_Tabs_Filter = function(){
        $('.tab__menus').on('click', function (e) {
            $('.product__tab__content .tab-pane').removeClass('active in');
            var $this = $(this);
            if (!$this.hasClass('active in')) {
                $this.addClass('active in');
            }
            e.preventDefault();
        });
    } */

    /*---------------------------------------
        EDD CATEGORY SEARCH FEATURES SELECT
    -----------------------------------------*/
    var Element_Ready_Edd_Search_Widget_Script = function($scope, $) {
        var select_Ids = $('select');
        $('select').addClass('wide');
        select_Ids.niceSelect();
    }


    /*---------------------------------------
        EDD CATEGORY SEARCH FEATURES SELECT
    -----------------------------------------*/
    var Element_Ready_Tooltip_Modal_Script = function($scope, $) {
       
        var tooltip_content = $('.element__ready__toltip__modal');
        if (tooltip_content.length > 0) {

            var window_width = $(window).width();
            if (window_width > 767) {

                tooltip_content.tooltipster({
                    animation: 'fade',
                    delay: 10,
                    contentAsHTML: true,
                    interactive: false,
                    maxWidth: 400,
                    theme: ['tooltipster-noir', 'noir-customized'],
                });
            }
        }
    }

    /*---------------------------------------
        FIRST WORD BLOCK CSS
    -----------------------------------------*/
    var Element_Ready_Add_Span_To_First_Word = function($scope, $) {
        /*$(".post__meta li").html(function(){
            var text= $(this).text().trim().split(" ");
            var last = text.pop();
            return text.join(" ") + (text.length > 0 ? " <span class='last__word'>" + last + "</span>" : last);
        });*/

        $(".post__meta li").html(function() {
            var text = $(this).text().trim().split(" ");
            var first = text.shift();
            return (text.length > 0 ? "<span class='first__word'>" + first + "</span> " : first) + text.join(" ");
        });
    }

    /*---------------------------------------

    ----------------------------------------*/
    var Animate_Headline_Script = function($scope, $) {

        var headline_content = $scope.find('.element__ready__animate__heading__activation').eq(0);
        var settings = headline_content.data('settings');
        var wrap_id = headline_content.attr('id');
        var active_wrap = $('#' + wrap_id);
        var random_id = settings['random_id'];
        var animate_type = settings['animate_type'];
        active_wrap.animatedHeadline({
            animationType: animate_type
        });
    }

    var Scroll_Buttom_Script = function($scope, $) {

        var content = $scope.find('.element__ready__scroll__button').eq(0);
        var settings = content.data('options');
        var scroll_type = settings['scroll_type'];

        if ('scroll_top' == scroll_type) {
            var bodyAnimate = $('html,body');
            var scrollToTop = $('.element__ready__scroll__button.scroll_top');
            scrollToTop.on('click', function(e) {
                bodyAnimate.animate({
                    scrollTop: 0
                }, 700);
                return false;
            });
        }
    }

    var Element_Ready_Select_Style_Script = function($scope, $) {
        var select_Ids = $('.edd_price_options select,.single-widgets select,.download__filter__edd__sorting select,.download__orderby__shoring__filter select,select');
        $('select').addClass('wide');
        select_Ids.niceSelect();
        $('.nice-select').after('<div class="clearfix"></div>');
    }

    var Element_Ready_DataTable_Handaler = function($scope, $) {

        var content   = $scope.find('.element__ready__datatable').eq(0);
        var settings  = content.data('options');
        var id        = settings['id'];
        var paging    = settings['show_pagi'];
        var searching = settings['show_searching'];
        var ordering  = settings['ordering'];
        var info      = settings['info'];

        $('.element__ready__datatable__' + id).DataTable({
            paging: paging,
            searching: searching,
            ordering: ordering,
            pageLength: 50,
            "info": info,
        });
    }


    var Element_Ready_Flip_Box_Script = function($scope, $) {
        var elem = $scope.find('.element__ready__flipbox__activation').eq(0);
        var settings = elem.data('options');
        var random_id = elem.attr('id');
        var actve_id = settings['actve_id'];
        var flip_axis = settings['flip_axis'];
        var flip_trigger = settings['flip_trigger'];
        var flip_reverse = settings['flip_reverse'];
        var flip_transition = settings['flip_transition'];

        var activatation_id = $('#' + random_id);
        var inner_wrap_class = activatation_id.find('.flip__box__main__wrap');

        inner_wrap_class.flip({
            axis: flip_axis,
            trigger: flip_trigger,
            reverse: flip_reverse,
            speed: flip_transition,
            front: '.flip__box__front__part',
            back: '.flip__box__back__part'
        });
    }
    var player;
    var Element_Ready_Sticky_Video_Script = function($scope, $) {
        var element = $scope.find('.sticky__video__wrap').eq(0);
        element.find('iframe').attr('class', 'sticky-container__object');
        var options = element.data('options');
        var active_id = element.attr('id');
        new StickyVideo(active_id);




    }


    

    var Element_Ready_Progress_Script = function($scope, $) {
        var element = $scope.find('.element__ready__progressbar__activation').eq(0);
        var settings = element.data('options');
        var id = element.attr('id');
        var activation_id = $('#' + id);

        var percentage = settings['percentage'] ? settings['percentage'] : 70;
        var unit = settings['unit'] ? settings['unit'] : '%';
        var height = settings['height'] ? settings['height'] + 'px' : '10px';
        var radius = settings['radius'] ? settings['radius'] + 'px' : '0px';
        var ShowProgressCount = settings['ShowProgressCount'];
        var animation = settings['animation'];
        var duration = settings['duration'] ? settings['duration'] : 1000;
        var fillBackgroundColor = settings['fillBackgroundColor'] ? settings['fillBackgroundColor'] : '#3498db';
        var backgroundColor = settings['backgroundColor'] ? settings['backgroundColor'] : '#EEEEEE';

        activation_id.appear(function() {
            var active = $(this);
            active.easyBar({
                percentage: percentage,
                unit: unit,
                ShowProgressCount: ShowProgressCount,
                animation: animation,
                duration: duration,
                fillBackgroundColor: fillBackgroundColor,
                backgroundColor: backgroundColor,
                radius: radius,
                height: height,
                width: '100%',
            });
        });
    }


    var Element_Ready_Image_Compare_Script = function($scope, $) {
        var element = $scope.find('.element__ready__image__compare__wrap').eq(0);
        var settings = element.data('options');
        var id = element.attr('id');
        var activation_id = $('#' + id);

        var default_offset_pct = settings['default_offset_pct'] ? settings['default_offset_pct'] : 0.5;
        var orientation = settings['orientation'] ? settings['orientation'] : 'horizontal';
        var before_label = settings['before_label'] ? settings['before_label'] : 'before';
        var after_label = settings['after_label'] ? settings['after_label'] : 'after';
        var no_overlay = settings['no_overlay'];
        var move_slider_on_hover = settings['move_slider_on_hover'];
        var move_with_handle_only = settings['move_with_handle_only'];
        var click_to_move = settings['click_to_move'];

        activation_id.twentytwenty({
            default_offset_pct: default_offset_pct,
            orientation: orientation,
            before_label: before_label,
            after_label: after_label,
            no_overlay: no_overlay,
            move_slider_on_hover: move_slider_on_hover,
            move_with_handle_only: move_with_handle_only,
            click_to_move: click_to_move
        });
    }

    var Element_Ready_Toggle_Tab_Price_Handaler = function($scope, $) {

        var $element = $scope.find('#switch-toggle-tab');
        if ($element.length) {

            var toggleSwitch = $('#switch-toggle-tab label.switch');
            var TabTitle = $('#switch-toggle-tab li');

            var monthTabTitle = $scope.find('#switch-toggle-tab li.month');
            var yearTabTitle = $scope.find('#switch-toggle-tab li.year');
            var monthTabContent = $scope.find('#month');
            var yearTabContent = $scope.find('#year');

            // hidden show deafult;
            monthTabContent.fadeIn();
            yearTabContent.fadeOut();

            function toggleHandle() {
                if (toggleSwitch.hasClass('on')) {
                    yearTabContent.fadeOut();
                    monthTabContent.fadeIn();
                    monthTabTitle.addClass('active');
                    yearTabTitle.removeClass('active');
                } else {
                    monthTabContent.fadeOut();
                    yearTabContent.fadeIn();
                    yearTabTitle.addClass('active');
                    monthTabTitle.removeClass('active');
                }
            };

            monthTabTitle.on('click', function() {
                toggleSwitch.addClass('on').removeClass('off');
                toggleHandle();
                return false;
            });

            yearTabTitle.on('click', function() {
                toggleSwitch.addClass('off').removeClass('on');
                toggleHandle();
                return false;
            });

            toggleSwitch.on('click', function() {
                toggleSwitch.toggleClass('on off');
                toggleHandle();
            });
        }

    }

    var general__countdown_banner = function($scope) {

        var $target     = $scope.find('.element--ready--lite--countdown');
        let date        = $target.data('date');
        let time        = $target.data('time');
        let _label_day  = $target.data('day');
        let _label_hour = $target.data('hour');
        let _label_min  = $target.data('min');
        let _label_sec  = $target.data('sec');
    
        var countDownDate = new Date(date +' '+ time).getTime();
        let label_days  = _label_day;
        let label_hours = _label_hour;
        let label_min   = _label_min;
        let label_sec   = _label_sec;
        
        var x = setInterval(function() {
        var now      = new Date().getTime();
        var distance = countDownDate - now;
        var days     = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours    = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes  = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds  = Math.floor((distance % (1000 * 60)) / 1000);
    
        $target.find('.element--ready--lite--day .er-countdown--num').text(days);
        $target.find('.element--ready--lite--hour .er-countdown--num').text(hours);
        $target.find('.element--ready--lite--min .er-countdown--num').text(minutes);
        $target.find('.element--ready--lite--sec .er-countdown--num').text(seconds);
    
        $target.find('.element--ready--lite--day .er-countdown--word').text(label_days);
        $target.find('.element--ready--lite--hour .er-countdown--word').text(label_hours);
        $target.find('.element--ready--lite--min .er-countdown--word').text(label_min);
        $target.find('.element--ready--lite--sec .er-countdown--word').text(label_sec);
       
            if (distance < 0) {
              
                clearInterval(x);
                $target.find('.element--ready--lite--day .er-countdown--num').text(0);
                $target.find('.element--ready--lite--hour .er-countdown--num').text(0);
                $target.find('.element--ready--lite--min .er-countdown--num').text(0);
                $target.find('.element--ready--lite--sec .er-countdown--num').text(0);

            }
        }, 1000);
       
    
    }

    $(window).on('elementor/frontend/init', function() {


        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Adv_Accordion.default', Element_Ready_Adv_Accordion_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Tabs_Menu_Widget.default', Element_Ready_ER_Tabs_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Price_Tabs_Widget.default', Element_Ready_Price_Tabs_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Animate_Headline.default', Animate_Headline_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Testmonial_Widget.default', Owl_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Teams_Widget.default', Owl_Carousel_Script_Handle);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Welcome_Slides_Widget.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Multi_Gallery_Widget.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Multi_Gallery_Widget.default', Element_Ready_Masonry_Filter_Script);

        /* API Widgets */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Instagram_Widget.default', Slick_Carousel_Script_Handle);

        /* Post */
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-news-post-tab.default', Element_Ready_Tabs_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Post_Carousel.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Post_Carousel.default', Element_Ready_Add_Span_To_First_Word);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-grid-post.default', News_Grid_Post);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-slick-post-slider.default', Slick_Carousel_Script_Handle);

        /* WooCommerce */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_WooCommerce_Products_Widget.default', Element_Ready_Tabs_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_WooCommerce_Products_Widget.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-wc-shopping-cart-popup.default', Element_Ready_WC_Cart_PopUp);

        /* Portfolio */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Portfolio_Carousel_Widget.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Portfolio.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Portfolio.default', Element_Ready_Masonry_Filter_Script);

        /* Image Carousel */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Image_Carousel.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Image_Carousel_Alt.default', Swiper_Carousel_Script_Handle);

        /* Indivisual Items */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Subscriber_Widget.default', MailChimp_Subscribe_Form_Script_Handle);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Video_Button.default', Video_Popup_Button_Script_Handle);

        elementorFrontend.hooks.addAction('frontend/element_ready/element--ready--lite--countdown.default', general__countdown_banner);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Counter_Circle_Widget.default', Element_Ready_Counter_Circle_Widget_Script_Handle);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Countdown_Circle_Widget.default', Element_Ready_Countdown_Circle_Timer_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Give_Campains_Widget.default', Element_Ready_Give_Campains_Widget_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Give_Campains_Widget.default', Slick_Carousel_Script_Handle);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Timeline_Widget.default', Timeline_Script_Handle_Data);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Timeline_Roadmap_Widget.default', Timeline_Roadmap_Script_Handle_Data);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Step_Timeline_Widget.default', Slick_Carousel_Script_Handle);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Progress_Roadmap_Widget.default', Element_Ready_Progressbar_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Flip_Box_Widget.default', Element_Ready_Flip_Box_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Sticky_Video_Widget.default', Element_Ready_Sticky_Video_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Progress_Widget.default', Element_Ready_Progress_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Image_Compare_Widget.default', Element_Ready_Image_Compare_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Scroll_Button.default', Scroll_Buttom_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Data_Table_Widget.default', Element_Ready_DataTable_Handaler);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-toggle-pricing-tab.default', Element_Ready_Toggle_Tab_Price_Handaler);

      
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-popup-search.default', Element_Ready_Menu_Search);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-user-sign-signup-popup.default', Element_Ready_Menu_SigninUp);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-global-popup.default', Element_Ready_Menu_Mini_PopUp);

        /* Easy Digital Downloads */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Search_Widget.default', Element_Ready_Edd_Search_Widget_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Slick_Carousel_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Element_Ready_Masonry_Filter_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Element_Ready_Tooltip_Modal_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Element_Ready_Select_Style_Script);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Thumb_Category_Widget.default', Slick_Carousel_Script_Handle);

        /* Learnpress */
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-grid-course.default', LP_Grid_Course);
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-lp-course-slider.default', Slick_Carousel_Script_Handle);
       
    });


})(jQuery);
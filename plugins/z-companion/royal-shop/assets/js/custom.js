/**************/
// RoyalShopLib
/**************/
(function ($) {
    var RoyalShopLib = {
        init: function (){
            this.bindEvents();
        },
        bindEvents: function (){
             var $this = this;
              if($('#wzta-single-slider').length!==0){
               $this.jssor_slider1_init();
              }
            $this.product_slide_margin_padding();
            $this.MobilenavBar();
            $this.CategoryTabFilter();
            $this.ProductSlide();
            $this.ProductListSlide();
            $this.CategorySlider();
            $this.BrandSlider();
            $this.Top2Slider();
            $this.widget_blog_excerpt();
            // $this.product_slide_2row();
             
        },

         Top2Slider:function(){
                      var owl = $('.wzta-top2-slide');
                           owl.owlCarousel({
                             items:1,
                             nav: true,
                             navText: ["<i class='brand-nav fa fa-angle-left'></i>",
                             "<i class='brand-nav fa fa-angle-right'></i>"],
                             loop:royal_shop.royal_shop_top_slider_optn,
                             dots: false,
                             smartSpeed:500,
                             autoHeight: false,
                             margin:0,
                             autoplay:royal_shop.royal_shop_top_slider_optn,
                             autoplayTimeout: parseInt(royal_shop.royal_shop_top_slider_speed),
                 });
                         // add animate.css class(es) to the elements to be animated
                        function setAnimation ( _elem, _InOut ) {
                          // Store all animationend event name in a string.
                          // cf animate.css documentation
                          var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

                          _elem.each ( function () {
                            var $elem = $(this);
                            var $animationType = 'animated ' + $elem.data( 'animation-' + _InOut );

                            $elem.addClass($animationType).one(animationEndEvent, function () {
                              $elem.removeClass($animationType); // remove animate.css Class at the end of the animations
                            });
                          });
                        }

                      // Fired before current slide change
                        owl.on('change.owl.carousel', function(event) {
                            var $currentItem = $('.owl-item', owl).eq(event.item.index);
                            var $elemsToanim = $currentItem.find("[data-animation-out]");
                            setAnimation ($elemsToanim, 'out');
                        });

                      // Fired after current slide has been changed
                        var round = 0;
                        owl.on('changed.owl.carousel', function(event) {

                            var $currentItem = $('.owl-item', owl).eq(event.item.index);
                            var $elemsToanim = $currentItem.find("[data-animation-in]");
                          
                            setAnimation ($elemsToanim, 'in');
                        })
                        
                        owl.on('translated.owl.carousel', function(event) {
                          //console.log (event.item.index, event.page.count);
                          
                            if (event.item.index == (event.page.count - 1))  {
                              if (round < 1) {
                                round++
                               // console.log (round);
                              } else {
                                owl.trigger('stop.owl.autoplay');
                                var owlData = owl.data('owl.carousel');
                                owlData.settings.autoplay = false; //don't know if both are necessary
                                owlData.options.autoplay = false;
                                owl.trigger('refresh.owl.carousel');
                              }
                            }
                        });
                          
        },

        jssor_slider1_init : function () {
           if(royalshop_obj.royal_shop_sidebar_front_option =='no-sidebar'){
             var widthslide = parseInt('1350');
           }else if(royalshop_obj.royal_shop_sidebar_front_option =='disable-left-sidebar' || royalshop_obj.royal_shop_sidebar_front_option =='disable-right-sidebar'){
             var widthslide = parseInt('1000');
           }else{
             var widthslide = parseInt('930');
           }
               
            var options = {
                $AutoPlay: royalshop_obj.royal_shop_top_slider_optn,                                    //[Optional] Auto play or not, to enable slideshow, this option must be set to greater than 0. Default value is 0. 0: no auto play, 1: continuously, 2: stop at last slide, 4: stop on click, 8: stop on user navigation (by arrow/bullet/thumbnail/drag/arrow key navigation)
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $Idle: parseInt(royalshop_obj.royal_shop_top_slider_speed),                         //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: 1,                     //[Optional] Steps to go for each navigation request by pressing arrow key, default value is 1.
                $SlideDuration: 1000,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide, default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0,                           //[Optional] Space between each slide in pixels, default value is 0
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $Cols is greater than 1, or parking position is not 0)

                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $ActionMode: 1,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                    $Rows: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                    $SpacingX:5,                                  //[Optional] Horizontal space between each item in pixel, default value is 0
                    $SpacingY:5,                                  //[Optional] Vertical space between each item in pixel, default value is 0
                    $Orientation:1                                //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                },

                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 2                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                },

                $ThumbnailNavigatorOptions: {
                    $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $ActionMode: 0,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                    $NoDrag: true,                             //[Optional] Disable drag or not, default value is false
                    $Orientation: 2                                 //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
                }
            };

            var jssor_slider2 = new $JssorSlider$('wzta-single-slider', options);
            /*#region responsive code begin*/
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var parentWidth = jssor_slider2.$Elmt.parentNode.clientWidth;
                if (parentWidth)
                    jssor_slider2.$ScaleWidth(Math.min(parentWidth, widthslide));
                else
                    $Jssor$.$Delay(ScaleSlider, 30);
            }
            ScaleSlider();
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            /*#endregion responsive code end*/
        },
        product_slide_margin_padding : function () {
            $(document).ready(function(){ 
              $(".wzta-product").hover(function() { 
                $('.wzta-slide .owl-stage-outer').css("margin", "-6px -6px -100px"); 
                $('.wzta-slide .owl-stage-outer').css("padding", "6px 6px 100px");
                $('.wzta-slide .owl-nav').css("top", "-52px");
                $('.product-slide-widget .wzta-slide .owl-nav').css("top", "125px");
              }, function() { 
                $('.wzta-slide .owl-stage-outer').css("margin", "0"); 
                $('.wzta-slide .owl-stage-outer').css("padding", "0"); 
                $('.wzta-slide .owl-nav').css("top", "-58px");
                $('.product-slide-widget .wzta-slide .owl-nav').css("top", "119px");
             }); 
             });

          },
          MobilenavBar:function(){
                 //show ,hide
                        jQuery(window).scroll(function (){
                          if(jQuery(this).scrollTop() > 160){
                            jQuery('#royal-shop-mobile-bar').addClass('active').removeClass('hiding');
                             if(jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {
                                  jQuery('#royal-shop-mobile-bar').removeClass('active');
                                }
                          }else{
                            jQuery('#royal-shop-mobile-bar').removeClass('active').addClass('hiding');
                          }

                        });
                   },   

                   widget_blog_excerpt:function(){
          jQuery('.royal-shop-slide-post .entry-content').each(function(){
              var truncated = jQuery(this).text().substr(0, 70);
              //Updating with ellipsis if the string was truncated
              jQuery(this).text(truncated+(truncated.length<70?'':' ..'));
            
            // jQuery(".os-product-excerpt *").not(":first-child").hide();
          });
          },

                   /***********************/        
// Front Page Function
/***********************/  
      CategoryTabFilter:function(){
                         //product slider 
                          if(royalshop_obj.royal_shop_single_row_slide_cat == true){
                          var sliderow = false;
                          }else{
                          var sliderow = true;
                          }
                    // slide autoplay
                            if(royalshop_obj.royal_shop_cat_slider_optn == true){
                            var cat_atply = true;
                            }else{
                            var cat_atply = false; 
                            } 
                     //no.slide
                            if(royalshop_obj.royal_shop_sidebar_front_option =='no-sidebar'){
                             var numslide = parseInt('5');
                            }else if(royalshop_obj.royal_shop_sidebar_front_option =='disable-left-sidebar' || royalshop_obj.royal_shop_sidebar_front_option =='disable-right-sidebar'){
                             var numslide = parseInt('4');
                            }else{
                             var numslide = parseInt('4');
                            }       
                            var owl = $('.wzta-product-cat-slide');
                                     owl.owlCarousel({
                                       items:numslide,
                                       nav: true,
                                       owl2row:sliderow, 
                                       owl2rowDirection: 'ltr',
                                       owl2rowTarget: 'wzta-woo-product-list',
                                       navText: ["<i class='slick-nav fa fa-angle-left'></i>",
                                       "<i class='slick-nav fa fa-angle-right'></i>"],
                                       loop:cat_atply,
                                       dots: false,
                                       smartSpeed: 1800,
                                       autoHeight: false,
                                       margin: 15,
                                       autoplay:cat_atply,
                                       autoplayHoverPause: true,
                                       autoplayTimeout: parseInt(royalshop_obj.royal_shop_cat_slider_speed),
                                       responsive:{
                                       0:{
                                           items:2,
                                           margin:7.5,
                                       },
                                       768:{
                                           items:3,
                                       },
                                       900:{
                                           items:3,
                                       },
                                       1025:{
                                           items:numslide,
                                       }
                                   }
                                });
                          $('#wzta-cat-tab li a:first').addClass('active');
                          $(document).on('click', '#wzta-cat-tab li a', function(e){
                          $('#wzta-cat-tab .tab-content').append('<div class="wzta-loadContainer"> <div class="loader"></div></div>');
                          $(".wzta-product-tab-section .wzta-loadContainer").css("display", "block");
                          $('#wzta-cat-tab li a.active').removeClass("active");
                          $(this).addClass('active');
                                  var data_term_id = $( this ).attr( 'data-filter' );
                                  $.ajax({
                                      type: 'POST',
                                      url: royalshop_obj.ajaxUrl,
                                      data: {
                                        action :'z_companion_cat_filter_ajax',
                                        'data_cat_slug':data_term_id,
                                       },
                                dataType: 'html'
                              }).done( function( response ){
                                if ( response ){
                                 $('#wzta-cat-tab .tab-content').html('<div class="wzta-slide wzta-product-cat-slide owl-carousel"></div> <div class="wzta-loadContainer"> <div class="loader"></div></div>');
                                 $(".wzta-slide.wzta-product-cat-slide.owl-carousel").append(response);
                                 var owl = $('.wzta-product-cat-slide');
                                 owl.owlCarousel({
                                 items:numslide,
                                 nav: true,
                                 owl2row:sliderow, 
                                 owl2rowDirection: 'ltr',
                                 owl2rowTarget: 'wzta-woo-product-list',
                                 navText: ["<i class='slick-nav fa fa-angle-left'></i>",
                                 "<i class='slick-nav fa fa-angle-right'></i>"],
                                 loop:cat_atply,
                                 dots: false,
                                 smartSpeed: 1800,
                                 autoHeight: false,
                                 margin:15,
                                 autoplay:cat_atply,
                                 autoplayHoverPause: true,
                                 autoplayTimeout: parseInt(royalshop_obj.royal_shop_cat_slider_speed),
                                 responsive:{
                                  0:{
                                           items:2,
                                           margin:7.5,
                                       },
                                       768:{
                                           items:3,
                                       },
                                       900:{
                                           items:3,
                                       },
                                       1025:{
                                           items:numslide,
                                       }
                             }
                               });
                            $(".wzta-product-tab-section .wzta-loadContainer").css("display", "none");

                              $(".wzta-product").hover(function() { 
                                $('.wzta-slide .owl-stage-outer').css("margin", "-6px -6px -100px"); 
                                $('.wzta-slide .owl-stage-outer').css("padding", "6px 6px 100px");
                                $('.wzta-slide .owl-nav').css("top", "-52px");
                              }, function() { 
                                $('.wzta-slide .owl-stage-outer').css("margin", "0"); 
                                $('.wzta-slide .owl-stage-outer').css("padding", "0"); 
                                $('.wzta-slide .owl-nav').css("top", "-58px");
                             }); 
             
                            } 
                          } );
                              e.preventDefault();
                           });

              },
       ProductSlide:function(){
                if(royalshop_obj.royal_shop_single_row_prdct_slide == true){
                var sliderow_p = false;
                }else{
                var sliderow_p = true;
                }
                // slide autoplay
                if(royalshop_obj.royal_shop_product_slider_optn == true){
                var cat_atply_p = true;
                }else{
                var cat_atply_p = false; 
                }
                //no.slide
                            if(royalshop_obj.royal_shop_sidebar_front_option =='no-sidebar'){
                             var numslide = parseInt('5');
                            }else if(royalshop_obj.royal_shop_sidebar_front_option =='disable-left-sidebar' || royalshop_obj.royal_shop_sidebar_front_option =='disable-right-sidebar'){
                             var numslide = parseInt('4');
                            }else{
                             var numslide = parseInt('4');
                            }
                var owl = $('.wzta-product-slide');
                     owl.owlCarousel({
                       items:numslide,
                       nav: true,
                       owl2row:sliderow_p, 
                       owl2rowDirection: 'ltr',
                       owl2rowTarget: 'wzta-woo-product-list',
                       navText: ["<i class='slick-nav fa fa-angle-left'></i>",
                       "<i class='slick-nav fa fa-angle-right'></i>"],
                       loop:cat_atply_p,
                       dots: false,
                       smartSpeed: 1800,
                       autoHeight: false,
                       margin:15,
                       autoplay:cat_atply_p,
                       autoplayHoverPause: true,
                       autoplayTimeout: parseInt(royalshop_obj.royal_shop_product_slider_speed),
                       responsive:{
                        0:{
                                           items:2,
                                           margin:7.5,
                                       },
                                       768:{
                                           items:3,
                                       },
                                       900:{
                                           items:3,
                                       },
                                       1025:{
                                           items:numslide,
                                       }
                   }
                });

      },
      ProductListSlide:function(){
                          if(royalshop_obj.royal_shop_single_row_prdct_list == true){
                            var sliderow_l = false;
                            }else{
                            var sliderow_l = true;
                            }
                            // slide autoplay
                            if(royalshop_obj.royal_shop_product_list_slide_optn == true){
                            var cat_atply_l = true;
                            }else{
                            var cat_atply_l = false; 
                            }
                            //no.slide
                            if(royalshop_obj.royal_shop_sidebar_front_option =='no-sidebar'){
                             var numslide = parseInt('4');
                            }else if(royalshop_obj.royal_shop_sidebar_front_option =='disable-left-sidebar' || royalshop_obj.royal_shop_sidebar_front_option =='disable-right-sidebar'){
                             var numslide = parseInt('3');
                            }else{
                             var numslide = parseInt('3');
                            }
                            var owl = $('.wzta-product-list');
                                 owl.owlCarousel({
                                   items:numslide,
                                   nav: true,
                                   owl2row:sliderow_l, 
                                   owl2rowDirection: 'ltr',
                                   owl2rowTarget: 'wzta-woo-product-list',
                                   navText: ["<i class='slick-nav fa fa-angle-left'></i>",
                                   "<i class='slick-nav fa fa-angle-right'></i>"],
                                   loop:cat_atply_l,
                                   dots: false,
                                   smartSpeed: 1800,
                                   autoHeight: false,
                                   margin: 15,
                                   autoplay:cat_atply_l,
                                   autoplayHoverPause: true,
                                   autoplayTimeout: parseInt(royalshop_obj.royal_shop_product_list_slider_speed),
                                   responsive:{
                                   0:{
                                           items:2,
                                           margin:7.5,
                                       },
                                       768:{
                                           items:3,
                                       },
                                       900:{
                                           items:3,
                                       },
                                       1025:{
                                           items:numslide,
                                       }
                               }
                            });
                      
      },
       CategorySlider:function(){
                     // slide autoplay
                     if(royalshop_obj.royal_shop_category_slider_optn == true){
                      var cat_atply_c = true;
                      }else{
                      var cat_atply_c = false; 
                      }
                      // no.slide
                      var column_no = parseInt(royalshop_obj.royal_shop_cat_item_no);

                      var owl = $('.wzta-cat-slide');
                           owl.owlCarousel({
                             items:column_no,
                             nav: true,
                             navText: ["<i class='slick-nav fa fa-angle-left'></i>",
                             "<i class='slick-nav fa fa-angle-right'></i>"],
                             loop:cat_atply_c,
                             dots: false,
                             smartSpeed: 1800,
                             autoHeight: false,
                             margin:15,
                             autoplay:cat_atply_c,
                             autoplayHoverPause: true,
                             autoplayTimeout: parseInt(royalshop_obj.royal_shop_category_slider_speed),
                             responsive:{
                             0:{
                                           items:3,
                                           margin:7.5,
                                       },
                                       768:{
                                           items:4,
                                       },
                                       900:{
                                           items:4,
                                       },
                                       1025:{
                                           items:5,
                                       },
                                       1200:{
                                           items:column_no,
                                       }
                         }
              });

       }, 
       BrandSlider:function(){
                       // slide autoplay
                      if(royalshop_obj.royal_shop_brand_slider_optn == true){
                      var brd_atply = true;
                      }else{
                      var brd_atply = false; 
                      }
                      //no.slide
                            if(royalshop_obj.royal_shop_sidebar_front_option =='no-sidebar'){
                             var numslide = parseInt('8');
                            }else if(royalshop_obj.royal_shop_sidebar_front_option =='disable-left-sidebar' || royalshop_obj.royal_shop_sidebar_front_option =='disable-right-sidebar'){
                             var numslide = parseInt('6');
                            }else{
                             var numslide = parseInt('5');
                            }
                      var owl = $('.wzta-brand');
                           owl.owlCarousel({
                             items:numslide,
                             nav: true,
                             navText: ["<i class='brand-nav fa fa-angle-left'></i>",
                             "<i class='brand-nav fa fa-angle-right'></i>"],
                             loop:brd_atply,
                             dots: false,
                             smartSpeed: 1800,
                             autoHeight: false,
                             margin:15,
                             autoplay:brd_atply,
                             autoplayHoverPause: true,
                             autoplayTimeout: parseInt(royalshop_obj.royal_shop_brand_slider_speed),
                             responsive:{
                             0:{
                                 items:3,
                                 margin:7.5,
                             },
                             600:{
                                 items:4,
                             },
                             1024:{
                                 items:4,
                             },
                             1025:{
                                 items:numslide,
                             }
                         }
                 });
                          
        },
        product_slide_2row : function () {
//**************************/
//owl2row plugin
//**************************/
(function ($, window, document, undefined) {
    Owl2row = function (scope) {
        this.owl = scope;
        this.owl.options = $.extend({}, Owl2row.Defaults, this.owl.options);
        //link callback events with owl carousel here

        this.handlers = {
            'initialize.owl.carousel': $.proxy(function (e) {
                if (this.owl.settings.owl2row) {
                    this.build2row(this);
                }
            }, this)
        };

        this.owl.$element.on(this.handlers);
    };

    Owl2row.Defaults = {
        owl2row: false,
        owl2rowTarget: 'item',
        owl2rowContainer: 'owl2row-item',
        owl2rowDirection: 'utd' // ltr
    };

    //mehtods:
    Owl2row.prototype.build2row = function(thisScope){
    
        var carousel = $(thisScope.owl.$element);
        var carouselItems = carousel.find('.' + thisScope.owl.options.owl2rowTarget);

        var aEvenElements = [];
        var aOddElements = [];

        $.each(carouselItems, function (index, item) {
            if ( index % 2 === 0 ) {
                aEvenElements.push(item);
            } else {
                aOddElements.push(item);
            }
        });

        carousel.empty();

        switch (thisScope.owl.options.owl2rowDirection) {
            case 'ltr':
                thisScope.leftToright(thisScope, carousel, carouselItems);
                break;

            default :
                thisScope.upTodown(thisScope, aEvenElements, aOddElements, carousel);
        }

    };

    Owl2row.prototype.leftToright = function(thisScope, carousel, carouselItems){

        var o2wContainerClass = thisScope.owl.options.owl2rowContainer;
        var owlMargin = thisScope.owl.options.margin;

        var carouselItemsLength = carouselItems.length;

        var firsArr = [];
        var secondArr = [];

        //console.log(carouselItemsLength);

        if (carouselItemsLength %2 === 1) {
            carouselItemsLength = ((carouselItemsLength - 1)/2) + 1;
        } else {
            carouselItemsLength = carouselItemsLength/2;
        }

        //console.log(carouselItemsLength);

        $.each(carouselItems, function (index, item) {


            if (index < carouselItemsLength) {
                firsArr.push(item);
            } else {
                secondArr.push(item);
            }
        });

        $.each(firsArr, function (index, item) {
            var rowContainer = $('<div class="' + o2wContainerClass + '"/>');

            var firstRowElement = firsArr[index];
                firstRowElement.style.marginBottom = owlMargin + 'px';

            rowContainer
                .append(firstRowElement)
                .append(secondArr[index]);

            carousel.append(rowContainer);
        });

    };

    Owl2row.prototype.upTodown = function(thisScope, aEvenElements, aOddElements, carousel){

        var o2wContainerClass = thisScope.owl.options.owl2rowContainer;
        var owlMargin = thisScope.owl.options.margin;

        $.each(aEvenElements, function (index, item) {

            var rowContainer = $('<div class="' + o2wContainerClass + '"/>');
            var evenElement = aEvenElements[index];

            evenElement.style.marginBottom = owlMargin + 'px';

            rowContainer
                .append(evenElement)
                .append(aOddElements[index]);

            carousel.append(rowContainer);
        });
    };

    /**
     * Destroys the plugin.
     */
    Owl2row.prototype.destroy = function() {
        var handler, property;

        for (handler in this.handlers) {
            this.owl.dom.$el.off(handler, this.handlers[handler]);
        }
        for (property in Object.getOwnPropertyNames(this)) {
            typeof this[property] !== 'function' && (this[property] = null);
        }
    };

    $.fn.owlCarousel.Constructor.Plugins['owl2row'] = Owl2row;
})( window.Zepto || window.jQuery, window,  document );

//end of owl2row plugin
},
    }


  RoyalShopLib.init();

})(jQuery);
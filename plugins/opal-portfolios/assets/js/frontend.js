( function( $ ) {

    ! function(b) {
    "use strict";
    b.fn.insightSwiper = function() {
        this.each(function() {
            var e = b(this),
                t = e.data();
            if ("0" != t.queueInit) {
                var i, a = e.children(".swiper-container").first(),
                    n = t.lgItems ? t.lgItems : 1,
                    s = t.mdItems ? t.mdItems : n,
                    r = t.smItems ? t.smItems : s,
                    o = t.xsItems ? t.xsItems : r,
                    l = t.lgGutter ? t.lgGutter : 0,
                    d = t.mdGutter ? t.mdGutter : l,
                    c = t.smGutter ? t.smGutter : d,
                    p = t.xsGutter ? t.xsGutter : c,
                    h = t.speed ? t.speed : 1e3;
                if (t.slideWrap && e.children(".swiper-container").children(".swiper-wrapper").children("div").wrap("<div class='swiper-slide'><div class='swiper-slide-inner'></div></div>"), "auto" == n) var u = {
                    slidesPerView: "auto",
                    spaceBetween: l,
                    
                    breakpoints: {
                        767: {
                            spaceBetween: p
                        },
                        990: {
                            spaceBetween: c
                        },
                        1199: {
                            spaceBetween: d
                        }
                    }
                };
                
                if (u.el = a, u.watchOverflow = !0, t.slideColumns && (u.slidesPerColumn = t.slideColumns), t.initialSlide && (u.initialSlide = t.initialSlide), t.autoHeight && (u.autoHeight = !0), h && (u.speed = h), t.effect && (u.effect = t.effect), t.loop && (u.loop = !0), t.centered && (u.centeredSlides = !0), t.autoplay && (u.autoplay = {
                        pagination: {
                            el: '.swiper-pagination',
                            type: 'bullets',
                        },
                        delay: t.autoplay,
                        disableOnInteraction: !1
                    }), t.freemode && (u.freeMode = !0), t.wrapTools && (i = b('<div class="swiper-tools"></div>'), e.append(i)), t.nav) {

                    if (t.customNav && "" !== t.customNav) {
                        $customBtn = b("#" + t.customNav);
                        var f = $customBtn.find(".slider-prev-btn"),
                            m = $customBtn.find(".slider-next-btn")
                    } else {
                        f = b('<div class="swiper-nav-button swiper-button-prev"><i class="fa fa-chevron-left"></i></div>'), m = b('<div class="swiper-nav-button swiper-button-next"><i class="fa fa-chevron-right"></i></div>');
                        var g = b('<div class="swiper-nav-buttons"></div>');
                        g.append(f).append(m), i ? i.append(g) : e.append(g)
                    }
                    u.navigation = {
                        nextEl: m,
                        prevEl: f
                    }
                }
                
                if (t.pagination) {
                    var v = b('<div class="swiper-pagination"></div>');
                    e.addClass("has-pagination"), i ? i.append(v) : e.append(v), u.pagination = {
                        el: v,
                        clickable: !0
                    }, e.hasClass("pagination-style-07") ? (u.pagination.type = "custom", u.pagination.renderCustom = function(e, t, i) {
                        var a = 100 / i * t;
                        return a = a.toFixed(6), void 0 === e.prevProgressBarWidth && (e.prevProgressBarWidth = a + "%"), '<div class="progressbar"><div class="filled" data-width="' + a + '" style="width: ' + e.prevProgressBarWidth + '"></div></div>'
                    }) : e.hasClass("pagination-style-08") && (u.pagination.type = "custom", u.pagination.renderCustom = function(e, t, i) {
                        var a = 100 / i * t;
                        a = a.toFixed(6), void 0 === e.prevProgressBarWidth && (e.prevProgressBarWidth = a + "%");
                        var n = t.toString(),
                            s = i.toString();
                        return '<div class="fraction"><span class="current">' + (n = n.padStart(2, "0")) + '</span><span class="separator"> / </span><span class="total">' + (s = s.padStart(2, "0")) + "</span></div>" + '<div class="progressbar"><div class="filled" data-width="' + a + '" style="width: ' + e.prevProgressBarWidth + '"></div></div>'
                    })
                }
                
                t.mousewheel && (u.mousewheel = {
                    enabled: !0
                }), t.vertical && (u.direction = "vertical");
                var y, C = new Swiper(u);
                if (t.reinitOnResize) b(window).resize(function() {
                    clearTimeout(y), y = setTimeout(function() {
                        C.destroy(!0, !0), C = new Swiper(a, u)
                    }, 300)
                });
                
            }
        })
    }
}(jQuery), $( document ).ready(function(T){  
        "use strict";  
        $('.grid-item').each(function(e, t){
            var j = T(this);
            setTimeout(function() {
                j.addClass('animate')
            }, 200 * e)
            //console.log(j);

        })
        /*---------------------------------------------- 
         *    Apply Filter        
         *----------------------------------------------*/
        $(window).load(function(){

            if($('.isotope-filter li a').length >0){
                // init Isotope
                var $grid = $('#portfolio_filter .grid').isotope({
                    transitionDuration: 0,
                });
                // filter items on button click
                $('.isotope-filter li a').on( 'click', function() {
                    $(this).parents('ul.isotope-filter').find('li').removeClass('active');
                    $(this).parents().addClass('active');
                    $('#portfolio_filter .grid-item').removeClass( 'animate' );
                    var filterValue = $(this).attr('data-option-value');

                    $grid.isotope({filter: filterValue });
                   
                    $(filterValue).each(function(e, t){
                        var i = T(this);
                        setTimeout(function() {
                            i.addClass('animate')
                        }, 200 * e)
                    })  
                    return false;
                });

            }
        });
            
        $('.grid_masonry').isotope({
          itemSelector: '.grid-item',
          masonry: {
            columnWidth: '.grid-item'
          }
        });

        /*---------------------------------------------- 
         *    lightgallery    
         *----------------------------------------------*/
        function lightgallery_detect_activate( thumbnailSelector ) {
        // if not in elementor edit mode
            var $gridblock_lightbox = $(".lightgallery-detect-container");

            $gridblock_lightbox.lightGallery({
                selector: thumbnailSelector,
                addClass: 'mtheme-lightbox',
                preload: 3,
                hash: false,
                backdropDuration: 400,
                speed: 800,
                startClass: 'lg-start-fade',
                thumbMargin: 1,
                thumbWidth: 50,
                thumbContHeight: 65,
                share: false,
                mode: 'lg-zoom-out', 
                exThumbImage: 'data-exthumbimage'
            });
        }
        lightgallery_detect_activate( '.lightbox-active' );

            
        /*---------------------------------------------- 
         *    Swiper Slider        
         *----------------------------------------------*/

        if (T(document).ajaxStart(),
        T(".tm-swiper").insightSwiper(), setTimeout(function() {
            ! function() {    

                var s = window.location.hash;
                s && 0 < T(s).length && T.smoothScroll({
                    offset: n,
                    scrollTarget: T(s),
                    speed: 800,
                    easing: "linear",

                    beforeScroll: function() {
                        a = !0
                    },
                    afterScroll: function() {
                        a = !1
                    }
                })
            }
        }),  
        "undefined");
    });      
} )( jQuery );

jQuery(document).ready(function($) {
    $(document).ready(function(T) {
        if ( window.elementorFrontend ) {
            
            elementorFrontend.hooks.addAction('frontend/element_ready/opal-portfolio-grid.default', ($scope) => { 
                if( $scope.find(".lightgallery-detect-container").length > 0 ){ 
                   
                    function lightgallery_detect_activate( thumbnailSelector ) {
                        // if not in elementor edit mode
                        var gridblock_lightbox = $( '.lightgallery-detect-container' );
                        if ($.fn.lightGallery) {

                            gridblock_lightbox.lightGallery({
                                selector: thumbnailSelector,
                                addClass: 'mtheme-lightbox',
                                preload: 3,
                                hash: false,
                                backdropDuration: 400,
                                speed: 800,
                                startClass: 'lg-start-fade',
                                thumbMargin: 1,
                                thumbWidth: 50,
                                thumbContHeight: 65,
                                share: false,
                                mode: 'lg-zoom-out', 
                                exThumbImage: 'data-exthumbimage'
                            });        
                        }
                    }
                    lightgallery_detect_activate( '.lightbox-active' ); 
                }
            }); 

            elementorFrontend.hooks.addAction('frontend/element_ready/opal-portfolio-filter.default', ($scope) => { 
                if( $scope.find("#portfolio_filter").length > 0 ){ 
                   $('.grid-item').each(function(e, t){
                        var j = T(this);
                        setTimeout(function() {
                            j.addClass('animate')
                        }, 200 * e)
                        //console.log(j);

                    })
                    /*---------------------------------------------- 
                     *    Apply Filter        
                     *----------------------------------------------*/
                     if($('.isotope-filter li a').length >0){
                        // init Isotope
                        var $grid = $('#portfolio_filter .grid').isotope({
                            transitionDuration: 0,

                        });
                        // filter items on button click
                        $('.isotope-filter li a').on( 'click', function() {
                            $(this).parents('ul.isotope-filter').find('li').removeClass('active');
                            $(this).parents().addClass('active');
                            $('#portfolio_filter .grid-item').removeClass( 'animate' );
                            var filterValue = $(this).attr('data-option-value');

                            $grid.isotope({filter: filterValue });
                           
                            $(filterValue).each(function(e, t){
                                var i = T(this);
                                setTimeout(function() {
                                    i.addClass('animate')
                                }, 200 * e)
                            })  
                            return false;
                        });

                    }
                }
            }); 
        }
    });
          
});

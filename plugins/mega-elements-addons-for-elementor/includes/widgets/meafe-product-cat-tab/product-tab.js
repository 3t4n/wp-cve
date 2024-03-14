// Products Category filter
jQuery( window ).on( 'elementor/frontend/init', () => {
    var TCSliderBase = elementorModules.frontend.handlers.Base.extend({
        onInit: function () {
            elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
            this.initSwiper();
        },    
        getDefaultSettings: function() {
            return {
                "autoplay"       : false,
                "loop"           : false,
                "speed"          : 500,
                "centeredSlides" : false,
                "grabCursor"     : false,
                "freeMode"       : false,
                "effect" 		 : "slide",
                "watchSlidesProgress": true,
                "navigation" : "yes" === this.getElementSettings('PCT_ed_carousel') ? {
                    "nextEl" : '.prod-tab.meafa-navigation-next',
                    "prevEl" : '.prod-tab.meafa-navigation-prev'
                } : false,
                "pagination" : "yes" === this.getElementSettings('PCT_carousel_dots') ? {
                    "el": '.prod-tab.meafa-swiper-pagination',
                    "clickable": true,
                } : false,
                "slidesPerGroup": 1,
                "slidesPerView": 1, //mobile
                "spaceBetween": 30,
                "breakpoints": {
                    // tablet
                    768: {
                        "slidesPerView": 3
                    },
                    // desktop
                    991: {
                        "slidesPerView": 4
                    },
                }
            };
        },

        getDefaultElements: function () {
            return {
                $container: this.findElement('.swiper-container')
            };
        },

        onElementChange: function() {
            this.initSwiper();
        },

        getNavDetails: function() {
            var nav      = this.getElementSettings('PCT_ed_carousel');
            var nav_prev = this.getElementSettings('PCT_prev_icon');
            var nav_next = this.getElementSettings('PCT_next_icon');

            if( nav == 'yes' ) {
                var return_all = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
                var return_alls = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
                var return_all_start = [ '', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ];
                var return_all_end = [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '' ];
                
                if( nav_prev.library != 'svg' && nav_next.library != 'svg' ) {
                    return ( [ '<i class="' + nav_prev.value + '" aria-hidden="true"></i>', '<i class="' + nav_next.value + '" aria-hidden="true"></i>' ] );                    
                }
                
                if ( nav_prev.library == 'svg' && nav_next.library == 'svg' ){
                    return ( [ '<img src="' + nav_prev.value.url + '">', '<img src="' + nav_next.value.url + '">' ] );
                }
                
                if ( nav_prev.library == '' && nav_next.library == 'svg' ){
                    return_all_start.pop();
                    return_all_start.push(nav_next.value.url);
                    return ( [ '', '<img src="' + return_all_start[1] + '">' ] );
                    // return return_all_start;
                }

                if ( nav_prev.library != 'svg' && nav_next.library == 'svg' ){
                    return_all.pop();
                    return_all.push('<img src="' + nav_next.value.url + '">');
                    return return_all;
                }
                
                if ( nav_prev.library == 'svg' && nav_next.library == '' ){
                    return_all_end.reverse();
                    return_all_end.pop();
                    return_all_end.push(nav_prev.value.url);
                    return_all_end.reverse();
                    return ( [ '<img src="' + return_all_end[0] + '">', '' ] );
                }

                if ( nav_prev.library == 'svg' && nav_next.library != 'svg' ){
                    return_alls.reverse();
                    return_alls.pop();
                    return_alls.push('<img src="' + nav_prev.value.url + '">');
                    return_alls.reverse();
                    return return_alls;
                }                
            }
            
            return ( [ '<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>' ] );
        },

        runSwiper: function(){
            var widgetID        = document.getElementById('meafe-post-grid-' + this.getID());
            var sliderContainer = widgetID.querySelector(".swiper-container");

            if(!!sliderContainer.swiper) sliderContainer.swiper.destroy();

            if ( 'undefined' === typeof Swiper ) {
                const asyncSwiper = elementorFrontend.utils.swiper;
                new asyncSwiper( sliderContainer, this.getDefaultSettings()).then( ( newSwiperInstance ) => {
                    mySwiper = newSwiperInstance;
                } );
            } else {
                mySwiper = new Swiper( sliderContainer, this.getDefaultSettings() );
            }
        },

        initSwiper: function initSwiper() {
            if( this.getElementSettings("PCT_ed_carousel") == "yes"){
                this.runSwiper();
            }

            var edCat       = !! this.getElementSettings('PCT_ed_cat');
            var edTitle     = !! this.getElementSettings('PCT_ed_title');
            var edPrice     = !! this.getElementSettings('PCT_ed_price');
            var edCart      = !! this.getElementSettings('PCT_ed_cart');
            var edQuickView = !! this.getElementSettings('PCT_ed_quick_view');
            var edWishlist  = !! this.getElementSettings('PCT_ed_wishlist');
            var edBadge     = !! this.getElementSettings('PCT_ed_badge');
            var edExcerpt   = !! this.getElementSettings('PCT_ed_excerpt');
            var excerptNo   = this.getElementSettings('PCT_excerpt_number');
            var postNo      = this.getElementSettings('PCT_number');
            var layout      = this.getElementSettings('PCT_layouts');
            var prodType    = this.getElementSettings('PCT_type');
            var prodSelect  = this.getElementSettings('PCT_cat_select');
            var edCarousel  = !! this.getElementSettings('PCT_ed_carousel');
            var edDots      = !! this.getElementSettings('PCT_carousel_dots');
            var carousel    = this.getDefaultSettings();
            var filterItem  = jQuery('.post-filter-tab-wrapper li');
            var widgetId    = document.getElementById('meafe-post-grid-' + this.getID());
            var navDetails  = this.getNavDetails();

            const runAjaxPostFilter = ( widgetID, carousel, catID, edCat, edTitle, edPrice, edCart, edQuickView, edWishlist, edExcerpt, excerptNo, postNo, layout, edBadge, prodType, prodSelect, catText, edCarousel, edDots, navDetails ) => {

                let ajaxData = {
                    cat_id: catID,
                    edCat: edCat,
                    edTitle: edTitle,
                    edPrice: edPrice,
                    edCart: edCart,
                    edQuickView: edQuickView,
                    edWishlist: edWishlist,
                    edExcerpt: edExcerpt,
                    excerptNo: excerptNo,
                    postNo: postNo,
                    layout: layout,
                    edBadge: edBadge,
                    prodType: prodType,
                    prodSelect: prodSelect,
                    catText: catText,
                    edCarousel: edCarousel,
                    edDots: edDots,
                    navDetails: navDetails
                }

                fetch(
                    `${meafe_publicVars.ajaxUrl}?action=meafe_products_tab_content`,
                    {
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                        },
                        method: 'POST',
                        body: JSON.stringify({ajaxData})
                    }
                )
                    .then((r) => r.json())
                    .then((r) =>{
                        if (r.success) {
                            let wrapperClass = widgetID.querySelector('.meafe-products-wrapper');
                            wrapperClass.innerHTML = '';
                            wrapperClass.innerHTML = r.data; 
                            innerWrapper           = wrapperClass.querySelector('.swiper-container')
                            if( edCarousel ) {
                                mySwiper = new Swiper( innerWrapper, carousel );
                            }
                        }
                        
                    })
            }

            filterItem.each( function( el, index ) {
                jQuery(index).on('click', function(){
                    filterItem.each( function( tabs, elements ) {
                        jQuery(elements).removeClass('active') //Remove active class initially from all categories
                    })
                    jQuery(index).addClass('active') //Add active class on click
                    var catText = jQuery(index).find('a').text()
                    var catID  = jQuery(index).attr('data-tab').split('-')[2];
                    if( catID == undefined ){
                        catID = ''
                    }
                    runAjaxPostFilter( widgetId, carousel, catID, edCat, edTitle, edPrice, edCart, edQuickView, edWishlist, edExcerpt, excerptNo, postNo, layout, edBadge, prodType, prodSelect, catText, edCarousel, edDots, navDetails )
                })
            })
        }

    });

    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( TCSliderBase, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/meafe-product-cat-tab.default', addHandler );

} );
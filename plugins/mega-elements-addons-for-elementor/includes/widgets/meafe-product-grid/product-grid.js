// Products grid

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
                "navigation" : "yes" === this.getElementSettings('PG_ed_carousel') ? {
                    "nextEl" : '.prod-grid.meafa-navigation-next',
                    "prevEl" : '.prod-grid.meafa-navigation-prev'
                } : false,
                "pagination" : "yes" === this.getElementSettings('PG_carousel_dots') ? {
                    "el": '.prod-grid.meafa-swiper-pagination',
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

        initSwiper: function initSwiper() {
            if( this.getElementSettings("PG_ed_carousel") == "yes"){
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
            }
        }
    });

    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( TCSliderBase, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/meafe-product-grid.default', addHandler );
    
} );
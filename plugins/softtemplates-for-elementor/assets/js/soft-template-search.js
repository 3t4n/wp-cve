( function( $ ) {
    "use strict";

    $( window ).on( 'elementor/frontend/init', function () {
        var testimonialCarousel = elementorModules.frontend.handlers.Base.extend({
            onInit: function () {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                this.wrapper = this.$element.find('.elementor-soft-template-search');
                this.run();
            },  
            run: function() {
                const wrapper = this.wrapper;
            
                wrapper.magnificPopup({
                    mainClass: 'mfp-fade stfe-magnific-popup',
                    delegate: 'a.stfe-search-modal',
                    removalDelay: 500,
                    midClick: true,
                    showCloseBtn: true,
                    closeBtnInside: false,
                    prependTo: wrapper,
                    type: "inline",      
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: "auto"
                });
            }
        });

        var handlersClassMap = {
		    'soft-template-search.default': testimonialCarousel
		};

        const addHandler = ( $element ) => {
            elementorFrontend.elementsHandler.addHandler( testimonialCarousel, {
                $element,
            } );
        };

        elementorFrontend.hooks.addAction( 'frontend/element_ready/soft-template-search.default', addHandler );
    });

} )( jQuery );
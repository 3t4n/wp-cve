;(function( $, window ){
    "use strict";

    var HtMegaBlocks = {

        /**
         * Slick Slider
         * @param string Slider Selector class
         */
        initSlickSlider: function( $slider ){
            const settings = $($slider).data('settings');
            if( settings ){
                $($slider).slick({
                    ...settings,
                    prevArrow: '<button type="button" class="slick-prev"><i class="dashicons dashicons-arrow-left-alt2"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="dashicons dashicons-arrow-right-alt2"></i></button>',
                });
            }
        },

        /**
         * Accordion
         * @param string Accordion Toggle Selector (accordion header class)
         */
        initAccordion: function( $trigger ){
            $($trigger).on('click', function() {
                const $accordionCard = $(this).closest('.htmega-accordion-card'),
                    $accordionBody = $(this).siblings('.htmega-accordion-card-body'),
                    $siblingsCard = $accordionCard.siblings();
                if($accordionCard.hasClass('htmega-accordion-card-active')) {
                    $accordionCard.removeClass('htmega-accordion-card-active');
                    $accordionBody.slideUp();
                } else {
                    $siblingsCard.each(function() {
                        const $card = $(this);
                        $card.removeClass('htmega-accordion-card-active');
                        $card.find('.htmega-accordion-card-body').slideUp();
                    });
                    $accordionCard.addClass('htmega-accordion-card-active');
                    $accordionBody.slideDown();
                    $accordionCard.find('.slick-slider').slick('refresh');
                }
            })
        },

        /**
         * InitTab
         * @param string InitTab Toggle Selector
         */
        initTab: function( $trigger ){
            $($trigger).on('click', function() {
                const $this = $(this)[0],
                    $target = $this.dataset.tabTarget,
                    $tab = $this.closest('.htmega-tab');

                $($this).addClass('htmega-tab-nav-item-active').siblings().removeClass('htmega-tab-nav-item-active');
                $($tab).find(`[data-tab-id="${$target}"]`).show().siblings().hide();
                $($tab).find(`[data-tab-id="${$target}"]`).find('.slick-slider').slick('refresh');
            })
        },


    };

    $(".htmega-slick-slider").each(function(){
        HtMegaBlocks.initSlickSlider($(this));
    });

    $(".htmega-accordion-card-header").each(function(){
        HtMegaBlocks.initAccordion($(this));
    });
    $(".htmega-tab-nav-item").each(function(){
        HtMegaBlocks.initTab($(this)[0]);
    });

})(jQuery, window);

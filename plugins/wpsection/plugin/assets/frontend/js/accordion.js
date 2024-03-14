/**
 * Element Name: Accordion
 */

;(function ($, elementor) {

    'use strict';

    var widgetAccordion = function ($scope, $) {

        $(document).on('click', '.wpsection-accordion-item > .wpsection-accordion-title', function () {

            let thisTitle = $(this),
                thisItem = $(this).parent(),
                allItems = thisItem.parent(),
                thisContent = thisItem.find('.wpsection-accordion-content'),
                thisIcon = thisTitle.find('.toggle-icon');

            if (!thisItem.hasClass('active')) {
                allItems.find('.wpsection-accordion-item').removeClass('active').find('.wpsection-accordion-content').slideUp();
                allItems.find('.toggle-icon').removeClass('icon-minus');
                thisContent.slideToggle();
                thisItem.toggleClass('active');
                thisIcon.toggleClass('icon-minus icon-plus');
            }
        });

    };

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wpsection-accordion.default', widgetAccordion);
    });


    // Main Slider Carousel
if ($('.banner-carousel').length) {
    $('.banner-carousel').owlCarousel({
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        loop:true,
        margin:0,
        dots: true,
        nav:true,
        singleItem:true,
        smartSpeed: 500,
        autoplay: true,
        autoplayTimeout:6000,
        navText: [ '<span class="flaticon-left-arrow"></span>', '<span class="flaticon-right-arrow"></span>' ],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1024:{
                items:1
            }
        }
    });         
}
      

}(jQuery, window.elementorFrontend));
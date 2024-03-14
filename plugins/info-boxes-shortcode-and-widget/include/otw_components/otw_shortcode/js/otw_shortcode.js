
'use strict';

jQuery(document).ready(function () {

    //tabs
    otw_shortcode_tabs(jQuery('.otw-sc-tabs'));

    //content toggle
    otw_shortcode_content_toggle(jQuery('.toggle-trigger'), jQuery('.toggle-trigger.closed'));

    //accordions
    otw_shortcode_accordions(jQuery('.otw-sc-accordion'));

    //faqs
    otw_shortcode_faq(jQuery('.otw-sc-faq'));

    //shadow overlay
    otw_shortcode_shadow_overlay(jQuery('.shadow-overlay'));
    
    //category filters
    otw_shortcode_category_filter( '.otw-portfolio-filter', '.otw-portfolio' );

    //messages
    jQuery(".otw-sc-message.closable-message").find(".close-message").on( 'click', function () {
        jQuery(this).parent(".otw-sc-message").fadeOut("fast");
    });

    //testimonials
    otw_shortcode_testimonials(jQuery('.otw-sc-testimonials'));

    //scroll to top
    otw_shortcode_scroll_to_top(jQuery('.scroll-top a'));

    //tables
    otw_shortcode_sortable_table(jQuery('.footable'));

    /*
     * Init the count down script for front mode 
     */
    jQuery('.otw-countdown').each(function (index) {
        otw_shortcode_count_down(jQuery(this));
    });

    /*
     * Init the progress bar in front mode
     */
    jQuery(".otw-b-meter > span").each(function () {
        animate_progressbar(jQuery(this));
    });

    /*
     *Init the animation images in front mode  
     */
    otw_start_animated_image(jQuery(".otw-b-animated-image"));

    /**
     * Clients slider init in front mode
     */
    jQuery(".otw-b-carousel:not(.otw-b-testimonials-slider)").each(function () {
        otw_start_client_caroucel(jQuery(this));
    });

    /*
     * Init of testimonials in front mode
     */
    jQuery(".otw-b-testimonials-slider").each(function () {
        otw_testimonials_start(jQuery(this));
    });

    //init gallery callbacks
    jQuery(".otw-b-gallery").each(function () {
        jQuery('a', jQuery(this)).on( 'click', function (e) {
            if (jQuery(this).hasClass('otw-external') === false) {
                e.preventDefault();
                jQuery(this).removeClass('otw-b-active');
            }
        });
        jQuery('li', jQuery(this).find('.otw-b-gallery-thumbs')).on( 'click', function () {
            generateGallery(this);
        });
        generateGallery(this);
    });
});
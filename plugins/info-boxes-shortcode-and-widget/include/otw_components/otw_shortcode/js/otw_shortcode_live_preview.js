jQuery(function ($) {

    'use strict';
    
    /*tabs layout*/
    otw_shortcode_tabs(jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-tabs'));
    otw_shortcode_tabs(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-tabs'));
    otw_shortcode_tabs(jQuery('.otw_live_preview_shortcode .otw-sc-tabs'));
    
    /*content toggle*/
    otw_shortcode_content_toggle(jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-toggle > .toggle-trigger'), jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-toggle > .toggle-trigger.closed'));
    otw_shortcode_content_toggle(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-toggle > .toggle-trigger'), jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-toggle > .toggle-trigger.closed'));
    otw_shortcode_content_toggle(jQuery('.otw_live_preview_shortcode').find('.otw-sc-toggle > .toggle-trigger'), jQuery('.otw_live_preview_shortcode').find('.otw-sc-toggle > .toggle-trigger.closed'));

    //accordions
    otw_shortcode_accordions(jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-accordion'));
    otw_shortcode_accordions(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-accordion'));
    otw_shortcode_accordions(jQuery('.otw_live_preview_shortcode').find('.otw-sc-accordion'));

    //faq
    otw_shortcode_faq(jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-faq'));
    otw_shortcode_faq(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-faq'));
    otw_shortcode_faq(jQuery('.otw_live_preview_shortcode').find('.otw-sc-faq'));

    //showdow overlay
    otw_shortcode_shadow_overlay(jQuery('#otw-shortcode-preview').contents().find('body').find('.shadow-overlay'));
    otw_shortcode_shadow_overlay(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.shadow-overlay'));
    otw_shortcode_shadow_overlay(jQuery('.otw_live_preview_shortcode').find('.shadow-overlay'));

    //contact form
    jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-contact-form form').on( 'submit', function () {
        return false;
    });
    jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-contact-form form').on( 'submit', function () {
        return false;
    });
    jQuery('.otw_live_preview_shortcode').find('.otw-sc-contact-form form').on( 'submit', function () {
        return false;
    });

    otw_shortcode_testimonials(jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-sc-testimonials'));
    otw_shortcode_testimonials(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.otw-sc-testimonials'));
    otw_shortcode_testimonials(jQuery('.otw_live_preview_shortcode').find('.otw-sc-testimonials'));


    //tables
    otw_shortcode_sortable_table(jQuery('#otw-shortcode-preview').contents().find('body').find('.footable'));
    otw_shortcode_sortable_table(jQuery('.otw-shortcode-preview iframe').contents().find('body').find('.footable'));
    otw_shortcode_sortable_table(jQuery('.otw_live_preview_shortcode').find('.footable'));

    jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-countdown').each(function (index) {
        otw_shortcode_count_down($(this));
    });

    jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-b-meter > span').each(function (index) {
        animate_progressbar(jQuery(this));
    });
    
    jQuery('.otw_live_preview_shortcode').find('.otw-b-meter > span').each(function (index) {
        animate_progressbar(jQuery(this));
    });

    otw_start_animated_image(jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-b-animated-image'));
    otw_start_animated_image(jQuery('.otw_live_preview_shortcode').find('.otw-b-animated-image'));


    jQuery("#otw-shortcode-preview").contents().find('.otw-b-logo-carousel').each(function(index) {
        otw_start_client_caroucel_preview(jQuery(this));
    });



    /*
     * Testimonials slider initialization in preview mode 
     */
    jQuery('#otw-shortcode-preview').contents().find('body').find('.otw-b-testimonials-slider').each(function (index) {
        otw_testimonials_start($(this));
    });



});

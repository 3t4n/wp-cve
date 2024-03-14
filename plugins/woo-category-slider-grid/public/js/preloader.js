; (function ($) {
    'use strict'
    jQuery('body').find('.sp-wcsp-slider-section.wcsp-preloader').each(function (e) {
        var wcsp_id = $(this).attr('id'),
            parents_class = jQuery('#' + wcsp_id).parent('.sp-wcsp-slider-area'),
            parents_siblings_id = parents_class.find('.sp-wcsp-preloader').attr('id');
        jQuery(document).ready(function() {
            jQuery('#' + parents_siblings_id).animate({ opacity: 0 }, 600).remove();
            jQuery('#' + wcsp_id).animate({ opacity: 1 }, 600)  
        })
    })                       
})(jQuery)

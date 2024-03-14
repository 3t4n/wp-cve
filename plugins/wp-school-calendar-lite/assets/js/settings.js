/* global WPSC_Admin, Infinity */

jQuery(document).ready(function($) {
    "use strict";
    
    $('.wpsc-select').select2({
        minimumResultsForSearch: Infinity,
        width: "25em"
    });
    
    $('.wpsc-select-location-posts').select2({
        minimumInputLength: 3,
        minimumResultsForSearch: 0,
        width: "100%",
        closeOnSelect: true,
        data: WPSC_Admin.post_type_posts,
        placeholder: WPSC_Admin.placeholder
    });
});
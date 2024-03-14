/* global WPSC_Admin */

jQuery(document).ready(function($) {
    "use strict";
    
    $('#start-datepicker').datepicker({
        dateFormat: "mm/dd/yy",
        showOn    : "button",
        buttonText: WPSC_Admin.datepickerButton
    }).on('change', function(){
        endDate.datepicker("option", "minDate", getDate(this));
    });

    var endDate = $('#end-datepicker').datepicker({
        dateFormat: "mm/dd/yy",
        showOn    : "button",
        buttonText: WPSC_Admin.datepickerButton
    });
    
    function getDate(element) {
        var date;
        
        try {
            date = $.datepicker.parseDate("mm/dd/yy", element.value);
        } catch( error ) {
            date = null;
        }
 
        return date;
    }
    
    try {
        $("#end-datepicker").datepicker("option", "minDate", new Date($('#start-datepicker').val() ) );
    } catch(e) {}
});
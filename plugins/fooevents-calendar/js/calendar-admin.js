(function($) {

    jQuery('.fooevents-tooltip').tooltip({
        tooltipClass: "fooevents-tooltip-box",
    });
    
    jQuery('.woocommerce-events-color-field').wpColorPicker();

    if (jQuery( "#WooCommerceEventsMetaEvent" ).length ) {

        if( (typeof localObj === "object") && (localObj !== null) )
        {

            jQuery('.WooCommerceEventsMetaBoxDate').datepicker({

                showButtonPanel: true,
                closeText: localObj.closeText,
                currentText: localObj.currentText,
                monthNames: localObj.monthNames,
                monthNamesShort: localObj.monthNamesShort,
                dayNames: localObj.dayNames,
                dayNamesShort: localObj.dayNamesShort,
                dayNamesMin: localObj.dayNamesMin,
                dateFormat: localObj.dateFormat,
                firstDay: localObj.firstDay,
                isRTL: localObj.isRTL,

            });

        } else {

            jQuery('#WooCommerceEventsMetaBoxDate').datepicker();

        }
    
    }
    
    if (jQuery("#fooevents-eventbrite-import-output" ).length) {
        
        jQuery('.wrap').on('click', '#fooevents-eventbrite-import', function(e) {
            
            jQuery('#fooevents-eventbrite-import-output').show().html('Fetching...');
            
            var data = {
                'action': 'fooevents-eventbrite-import',
                'import': true
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                
                jQuery('#fooevents-eventbrite-import-output').html(response);
                
            });
            
            return false;
        });
        
    }

})(jQuery);
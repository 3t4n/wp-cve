(function ($) {
    'use strict';

    jQuery(function () {
        /** Adv order filter */
        jQuery("input[name='filter_delivery_from_date']").parent().css({ 'box-sizing': 'border-box', 'border': '1px solid #ccc', 'padding': '10px 6px', 'margin': '10px 0px', 'width': '100%' });

        if (typeof jQuery.fn.datepicker != 'undefined') {
            jQuery("input[name='filter_delivery_from_date'], input[name='filter_delivery_to_date']").datepicker({
                dateFormat: 'yy/mm/dd',
                showButtonPanel: true,
                closeText: 'Clear',
                onClose: function (dateText, inst) {
                    if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                        document.getElementById(this.id).value = '';
                    }
                }
            });
        } else {
            jQuery("input[name='filter_delivery_from_date'], input[name='filter_delivery_to_date']").attr('placeholder', 'YYYY/MM/DD')
        }
    });

})(jQuery)
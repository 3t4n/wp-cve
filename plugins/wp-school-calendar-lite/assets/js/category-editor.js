/* global WPSC_Admin */

jQuery(document).ready(function($) {
    "use strict";
    
    $('.color-picker').wpColorPicker();
    
    $('#the-list.wpsc-list-table').on('click', '.delete', function(){
        if (confirm(WPSC_Admin.warnDelete)) {
            return true;
        }
        
        return false;
    });
    
    $('table tbody.wpsc-list-table').sortable({
        items: 'tr',
        axis: 'y',
        cursor: 'move',
        handle: 'td span.wpsc-sortable-handle',
        scrollSensitivity: 40,
        helper: function (event, ui) {
            ui.children().each(function () {
                $(this).width($(this).width());
            });
            return ui;
        },
        start: function (event, ui) {
            ui.item.css('background-color', '#f6f6f6');
        },
        stop: function (event, ui) {
            ui.item.removeAttr('style');
        }
    });
});
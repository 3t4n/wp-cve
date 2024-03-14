
/*global jQuery:false*/

jQuery(document).ready(function () {
    LSOW_JS.init();
    // Run tab open/close event
    LSOW_Tab.event();
});

// Init all fields functions (invoked from ajax)
var LSOW_JS = {
    init: function () {
        // Run tab open/close
        LSOW_Tab.init();
        // Load colorpicker if field exists
        LSOW_ColorPicker.init();
    }
};


var LSOW_ColorPicker = {
    init: function () {
        var $colorPicker = jQuery('.lsow-colorpicker');
        if ($colorPicker.length > 0) {

            $colorPicker.wpColorPicker();

        }
    }
};

var LSOW_Tab = {
    init: function () {
        // display the tab chosen for initial display in content
        jQuery('.lsow-tab.selected').each(function () {
            LSOW_Tab.check(jQuery(this));
        });
    },
    event: function () {
        jQuery(document).on('click', '.lsow-tab', function () {
            LSOW_Tab.check(jQuery(this));
        });
    },
    check: function (elem) {
        var chosen_tab_name = elem.data('target');
        elem.siblings().removeClass('selected');
        elem.addClass('selected');
        elem.closest('.lsow-inner').find('.lsow-tab-content').removeClass('lsow-tab-show').hide();
        elem.closest('.lsow-inner').find('.lsow-tab-content.' + chosen_tab_name + '').addClass('lsow-tab-show').show();
    }
};
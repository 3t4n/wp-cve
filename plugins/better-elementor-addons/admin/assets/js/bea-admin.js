
/*global jQuery:false*/

jQuery(document).ready(function () {
    BEA_JS.init();
    // Run tab open/close event
    BEA_Tab.event();
});

// Init all fields functions (invoked from ajax)
var BEA_JS = {
    init: function () {
        // Run tab open/close
        BEA_Tab.init();
        // Load colorpicker if field exists
        BEA_ColorPicker.init();
    }
};


var BEA_ColorPicker = {
    init: function () {
        var $colorPicker = jQuery('.bea-colorpicker');
        if ($colorPicker.length > 0) {

            $colorPicker.wpColorPicker();

        }
    }
};

var BEA_Tab = {
    init: function () {
        // display the tab chosen for initial display in content
        jQuery('.bea-tab.selected').each(function () {
            BEA_Tab.check(jQuery(this));
        });
    },
    event: function () {
        jQuery(document).on('click', '.bea-tab', function () {
            BEA_Tab.check(jQuery(this));
        });
    },
    check: function (elem) {
        var chosen_tab_name = elem.data('target');
        elem.siblings().removeClass('selected');
        elem.addClass('selected');
        elem.closest('.bea-inner').find('.bea-tab-content').removeClass('bea-tab-show').hide();
        elem.closest('.bea-inner').find('.bea-tab-content.' + chosen_tab_name + '').addClass('bea-tab-show').show();
    }
};
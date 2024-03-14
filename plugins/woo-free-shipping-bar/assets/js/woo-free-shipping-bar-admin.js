/**
 * Created by huyko on 02/06/2017.
 */
'use strict';
jQuery(document).ready(function () {
    jQuery('.vi-ui.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });

    /*Setup tab*/
    // var tabs,
    //     tabEvent = false,
    //     initialTab = 'general',
    //     navSelector = '.vi-ui.menu',
    //     navFilter = function (el) {
    //         // return jQuery(el).attr('href').replace(/^#/, '');
    //     },
    //     panelSelector = '.vi-ui.tab',
    //     panelFilter = function () {
    //         jQuery(panelSelector + ' a').filter(function () {
    //             return jQuery(navSelector + ' a[title=' + jQuery(this).attr('title') + ']').size() != 0;
    //         });
    //     };
    //
    // // Initializes plugin features
    // jQuery.address.strict(false).wrap(true);
    //
    // if (jQuery.address.value() == '') {
    //     jQuery.address.history(false).value(initialTab).history(true);
    // }
    //
    // // Address handler
    // jQuery.address.init(function (event) {
    //
    //     // Adds the ID in a lazy manner to prevent scrolling
    //     jQuery(panelSelector).attr('id', initialTab);
    //
    //     panelFilter();
    //
    //     // Tabs setup
    //     tabs = jQuery('.vi-ui.menu')
    //         .vi_tab({
    //             history: true,
    //             historyType: 'hash'
    //         })
    //
    //     // Enables the plugin for all the tabs
    //     jQuery(navSelector + ' a').click(function (event) {
    //         tabEvent = true;
    //         // jQuery.address.value(navFilter(event.target));
    //         tabEvent = false;
    //         return true;
    //     });
    //
    // });

    jQuery('.vi-ui.checkbox').checkbox();
    jQuery('.vi-ui.dropdown').dropdown();
    /*Save Submit button*/
    jQuery('.wfsb-submit').one('click', function () {
        jQuery(this).addClass('loading');
    });




    jQuery('.wfspb-progress-percent').dependsOn({
        'input[name="wfspb-param[enable-progress]"]': {
            checked: true
        }
    });


    jQuery('input[name="wfspb-param[bg-color]"]').colorPicker({
        renderCallback: function ($elm, toggled) {
            var id = $elm.attr('id');
            if ($elm.text) {
                jQuery('#wfspb-top-bar').css('background-color', $elm.text);
            }
        }
    });

    jQuery('input[name="wfspb-param[text-color]"]').colorPicker({
        renderCallback: function ($elm, toggled) {
            var id = $elm.attr('id');
            if ($elm.text) {
                jQuery('#wfspb-top-bar').css('color', $elm.text);
            }
        }
    });

    jQuery('input[name="wfspb-param[link-color]"]').colorPicker({
        renderCallback: function ($elm, toggled) {
            var id = $elm.attr('id');
            if ($elm.text) {
                jQuery('#wfspb-top-bar #wfspb-main-content a').css('color', $elm.text);
            }
        }
    });

    jQuery('input[name="wfspb-param[progress-text-color]"]').colorPicker({
        renderCallback: function ($elm, toggled) {
            var id = $elm.attr('id');
            if ($elm.text) {
                jQuery('#wfspb-label').css('color', $elm.text);
            }
        }
    });

    jQuery('input[name="wfspb-param[bg-color-progress]"]').colorPicker({
        renderCallback: function ($elm, toggled) {
            var id = $elm.attr('id');
            if ($elm.text) {
                jQuery('#wfspb-progress').css('background-color', $elm.text);
            }
        }
    });

    jQuery('input[name="wfspb-param[bg-current-progress]"]').colorPicker({
        renderCallback: function ($elm, toggled) {
            var id = $elm.attr('id');
            if ($elm.text) {
                jQuery('#wfspb-current-progress').css('background-color', $elm.text);
            }
        }
    });

    jQuery('input[name="wfspb-param[position]"]').on('change', function () {
        var data = jQuery(this).val();
        if (data == 0) {
            jQuery('#wfspb-top-bar').removeClass('bottom_bar').addClass('top_bar');
        } else {
            jQuery('#wfspb-top-bar').removeClass('top_bar').addClass('bottom_bar');
        }
    });

    jQuery('.select-textalign').dropdown({
        onChange: function () {
            var text_align = jQuery('.select-textalign').children('.text').text();
            jQuery('#wfspb-top-bar #wfspb-main-content').css('text-align', text_align);
        }
    });

    jQuery('#wfspb-font').fontselect().change(function () {
        var font = jQuery(this).val().replace(/\+/g, ' ');
        jQuery('#wfspb-top-bar').css('font-family', font);

    });

    jQuery('.select-fontsize').dropdown({
        onChange: function () {
            var font_size = jQuery('.select-fontsize').children('.text').text();
            jQuery('#wfspb-top-bar #wfspb-main-content').css('font-size', font_size);
            jQuery('#wfspb-close').css({'font-size': font_size, 'line-height': font_size});
        }
    });

    jQuery('.select-fontsize-progress').dropdown({
        onChange: function () {
            var font_size = jQuery('.select-fontsize-progress').children('.text').text();
            jQuery('#wfspb-label').css('font-size', font_size);
        }
    });

    jQuery('.wfspb-enable-progress').checkbox('setting', 'onChange', function () {
        if (jQuery('.wfspb-enable-progress').hasClass('checked')) {
            jQuery('#wfspb-progress').removeClass('disable_progress_bar').addClass('anable_progress_bar');
        } else {
            jQuery('#wfspb-progress').removeClass('anable_progress_bar').addClass('disable_progress_bar');
        }
    });

});


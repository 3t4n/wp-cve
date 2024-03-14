
jQuery(document).ready(function () {
    'use strict';
    function init() {
        jQuery('.vi-ui.vi-ui-main.tabular.menu .item').tab({
            history: true,
            historyType: 'hash'
        });
        /*Setup tab*/
        let tabs,
            tabEvent = false,
            initialTab = 'general',
            navSelector = '.vi-ui.vi-ui-main.menu',
            panelSelector = '.vi-ui.vi-ui-main.tab',
            panelFilter = function () {
                jQuery(panelSelector + ' a').filter(function () {
                    return jQuery(navSelector + ' a[title=' + jQuery(this).attr('title') + ']').size() != 0;
                });
            };
        // Initializes plugin features
        jQuery.address.strict(false).wrap(true);

        if (jQuery.address.value() == '') {
            jQuery.address.history(false).value(initialTab).history(true);
        }
        // Address handler
        jQuery.address.init(function (event) {

            // Adds the ID in a lazy manner to prevent scrolling
            jQuery(panelSelector).attr('id', initialTab);

            panelFilter();

            // Tabs setup
            tabs = jQuery('.vi-ui.vi-ui-main.menu')
                .tab({
                    history: true,
                    historyType: 'hash'
                });

            // Enables the plugin for all the tabs
            jQuery(navSelector + ' a').click(function (event) {
                tabEvent = true;

                tabEvent = false;
                return true;
            });

        });
        jQuery('.ui-sortable').sortable({
            placeholder: 'sctv-place-holder',
        });
    }

    init();
});
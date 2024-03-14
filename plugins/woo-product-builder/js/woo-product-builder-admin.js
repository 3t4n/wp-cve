'use strict';
jQuery(document).ready(function () {
	woo_product_builder.init();
});

var woo_product_builder = {
	init        : function () {
		/*Save loading when submit*/
		this.save_submit();
		/*Load color picker*/
		this.color_picker();

		/*Init tab */
		jQuery('.menu .item').unbind();
		jQuery('.vi-ui.tabular.menu .item').vi_tab({
			history    : true,
			historyType: 'hash'
		});

		/*Setup tab*/
        var tabs,
            tabEvent = false,
            initialTab = 'general',
            navSelector = '.vi-ui.menu',
            navFilter = function (el) {
                // return jQuery(el).attr('href').replace(/^#/, '');
            },
            panelSelector = '.vi-ui.tab',
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
            tabs = jQuery('.vi-ui.menu')
                .vi_tab({
                    history: true,
                    historyType: 'hash'
                })

            // Enables the plugin for all the tabs
            jQuery(navSelector + ' a').click(function (event) {
                tabEvent = true;
                // jQuery.address.value(navFilter(event.target));
                tabEvent = false;
                return true;
            });

        });
		/*End setup tab*/
		jQuery('.vi-ui.checkbox').checkbox();
		jQuery('.vi-ui.radio').checkbox();
	},
	save_submit : function () {
		jQuery('.woopb-button-save').one('click', function () {
			jQuery(this).addClass('loading');
		})
	},
	color_picker: function () {
		// Color picker
		jQuery('.color-picker').iris({
			change: function (event, ui) {
				jQuery(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
				var ele = jQuery(this).data('ele');
				if (ele == 'highlight') {
					jQuery('#message-purchased').find('a').css({'color': ui.color.toString()});
				} else if (ele == 'textcolor') {
					jQuery('#message-purchased').css({'color': ui.color.toString()});
				} else {
					jQuery('#message-purchased').css({backgroundColor: ui.color.toString()});
				}
			},
			hide  : true,
			border: true
		}).click(function () {
			jQuery('.iris-picker').hide();
			jQuery(this).closest('td').find('.iris-picker').show();
		});
		jQuery('body').click(function () {
			jQuery('.iris-picker').hide();
		});
		jQuery('.color-picker').click(function (event) {
			event.stopPropagation();
		});
	}
};


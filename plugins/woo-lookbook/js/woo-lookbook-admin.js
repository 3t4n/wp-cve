'use strict';
jQuery(document).ready(function () {
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
	/*Init JS input*/
	jQuery('.vi-ui.checkbox').checkbox();
	jQuery('select.vi-ui.dropdown').dropdown();
	jQuery('.select2').select2();

	// jQuery("#IncludeFieldsMulti").select2("val", selectedItems);

	/*Save Submit button*/
	jQuery('.wlb-submit').one('click', function () {
		jQuery(this).addClass('loading');
	});
	jQuery('.select2-multiple').select2({
		width: '100%' // need to override the changed default
	});
	/*Color picker*/
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

	jQuery('.wlb-get-access-token').on('click', function (e) {
		var popup_frame;
		e.preventDefault();
		var url = jQuery(this).attr('data-href');
		popup_frame = window.open(url, "myWindow", "width=500,height=300");
		popup_frame.focus();
		var timer = setInterval(function () {
			if (popup_frame.closed) {
				clearInterval(timer);
				window.location.reload(); // Refresh the parent page
			}
		}, 1000);
	});

});
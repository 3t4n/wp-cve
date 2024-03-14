jQuery(document).ready(function ($) {
    'use strict';
    /* Pane, enqueue w/ customize-controls dependency at customize_controls_enqueue_scripts */
    wp.customize.bind('ready', function () {
        let submenu = [
            'thank_you_message',
            'order_confirmation',
            'order_details',
            'customer_information',
            'coupon',
            'social_icons',
            'google_map',
            'order_again',
        ];
        jQuery('.customize-section-back').on('click', function () {
            let id = jQuery(this).parent().parent().parent().prop('id').replace('sub-accordion-section-woo_thank_you_page_design_', '');
            if (submenu.indexOf(id) > -1) {
                wp.customize.section('woo_thank_you_page_design_general').expanded(true);
            }
        })
        jQuery('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
            wp.customize.previewer.send('wtyp_shortcut_to_available_shortcodes', 'show');
        });
        let url = location.href;
        wp.customize.section('woo_thank_you_page_design_general', function (section) {
            section.expanded.bind(function (isExpanded) {
                if (isExpanded) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: 'admin-ajax.php',
                        data: {
                            action: 'woo_thank_you_page_select_order',
                            order_id: wp.customize('woo_thank_you_page_params[select_order]').get(),
                        },
                        success: function (response) {
                            if (response && response.hasOwnProperty('url') && response.url) {
                                wp.customize.previewer.send('wtyp_update_url', response.url);
                            }
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    })
                } else {
                }
            })
        });
        wp.customize.previewer.bind('wtyp_open_latest_added_item', function (message) {
            jQuery('.woocommerce-thank-you-page-latest-item').find('.woocommerce-thank-you-page-edit').click();
            jQuery('.woocommerce-thank-you-page-item').removeClass('woocommerce-thank-you-page-latest-item');
        });
        wp.customize.previewer.bind('wtyp_update_text_editor', function (message) {
            wp.customize('woo_thank_you_page_params[text_editor]').set(message);
        });
        wp.customize.previewer.bind('wtyp_handle_overlay_processing', function (message) {
            if (message === 'show') {
                jQuery('.woocommerce-thank-you-page-control-processing').show();
            } else {
                jQuery('.woocommerce-thank-you-page-control-processing').hide();
            }
        });

        wp.customize.previewer.bind('wtyp_update_url', function (message) {
            location.href = message;
        });
        wp.customize.previewer.bind('wtyp_shortcut_edit', function (message) {
            wp.customize.section('woo_thank_you_page_design_' + message).expanded(true);
        });
        for (let i in submenu) {
            focus_on_editing_item_send(submenu[i]);
        }

        function focus_on_editing_item_send(name) {
            wp.customize.section('woo_thank_you_page_design_' + name, function (section) {
                section.expanded.bind(function (isExpanded) {
                    if (isExpanded) {
                        wp.customize.previewer.send('wtyp_focus_on_editing_item', 'woocommerce-thank-you-page-' + name + '__container');
                    }
                })
            });
        }

        /*edit item*/
        jQuery('body').on('click', '.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-edit', function (event) {
            event.stopPropagation();
            let parent = jQuery(this).parent();
            let item = parent.data()['block_item'];
            if (item == 'text_editor') {
                let position = jQuery('.woocommerce-thank-you-page-container__block .woocommerce-thank-you-page-' + item).index(parent);
                wp.customize.previewer.send('wtyp_shortcut_edit_' + item + '_from_section', position);
            } else {
                wp.customize.previewer.send('wtyp_shortcut_edit_item_from_section', 'woocommerce-thank-you-page-edit-item-shortcut[data-edit_section="' + item + '"]');
            }
        });
        jQuery('.wtyp-button-update-changes-google-map').on('click', function () {
            let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
            wp.customize.previewer.send('wtyp_update_google_map_address', address);
        })
    });
});
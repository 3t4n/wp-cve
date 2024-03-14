'use strict';
(function ($) {
    let markers_url = woo_thank_you_page_params.markers_url;
    let shortcodes = woo_thank_you_page_params.shortcodes;
    let payment_method_html;
    let infowindow;
    if (!shortcodes['order_number']) {
        let order_id = jQuery('.wtyp-order-id').val();
        if (order_id) {
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: woo_thank_you_page_params.url,
                data: {
                    action: 'woo_thank_you_page_get_available_shortcodes',
                    order_id: order_id
                },
                success: function (response) {
                    if (response && response.hasOwnProperty('shortcodes')) {
                        shortcodes = response.shortcodes;
                    }
                },
                error: function (err) {

                }
            })
        }
    }
    /*general*/
    wp.customize.bind('preview-ready', function () {
        /*ajax search*/
        jQuery(".search-product-parent").select2({
            closeOnSelect: false,
            placeholder: "Please fill in your  product title",
            ajax: {
                url: woo_thank_you_page_params.url,
                dataType: 'json',
                quietMillis: 50,
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: 'wtyp_search_product_parent',
                    };
                },
                type: "GET",
                processResults: function (data) {
                    return {
                        results: data ? data : []
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 2
        });
        jQuery(".search-category").select2({
            closeOnSelect: false,
            placeholder: "Please fill in your category title",
            ajax: {
                url: woo_thank_you_page_params.url,
                dataType: 'json',
                quietMillis: 50,
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: 'wtyp_search_cate',
                    };
                },
                type: "GET",
                processResults: function (data) {
                    return {
                        results: data ? data : []
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 2
        });

        wp.customize.preview.bind('active', function () {
            jQuery('.woocommerce-thank-you-page-available-shortcodes-item-copy').on('click', function () {
                jQuery(this).parent().find('input').select();
                document.execCommand("copy");
            });
            jQuery('.woocommerce-thank-you-page-coupon__code-code').focus(function () {
                jQuery(this).select();
            })
            jQuery('body').on('click', '.woocommerce-thank-you-page-edit-item-shortcut', function () {
                wp.customize.preview.send('wtyp_shortcut_edit', jQuery(this).data()['edit_section']);
            });
            jQuery('.woocommerce-thank-you-page-available-shortcodes-shortcut').on('click', function () {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
            });
            wp.customize.preview.bind('wtyp_shortcut_to_available_shortcodes', function () {
                if (jQuery('.woocommerce-thank-you-page-available-shortcodes-container').hasClass('woocommerce-thank-you-page-hidden')) {
                    jQuery('.woocommerce-thank-you-page-available-shortcodes-container').removeClass('woocommerce-thank-you-page-hidden');
                } else {
                    jQuery('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
                }

            });
            jQuery('.woocommerce-thank-you-page-available-shortcodes-items-close').on('click', function () {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-overlay').click();
            });
            jQuery('.woocommerce-thank-you-page-available-shortcodes-overlay').on('click', function () {
                jQuery('.woocommerce-thank-you-page-available-shortcodes-container').addClass('woocommerce-thank-you-page-hidden');
            });
            jQuery('.woocommerce-thank-you-page-available-shortcodes-item-syntax').find('input').on('click', function () {
                jQuery(this).select()
            });

            jQuery('body').on('click', '.woocommerce-thank-you-page-text-editor', function () {
                wtypc_disable_scroll();
                let index = jQuery('.woocommerce-thank-you-page-text-editor').index(jQuery(this));
                let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
                if (index > -1) {
                    jQuery('.woocommerce-thank-you-page-wp-editor-container').addClass('woocommerce-thank-you-page-active');
                    jQuery('.woocommerce-thank-you-page-wp-editor-overlay').addClass('woocommerce-thank-you-page-active');
                    jQuery(this).addClass('woocommerce-thank-you-page-editing');
                    let content = wtypc_b64DecodeUnicode(textEditor[index]);
                    if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                        tinyMCE.get('woocommerce-thank-you-page-wp-editor').setContent(content);
                    } else {
                        jQuery('#woocommerce-thank-you-page-wp-editor').val(content);
                    }
                }
            });
            jQuery('.woocommerce-thank-you-page-wp-editor-save').on('click', function () {
                jQuery('.woocommerce-thank-you-page-preview-processing-overlay').show();
                let content;
                if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                    content = tinyMCE.get('woocommerce-thank-you-page-wp-editor').getContent();
                } else {
                    content = jQuery('#woocommerce-thank-you-page-wp-editor').val();
                }
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_get_text_editor_content',
                        shortcodes: shortcodes,
                        content: content,
                        security:woocommerce_thank_you_page_customizer_params.ajax_nonce,
                    },
                    success: function (response) {
                        jQuery('.woocommerce-thank-you-page-preview-processing-overlay').hide();
                        let editing = jQuery('.woocommerce-thank-you-page-editing');
                        let index = jQuery('.woocommerce-thank-you-page-text-editor').index(editing);
                        let textEditor = JSON.parse(wp.customize('woo_thank_you_page_params[text_editor]').get());
                        if (index > -1) {
                            textEditor[index] = wtypc_b64EncodeUnicode(content);
                            wp.customize.preview.send('wtyp_update_text_editor', JSON.stringify(textEditor));
                        }
                        editing.find('.woocommerce-thank-you-page-text-editor-content').html(response.html)
                        jQuery('.woocommerce-thank-you-page-wp-editor-overlay').click();
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                        alert('Cannot save content.')
                    }
                })
            });

            jQuery('.woocommerce-thank-you-page-wp-editor-overlay').on('click', function () {
                jQuery('.woocommerce-thank-you-page-wp-editor-container').removeClass('woocommerce-thank-you-page-active');
                jQuery('.woocommerce-thank-you-page-wp-editor-overlay').removeClass('woocommerce-thank-you-page-active');
                jQuery('.woocommerce-thank-you-page-text-editor').removeClass('woocommerce-thank-you-page-editing');
                if (tinyMCE.get('woocommerce-thank-you-page-wp-editor')) {
                    tinyMCE.get('woocommerce-thank-you-page-wp-editor').setContent('');
                } else {
                    jQuery('#woocommerce-thank-you-page-wp-editor').val('');
                }
                wtypc_enable_scroll()
            });
            jQuery('.woocommerce-thank-you-page-wp-editor-cancel').on('click', function () {
                jQuery('.woocommerce-thank-you-page-wp-editor-overlay').click();
            });
            let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
            let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[google_map_zoom_level]').get());
            initMap(zoom_level, address);
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_item_from_section', function (item) {
            jQuery('.woocommerce-thank-you-page-wp-editor-overlay').click();
            jQuery('.' + item).click();
        });
        wp.customize.preview.bind('wtyp_shortcut_edit_text_editor_from_section', function (position) {
            jQuery('.woocommerce-thank-you-page-wp-editor-overlay').click();
            let item = jQuery('.woocommerce-thank-you-page-text-editor').eq(position);
            if (item.length) {
                item.click();
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                jQuery('html, body').animate({scrollTop: top}, 'slow');
            }
        });

        wp.customize.preview.bind('wtyp_focus_on_editing_item', function (message) {
            let item = jQuery('#' + message);
            if (item.length) {
                let top = item.offset().top;
                if (top > 200) {
                    top = top - 200;
                }
                jQuery('html, body').animate({scrollTop: top}, 'slow');
                let count = 6;
                let setHighLight = setInterval(function () {
                    if (count == 0) {
                        clearInterval(setHighLight);
                        item.css({'outline': 'none'});
                    } else {
                        if (count % 2 == 1) {
                            item.css({'outline': 'none'});
                        } else {
                            item.css({'outline': '1px solid rgba(1,1,1,1)'});
                        }
                        count--;
                    }
                }, 500)
            }
        });
        wp.customize.preview.bind('wtyp_update_url', function (message) {
            if (jQuery('.woocommerce-thank-you-page-customize-preview').length == 0) {
                wp.customize.preview.send('wtyp_update_url', message);
            }
        });
        wp.customize.preview.bind('wtyp_update_google_map_address', function (addr) {
            let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[google_map_zoom_level]').get());
            initMap(zoom_level, addr);
        })
        payment_method_html = jQuery('#woocommerce-thank-you-page-payment-method-html-hold').html();
    });

    function initMap(google_map_zoom_level, address) {
        if (woo_thank_you_page_params.google_map_api && jQuery('#woocommerce-thank-you-page-google-map').length > 0) {
            let map = new google.maps.Map(document.getElementById('woocommerce-thank-you-page-google-map'), {
                zoom: google_map_zoom_level,
            });

            var geocoder = new google.maps.Geocoder();
            address = address.replace(/\n/g, '<\/br>');
            address = address.replace('{store_address}', shortcodes['store_address']);
            address = address.replace('{billing_address}', shortcodes['billing_address']);
            address = address.replace('{shipping_address}', shortcodes['shipping_address']);
            // let address = jQuery('.woocommerce-thank-you-page-google-map-address').val();
            geocodeAddress(geocoder, map, address);
        } else if (jQuery('#woocommerce-thank-you-page-google-map').length > 0) {
            jQuery('#woocommerce-thank-you-page-google-map').html('<div class="woocommerce-thank-you-page-google-map-not-available"><h3>Google Map</h3> Please enter your Google API key, your map will show here.</div>');
        }
    }

    function geocodeAddress(geocoder, resultsMap, address) {
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location,
                    title: address,
                    icon: woo_thank_you_page_params.google_map_marker
                });
                wp.customize('woo_thank_you_page_params[google_map_zoom_level]', function (value) {
                    value.bind(function (newval) {
                        resultsMap.setZoom(parseInt(newval));
                    })
                })
                wp.customize('woo_thank_you_page_params[google_map_marker]', function (value) {
                    value.bind(function (newval) {
                        marker.setIcon(markers_url + newval + '.png');
                    })
                })
                if (typeof infowindow === 'undefined') {
                    infowindow = new google.maps.InfoWindow({});
                }
                let map_label = wp.customize('woo_thank_you_page_params[google_map_label]').get().replace(/\n/g, '<\/br>');
                map_label = map_label.replace('{address}', wp.customize('woo_thank_you_page_params[google_map_address]').get());
                map_label = map_label.replace('{store_address}', shortcodes['store_address']);
                map_label = map_label.replace('{billing_address}', shortcodes['billing_address']);
                map_label = map_label.replace('{shipping_address}', shortcodes['shipping_address']);
                infowindow.setContent(map_label);
                infowindow.open(resultsMap, marker);
                marker.addListener('click', function () {
                    infowindow.open(resultsMap, marker);
                });
                wp.customize('woo_thank_you_page_params[google_map_label]', function (value) {
                    value.bind(function (newval) {
                        newval = newval.replace(/\n/g, '<\/br>');
                        newval = newval.replace('{address}', wp.customize('woo_thank_you_page_params[google_map_address]').get());
                        newval = newval.replace('{store_address}', shortcodes['store_address']);
                        newval = newval.replace('{billing_address}', shortcodes['billing_address']);
                        newval = newval.replace('{shipping_address}', shortcodes['shipping_address']);
                        infowindow.setContent(newval);
                    })
                })
            }
        });
    }

    function handleOverlayProcessing(action) {
        wp.customize.preview.send('wtyp_handle_overlay_processing', action);
        jQuery('.woocommerce-thank-you-page-wp-editor-overlay').click();

        if (action === 'show') {
            jQuery('.woocommerce-thank-you-page-preview-processing-overlay').show();
        } else {
            jQuery('.woocommerce-thank-you-page-preview-processing-overlay').hide();
        }
    }

    wp.customize('woo_thank_you_page_params[select_order]', function (value) {
        value.bind(function (newval) {
            let container = jQuery('.woocommerce-thank-you-page-container');
            handleOverlayProcessing('show');
            if (container.length > 0) {
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_layout',
                        order_id: newval,
                        block: wp.customize('woo_thank_you_page_params[blocks]').get(),
                        text_editor: wp.customize('woo_thank_you_page_params[text_editor]').get(),
                        order_confirmation_header: wp.customize('woo_thank_you_page_params[order_confirmation_header]').get(),
                        order_details_header: wp.customize('woo_thank_you_page_params[order_details_header]').get(),
                        order_details_product_image: wp.customize('woo_thank_you_page_params[order_details_product_image]').get(),
                        customer_information_header: wp.customize('woo_thank_you_page_params[customer_information_header]').get(),
                        thank_you_message_header: wp.customize('woo_thank_you_page_params[thank_you_message_header]').get(),
                        thank_you_message_message: wp.customize('woo_thank_you_page_params[thank_you_message_message]').get(),
                        social_icons: {
                            'social_icons_header': wp.customize('woo_thank_you_page_params[social_icons_header]').get(),
                            'social_icons_target': wp.customize('woo_thank_you_page_params[social_icons_target]').get(),
                            'social_icons_facebook_url': wp.customize('woo_thank_you_page_params[social_icons_facebook_url]').get(),
                            'social_icons_facebook_select': wp.customize('woo_thank_you_page_params[social_icons_facebook_select]').get(),
                            'social_icons_twitter_url': wp.customize('woo_thank_you_page_params[social_icons_twitter_url]').get(),
                            'social_icons_twitter_select': wp.customize('woo_thank_you_page_params[social_icons_twitter_select]').get(),
                            'social_icons_pinterest_url': wp.customize('woo_thank_you_page_params[social_icons_pinterest_url]').get(),
                            'social_icons_pinterest_select': wp.customize('woo_thank_you_page_params[social_icons_pinterest_select]').get(),
                            'social_icons_instagram_url': wp.customize('woo_thank_you_page_params[social_icons_instagram_url]').get(),
                            'social_icons_instagram_select': wp.customize('woo_thank_you_page_params[social_icons_instagram_select]').get(),
                            'social_icons_dribbble_url': wp.customize('woo_thank_you_page_params[social_icons_dribbble_url]').get(),
                            'social_icons_dribbble_select': wp.customize('woo_thank_you_page_params[social_icons_dribbble_select]').get(),
                            'social_icons_tumblr_url': wp.customize('woo_thank_you_page_params[social_icons_tumblr_url]').get(),
                            'social_icons_tumblr_select': wp.customize('woo_thank_you_page_params[social_icons_tumblr_select]').get(),
                            'social_icons_google_url': wp.customize('woo_thank_you_page_params[social_icons_google_url]').get(),
                            'social_icons_google_select': wp.customize('woo_thank_you_page_params[social_icons_google_select]').get(),
                            'social_icons_vkontakte_url': wp.customize('woo_thank_you_page_params[social_icons_vkontakte_url]').get(),
                            'social_icons_vkontakte_select': wp.customize('woo_thank_you_page_params[social_icons_vkontakte_select]').get(),
                            'social_icons_linkedin_url': wp.customize('woo_thank_you_page_params[social_icons_linkedin_url]').get(),
                            'social_icons_linkedin_select': wp.customize('woo_thank_you_page_params[social_icons_linkedin_select]').get(),
                            'social_icons_youtube_url': wp.customize('woo_thank_you_page_params[social_icons_youtube_url]').get(),
                            'social_icons_youtube_select': wp.customize('woo_thank_you_page_params[social_icons_youtube_select]').get(),
                        },
                        payment_method_html: wtypc_b64EncodeUnicode(payment_method_html),
                        google_map_address: wp.customize('woo_thank_you_page_params[google_map_address]').get(),
                        google_map_label: wp.customize('woo_thank_you_page_params[google_map_label]').get(),
                    },
                    success: function (response) {
                        handleOverlayProcessing('hide');
                        if (response.hasOwnProperty('shortcodes')) {
                            shortcodes = response.shortcodes;
                        }
                        if (response.hasOwnProperty('blocks')) {
                            jQuery('.woocommerce-thank-you-page-container').html(response.blocks);
                        }
                        let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
                        let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[google_map_zoom_level]').get());
                        initMap(zoom_level, address);
                    },
                    error: function (err) {
                        handleOverlayProcessing('hide');
                        console.log(err);
                    }
                })
            } else {
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: woo_thank_you_page_params.url,
                    data: {
                        action: 'woo_thank_you_page_layout',
                        order_id: newval,
                        change_url: true
                    },
                    success: function (response) {
                        wp.customize.preview.send('wtyp_update_url', response.url);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                })
            }

        });
    });
    wp.customize('woo_thank_you_page_params[blocks]', function (value) {
        value.bind(function (newval) {
            handleOverlayProcessing('show');
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: woo_thank_you_page_params.url,
                data: {
                    action: 'woo_thank_you_page_layout',
                    order_id: wp.customize('woo_thank_you_page_params[select_order]').get(),
                    block: newval,
                    text_editor: wp.customize('woo_thank_you_page_params[text_editor]').get(),
                    order_confirmation_header: wp.customize('woo_thank_you_page_params[order_confirmation_header]').get(),
                    order_details_header: wp.customize('woo_thank_you_page_params[order_details_header]').get(),
                    order_details_product_image: wp.customize('woo_thank_you_page_params[order_details_product_image]').get(),
                    customer_information_header: wp.customize('woo_thank_you_page_params[customer_information_header]').get(),
                    thank_you_message_header: wp.customize('woo_thank_you_page_params[thank_you_message_header]').get(),
                    thank_you_message_message: wp.customize('woo_thank_you_page_params[thank_you_message_message]').get(),
                    social_icons: {
                        'social_icons_header': wp.customize('woo_thank_you_page_params[social_icons_header]').get(),
                        'social_icons_target': wp.customize('woo_thank_you_page_params[social_icons_target]').get(),
                        'social_icons_facebook_url': wp.customize('woo_thank_you_page_params[social_icons_facebook_url]').get(),
                        'social_icons_facebook_select': wp.customize('woo_thank_you_page_params[social_icons_facebook_select]').get(),
                        'social_icons_twitter_url': wp.customize('woo_thank_you_page_params[social_icons_twitter_url]').get(),
                        'social_icons_twitter_select': wp.customize('woo_thank_you_page_params[social_icons_twitter_select]').get(),
                        'social_icons_pinterest_url': wp.customize('woo_thank_you_page_params[social_icons_pinterest_url]').get(),
                        'social_icons_pinterest_select': wp.customize('woo_thank_you_page_params[social_icons_pinterest_select]').get(),
                        'social_icons_instagram_url': wp.customize('woo_thank_you_page_params[social_icons_instagram_url]').get(),
                        'social_icons_instagram_select': wp.customize('woo_thank_you_page_params[social_icons_instagram_select]').get(),
                        'social_icons_dribbble_url': wp.customize('woo_thank_you_page_params[social_icons_dribbble_url]').get(),
                        'social_icons_dribbble_select': wp.customize('woo_thank_you_page_params[social_icons_dribbble_select]').get(),
                        'social_icons_tumblr_url': wp.customize('woo_thank_you_page_params[social_icons_tumblr_url]').get(),
                        'social_icons_tumblr_select': wp.customize('woo_thank_you_page_params[social_icons_tumblr_select]').get(),
                        'social_icons_google_url': wp.customize('woo_thank_you_page_params[social_icons_google_url]').get(),
                        'social_icons_google_select': wp.customize('woo_thank_you_page_params[social_icons_google_select]').get(),
                        'social_icons_vkontakte_url': wp.customize('woo_thank_you_page_params[social_icons_vkontakte_url]').get(),
                        'social_icons_vkontakte_select': wp.customize('woo_thank_you_page_params[social_icons_vkontakte_select]').get(),
                        'social_icons_linkedin_url': wp.customize('woo_thank_you_page_params[social_icons_linkedin_url]').get(),
                        'social_icons_linkedin_select': wp.customize('woo_thank_you_page_params[social_icons_linkedin_select]').get(),
                        'social_icons_youtube_url': wp.customize('woo_thank_you_page_params[social_icons_youtube_url]').get(),
                        'social_icons_youtube_select': wp.customize('woo_thank_you_page_params[social_icons_youtube_select]').get(),
                    },
                    payment_method_html: wtypc_b64EncodeUnicode(payment_method_html),
                    google_map_address: wp.customize('woo_thank_you_page_params[google_map_address]').get(),
                    google_map_label: wp.customize('woo_thank_you_page_params[google_map_label]').get(),
                },
                success: function (response) {
                    handleOverlayProcessing('hide');
                    if (response.hasOwnProperty('blocks')) {
                        jQuery('.woocommerce-thank-you-page-container').html(response.blocks);
                        if (response.hasOwnProperty('shortcodes')) {
                            shortcodes = response.shortcodes;
                        }
                        wp.customize.preview.send('wtyp_open_latest_added_item', '');

                    }
                    let address = wp.customize('woo_thank_you_page_params[google_map_address]').get();
                    let zoom_level = parseInt(wp.customize('woo_thank_you_page_params[google_map_zoom_level]').get());
                    initMap(zoom_level, address);
                },
                error: function (err) {
                    handleOverlayProcessing('hide');
                    console.log(err);
                }
            })
        });
    });

    /*order confirmation*/
    addPreviewControl('order_confirmation_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'background-color');
    addPreviewControl('order_confirmation_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'padding', 'px');
    addPreviewControl('order_confirmation_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-radius', 'px');
    addPreviewControl('order_confirmation_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-width', 'px');
    addPreviewControl('order_confirmation_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-style');
    addPreviewControl('order_confirmation_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', 'border-color');

    addPreviewControl('order_confirmation_vertical_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-width', 'px');
    addPreviewControl('order_confirmation_vertical_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-style');
    addPreviewControl('order_confirmation_vertical_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'border-right-color');

    addPreviewControl('order_confirmation_horizontal_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-top-width', 'px');
    addPreviewControl('order_confirmation_horizontal_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-top-style');
    addPreviewControl('order_confirmation_horizontal_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:first-child) .woocommerce-thank-you-page-order_confirmation-value div', 'border-top-color');

    wp.customize('woo_thank_you_page_params[order_confirmation_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-order_confirmation-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    addPreviewControl('order_confirmation_header_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'color');
    addPreviewControl('order_confirmation_header_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'background-color');
    addPreviewControl('order_confirmation_header_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'font-size', 'px');
    addPreviewControl('order_confirmation_header_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', 'text-align');

    addPreviewControl('order_confirmation_title_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'color');
    addPreviewControl('order_confirmation_title_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'background-color');
    addPreviewControl('order_confirmation_title_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'font-size', 'px');
    addPreviewControl('order_confirmation_title_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', 'text-align');

    addPreviewControl('order_confirmation_value_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'color');
    addPreviewControl('order_confirmation_value_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'background-color');
    addPreviewControl('order_confirmation_value_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'font-size', 'px');
    addPreviewControl('order_confirmation_value_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', 'text-align');

    /*order details*/
    addPreviewControl('order_details_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'color');
    addPreviewControl('order_details_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'background-color');
    addPreviewControl('order_details_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'padding', 'px');
    addPreviewControl('order_details_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-radius', 'px');
    addPreviewControl('order_details_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-width', 'px');
    addPreviewControl('order_details_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-style');
    addPreviewControl('order_details_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', 'border-color');

    addPreviewControl('order_details_horizontal_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-width', 'px');
    addPreviewControl('order_details_horizontal_style', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-style');
    addPreviewControl('order_details_horizontal_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', 'border-top-color');

    addPreviewControl('order_details_header_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'color');
    addPreviewControl('order_details_header_bg_color', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'background-color');
    addPreviewControl('order_details_header_font_size', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'font-size', 'px');
    addPreviewControl('order_details_header_text_align', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', 'text-align');

    addPreviewControl('order_details_product_image_width', '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-title a.woocommerce-thank-you-page-order-item-image-wrap', 'width', 'px');

    wp.customize('woo_thank_you_page_params[order_details_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-order_details-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    wp.customize('woo_thank_you_page_params[order_details_product_image]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.woocommerce-thank-you-page-order-item-image-container').addClass('woocommerce-thank-you-page-active');
            } else {
                jQuery('.woocommerce-thank-you-page-order-item-image-container').removeClass('woocommerce-thank-you-page-active');
            }
        })
    });


    /*customer information*/
    addPreviewControl('customer_information_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'color');
    addPreviewControl('customer_information_bg', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'background-color');
    addPreviewControl('customer_information_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'padding', 'px');
    addPreviewControl('customer_information_border_radius', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-radius', 'px');
    addPreviewControl('customer_information_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-width', 'px');
    addPreviewControl('customer_information_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-style');
    addPreviewControl('customer_information_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', 'border-color');

    addPreviewControl('customer_information_vertical_width', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-width', 'px');
    addPreviewControl('customer_information_vertical_style', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-style');
    addPreviewControl('customer_information_vertical_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', 'border-left-color');

    addPreviewControl('customer_information_header_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'color');
    addPreviewControl('customer_information_header_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'background-color');
    addPreviewControl('customer_information_header_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'font-size', 'px');
    addPreviewControl('customer_information_header_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', 'text-align');

    addPreviewControl('customer_information_address_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'color');
    addPreviewControl('customer_information_address_bg_color', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'background-color');
    addPreviewControl('customer_information_address_font_size', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'font-size', 'px');
    addPreviewControl('customer_information_address_text_align', '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', 'text-align');

    wp.customize('woo_thank_you_page_params[customer_information_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-customer_information-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });

    /*social icons*/
    addPreviewControl('social_icons_header_color', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'color');
    addPreviewControl('social_icons_header_font_size', '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', 'font-size', 'px');
    addPreviewControl('social_icons_align', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials', 'text-align');
    addPreviewControl('social_icons_space', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li:not(:last-child)', 'margin-right', 'px');
    addPreviewControl('social_icons_size', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li .wtyp-social-button span', 'font-size', 'px');
    addPreviewControl('social_icons_facebook_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-facebook-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_twitter_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-twitter-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_pinterest_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-pinterest-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_instagram_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-instagram-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_dribbble_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-dribbble-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_tumblr_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-tumblr-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_google_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-google-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_vkontakte_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-vkontakte-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_linkedin_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-linkedin-follow .wtyp-social-button span:before', 'color');
    addPreviewControl('social_icons_youtube_color', '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-youtube-follow .wtyp-social-button span:before', 'color');
    wp.customize('woo_thank_you_page_params[social_icons_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-social_icons-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    wp.customize('woo_thank_you_page_params[social_icons_target]', function (value) {
        value.bind(function (newval) {
            jQuery('.wtyp-social-button').attr('target', newval);
        });
    });
    addPreviewControlSocialIcon('facebook');
    addPreviewControlSocialUrl('facebook');
    addPreviewControlSocialIcon('twitter');
    addPreviewControlSocialUrl('twitter');
    addPreviewControlSocialIcon('pinterest');
    addPreviewControlSocialUrl('pinterest');
    addPreviewControlSocialIcon('instagram');
    addPreviewControlSocialUrl('instagram');
    addPreviewControlSocialIcon('dribbble');
    addPreviewControlSocialUrl('dribbble');
    addPreviewControlSocialIcon('tumblr');
    addPreviewControlSocialUrl('tumblr');
    addPreviewControlSocialIcon('google');
    addPreviewControlSocialUrl('google');
    addPreviewControlSocialIcon('vkontakte');
    addPreviewControlSocialUrl('vkontakte');
    addPreviewControlSocialIcon('linkedin');
    addPreviewControlSocialUrl('linkedin');
    addPreviewControlSocialIcon('youtube');
    addPreviewControlSocialUrl('youtube');

    /*thank you message*/
    addPreviewControl('thank_you_message_color', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', 'color');
    addPreviewControl('thank_you_message_padding', '.woocommerce-thank-you-page-thank_you_message__container', 'padding', 'px');
    addPreviewControl('thank_you_message_text_align', '.woocommerce-thank-you-page-thank_you_message__container', 'text-align');
    addPreviewControl('thank_you_message_header_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header', 'font-size', 'px');
    addPreviewControl('thank_you_message_message_font_size', '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message', 'font-size', 'px');
    wp.customize('woo_thank_you_page_params[thank_you_message_header]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-thank_you_message-header>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    wp.customize('woo_thank_you_page_params[thank_you_message_message]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            jQuery('.woocommerce-thank-you-page-thank_you_message-message>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    /*coupon*/
    addPreviewControl('coupon_text_align', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'text-align');
    addPreviewControl('coupon_padding', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', 'padding', 'px');
    addPreviewControl('coupon_message_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'color');
    addPreviewControl('coupon_message_font_size', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', 'font-size', 'px');
    addPreviewControl('coupon_code_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'color');
    addPreviewControl('coupon_code_bg_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'background-color');
    addPreviewControl('coupon_code_border_width', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-width', 'px');
    addPreviewControl('coupon_code_border_style', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-style');
    addPreviewControl('coupon_code_border_color', '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', 'border-color');
    wp.customize('woo_thank_you_page_params[coupon_scissors_color]', function (value) {
        value.bind(function (newval) {
            jQuery('#woocommerce-thank-you-page-coupon-scissors-color-css').html('.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before{color:' + newval + ';}');
        })
    })
    wp.customize('woo_thank_you_page_params[coupon_email_enable]', function (value) {
        value.bind(function (newval) {
            if (newval) {
                jQuery('.woocommerce-thank-you-page-coupon__code-email').removeClass('woocommerce-thank-you-page-hidden');
            } else {
                jQuery('.woocommerce-thank-you-page-coupon__code-email').addClass('woocommerce-thank-you-page-hidden');
            }
        })
    })
    wp.customize('woo_thank_you_page_params[coupon_message]', function (value) {
        value.bind(function (newval) {
            if (shortcodes['order_number']) {
                for (let i in shortcodes) {
                    let reg_string = '{' + i + '}';
                    let reg = new RegExp(reg_string, 'g');
                    newval = newval.replace(reg, shortcodes[i]);
                }
            }
            let coupon_code = jQuery('.woocommerce-thank-you-page-coupon-code').val(),
                coupon_amount = jQuery('.woocommerce-thank-you-page-coupon-amount').val(),
                coupon_date_expires = jQuery('.woocommerce-thank-you-page-coupon-date-expires').val(),
                last_valid_date = jQuery('.woocommerce-thank-you-page-last-valid-date').val();
            newval = newval.replace(/{coupon_code}/g, coupon_code);
            newval = newval.replace(/{coupon_amount}/g, coupon_amount);
            newval = newval.replace(/{coupon_date_expires}/g, coupon_date_expires);
            newval = newval.replace(/{last_valid_date}/g, last_valid_date);
            jQuery('.woocommerce-thank-you-page-coupon-message>div').html(newval.replace(/\n/g, '<\/br>'));
        })
    });
    /*google map*/
    wp.customize('woo_thank_you_page_params[google_map_width]', function (value) {
        value.bind(function (newval) {
            if (newval != '0') {
                jQuery('#woocommerce-thank-you-page-preview-google-map-width').html('#woocommerce-thank-you-page-google-map{width:' + newval + 'px}');
            } else {
                jQuery('#woocommerce-thank-you-page-preview-google-map-width').html('#woocommerce-thank-you-page-google-map{width:100%}');
            }
        })
    });
    addPreviewControl('google_map_height', '#woocommerce-thank-you-page-google-map', 'height', 'px');
    /*custom css*/
    wp.customize('woo_thank_you_page_params[custom_css]', function (value) {
        value.bind(function (newval) {
            jQuery('#woocommerce-thank-you-page-preview-custom-css').html(newval);
        })
    });

    function addPreviewControl(name, element, style, suffix = '') {
        wp.customize('woo_thank_you_page_params[' + name + ']', function (value) {
            value.bind(function (newval) {
                jQuery('#woocommerce-thank-you-page-preview-' + name.replace(/_/g, '-')).html(element + '{' + style + ':' + newval + suffix + '}');
            })
        })
    }

    function addPreviewControlSocialIcon(name) {
        wp.customize('woo_thank_you_page_params[social_icons_' + name + '_select]', function (value) {
            value.bind(function (newval) {
                jQuery('.wtyp-' + name + '-follow span').attr('class', 'wtyp-social-icon ' + newval);
            })
        })
    }

    function addPreviewControlSocialUrl(name) {
        wp.customize('woo_thank_you_page_params[social_icons_' + name + '_url]', function (value) {
            value.bind(function (newval) {
                jQuery('.wtyp-' + name + '-follow a').attr('href', newval);
                if (newval) {
                    jQuery('.wtyp-' + name + '-follow').fadeIn(300);
                } else {
                    jQuery('.wtyp-' + name + '-follow').fadeOut(300);
                }
            })
        })
    }
})(jQuery);

function wtypc_enable_scroll() {
    let scrollTop = parseInt(jQuery('html').css('top'));
    jQuery('html').removeClass('wtypc-noscroll');
    jQuery('html,body').scrollTop(-scrollTop);
}

function wtypc_disable_scroll() {
    if (jQuery(document).height() > jQuery(window).height()) {
        let scrollTop = (jQuery('html').scrollTop()) ? jQuery('html').scrollTop() : jQuery('body').scrollTop(); // Works for Chrome, Firefox, IE...
        jQuery('html').addClass('wtypc-noscroll').css('top', -scrollTop);
    }
}
function wtypc_b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
        }));
}
function wtypc_b64DecodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}
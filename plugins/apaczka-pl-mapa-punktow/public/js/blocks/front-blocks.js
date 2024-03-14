var apaczkaMap;
var apaczka_geowidget_supplier;
var single_apaczka_method_req_map = false;
var apaczka_only_cod = false;
var is_method_visible = false;

let map_button = '<input type="button" ' +
    'class="button alt geowidget_show_map" ' +
    'name="geowidget_show_map" ' +
    'id="geowidget_show_map" ' +
    'value="' + apaczka_block.button_text1 + '" ' +
    'data-value="' + apaczka_block.button_text1 + '">';

function apaczka_wait_fo_element(selector) {
    return new Promise(resolve => {
        if (document.querySelector(selector)) {
            return resolve(document.querySelector(selector));
        }

        const observer = new MutationObserver(mutations => {
            if (document.querySelector(selector)) {
                resolve(document.querySelector(selector));
                observer.disconnect();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

function apaczka_get_shipping_method_block() {
    let data = {};
    let shipping_block_html = jQuery('.wc-block-components-shipping-rates-control');
    if(typeof shipping_block_html != 'undefined' && shipping_block_html !== null) {
        let shipping_radio_buttons = jQuery(shipping_block_html).find('input[name^="radio-control-0"]');
        if( shipping_radio_buttons.length > 0 ) {
            let method = jQuery('input[name^="radio-control-0"]:checked').val();
            let postfix = '';
            if ('undefined' == typeof method || null === method) {
                method = jQuery('input[name^="radio-control-0"]').val();
            }

            if (typeof method != 'undefined' && method !== null) {
                if (method.indexOf(':') > -1) {
                    let arr = method.split(':');
                    method = arr[0];
                    postfix = arr[1];
                }
            }
            data.method = method;
            data.postfix = postfix;
        }
    }

    return data;
}

function apaczka_changeReactInputValue(input,value){
    if (typeof input != 'undefined' && input !== null) {
        var nativeInputValueSetter = Object.getOwnPropertyDescriptor(
            window.HTMLInputElement.prototype,
            "value"
        ).set;
        nativeInputValueSetter.call(input, value);
        var inputEvent = new Event("input", {bubbles: true});
        input.dispatchEvent(inputEvent);
    }
}

function apaczka_reset_apm_data() {
    apaczka_changeReactInputValue(document.getElementById('apaczka-point'), '');
    jQuery('.apaczka-woocommerce-checkout-block-alert').remove();
    jQuery('#apaczka_selected_point_data').remove();
    jQuery('#shipping-phone').prop('required', false);
    jQuery('label[for="shipping-phone"]').text('Telefon (opcjonalnie)');
}

function apaczka_set_apm_data(record) {

    let visible_point_id = '';
    let visible_point_desc = '';
    let visible_city = '';
    let visible_street = '';
    let visible_house = '';
    let apaczka_point_data = {};

    if ('foreign_access_point_id' in record) {
        apaczka_point_data.apm_access_point_id = record.foreign_access_point_id;
        visible_point_id = '<div id="selected-parcel-machine-id">' + record.foreign_access_point_id + '</div>\n';
    }

    if ('supplier' in record) {
        apaczka_point_data.apm_supplier =  record.supplier;
    }

    if ('name' in record) {
        apaczka_point_data.apm_name =  record.name;
        visible_point_desc = '<span id="selected-parcel-machine-desc">' + record.name + '</span>';
    }

    if ('foreign_access_point_id' in record) {
        apaczka_point_data.apm_foreign_access_point_id =  record.foreign_access_point_id;
    }

    if ('street' in record) {
        apaczka_point_data.apm_street = record.street;
    }

    if ('city' in record) {
        apaczka_point_data.apm_city =  record.city;
    }

    if ('postal_code' in record) {
        apaczka_point_data.apm_postal_code =  record.postal_code;
    }

    if ('country_code' in record) {
        apaczka_point_data.apm_country_code =  record.country_code;
    }

    apaczka_changeReactInputValue(document.getElementById('apaczka-point'), JSON.stringify(apaczka_point_data) );

    jQuery('.apaczka-woocommerce-checkout-block-alert').remove();
    jQuery('.wc-block-checkout__actions_row').show();
    jQuery('#geowidget_show_map').text(apaczka_block.button_text2);

    let apaczka_point = '<div class="apaczka_selected_point_data" id="apaczka_selected_point_data">\n'
        + visible_point_id
        + visible_point_desc + '</div>';

    jQuery('#geowidget_show_map').after(apaczka_point);

    jQuery('#shipping-phone').prop('required', true);
    jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');

}


function apaczka_show_missed_locker_message() {
    if (jQuery('input[id="apaczka-point"]').val().length < 3) {
        let alert = '<div class="apaczka-woocommerce-checkout-block-alert">' +
            '<span style="color:red; font-size:24px;">' +
            apaczka_block.alert_text +
            '</span>' +
            '</div>';
        jQuery('.wc-block-checkout__actions').prepend(alert);
        jQuery('.wc-block-checkout__actions_row').hide();
    }
}

function apaczka_shipping_method_req_map(instance_id) {
    if( ! jQuery.isEmptyObject(apaczka_block.map_config) ) {
        if (apaczka_block.map_config.hasOwnProperty(instance_id)) {
            return true;
        }
    }
    return false;
}

jQuery(document).ready(function() {

    let shipping_data = apaczka_get_shipping_method_block();
    let method = null;
    let postfix = null;

    if( jQuery.isEmptyObject(shipping_data) ) {

        if (typeof apaczka_single != 'undefined' && apaczka_single !== null) {
            if (apaczka_single.need_map) {
                single_apaczka_method_req_map = true;
            }
        }

        let shipping_block_wrap = jQuery('.wc-block-components-shipping-rates-control__package');
        if (typeof shipping_block_wrap != 'undefined' && shipping_block_wrap !== null) {
            let is_method_visible_obj = jQuery(shipping_block_wrap).find('.wc-block-components-radio-control__label');
            if( is_method_visible_obj.length > 0 ) {
                is_method_visible = true;
            }
        }

        if( ! is_method_visible ) {

            apaczka_wait_fo_element('.wc-block-components-shipping-rates-control__package').then((elm) => {
                if(single_apaczka_method_req_map) {
                    jQuery('#shipping-phone').prop('required', true);
                    jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');
                    jQuery('.wc-block-components-shipping-rates-control__package').after(map_button);

                    if (apaczka_block.map_config.hasOwnProperty(apaczka_single.instance_id)) {
                        var key = apaczka_single.instance_id;
                        var shipping_config = apaczka_single.config[key];
                        if( typeof shipping_config.geowidget_supplier != 'undefined' && shipping_config.geowidget_supplier !== null) {
                            apaczkaMap.setFilterSupplierAllowed(shipping_config.geowidget_supplier);
                        }
                        if( typeof shipping_config.geowidget_only_cod != 'undefined' && shipping_config.geowidget_only_cod !== null) {
                            if ('yes' === shipping_config.geowidget_only_cod) {
                                apaczka_only_cod = true;
                            } else {
                                apaczka_only_cod = false;
                            }
                        }
                    }

                    apaczka_show_missed_locker_message();

                } else {

                    let checked_radio_control = jQuery('input[name^="radio-control-0"]:checked');
                    if( typeof checked_radio_control != 'undefined' && checked_radio_control !== null) {
                        let id = jQuery(checked_radio_control).attr('id');
                        let instance_id = null;
                        let method_data = null;
                        if (typeof id != 'undefined' && id !== null) {
                            method_data = id.split(":");
                            instance_id = method_data[method_data.length - 1];
                        }

                        jQuery('#geowidget_show_map').remove();
                        jQuery('#apaczka_selected_point_data').remove();

                        if ( instance_id && apaczka_shipping_method_req_map(instance_id)) {
                            if( ! jQuery.isEmptyObject(apaczka_block.map_config) ) {
                                if (apaczka_block.map_config.hasOwnProperty(instance_id)) {
                                    var key = instance_id;
                                    var shipping_config = apaczka_block.map_config[key];
                                    apaczka_geowidget_supplier = shipping_config.geowidget_supplier;
                                    if( typeof shipping_config.geowidget_supplier != 'undefined' && shipping_config.geowidget_supplier !== null) {
                                        apaczkaMap.setFilterSupplierAllowed(shipping_config.geowidget_supplier);
                                    }
                                    if( typeof shipping_config.geowidget_only_cod != 'undefined' && shipping_config.geowidget_only_cod !== null) {
                                        if ('yes' === shipping_config.geowidget_only_cod) {
                                            apaczka_only_cod = true;
                                        } else {
                                            apaczka_only_cod = false;
                                        }
                                    }
                                }
                            }
                            let label = jQuery('input[name^="radio-control-0"]:checked').parent('label');
                            jQuery(label).after(map_button);
                            jQuery('#shipping-phone').prop('required', true);
                            jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');
                            apaczka_show_missed_locker_message();
                        }
                    }
                }
            });
        }


    } else {
        method = shipping_data.method;
        postfix = shipping_data.postfix;
    }

    apaczkaMap = new ApaczkaMap({
        app_id: 'apaczka-woo-checkout',
        onChange: function (record) {
            if (record) {
                //console.log(record);
                apaczka_set_apm_data(record);
            }
        }
    });

    if( ! jQuery.isEmptyObject(apaczka_block.map_config) ) {
        if (apaczka_block.map_config.hasOwnProperty(postfix)) {
            var key = postfix;
            var shipping_config = apaczka_block.map_config[key];
            if( typeof shipping_config.geowidget_supplier != 'undefined' && shipping_config.geowidget_supplier !== null) {
                apaczkaMap.setFilterSupplierAllowed(shipping_config.geowidget_supplier);
            }
            if( typeof shipping_config.geowidget_only_cod != 'undefined' && shipping_config.geowidget_only_cod !== null) {
                if ('yes' === shipping_config.geowidget_only_cod) {
                    apaczka_only_cod = true;
                } else {
                    apaczka_only_cod = false;
                }
            }
        }
    }

    if ( (typeof method != 'undefined' && method !== null && typeof postfix != 'undefined' && postfix !== null) ) {
        if ( apaczka_shipping_method_req_map(postfix)) {
            let selector = 'radio-control-0-' + method + ':' + postfix;
            let label = jQuery('label[for="' + selector + '"]');
            jQuery(label).after(map_button);
            jQuery('#shipping-phone').prop('required', true);
            jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');
            apaczka_show_missed_locker_message();
        }

    } else if(single_apaczka_method_req_map) {

        let shipping_block_wrap = jQuery('.wc-block-components-shipping-rates-control__package');
        if (is_method_visible && typeof shipping_block_wrap != 'undefined' && shipping_block_wrap !== null) {
            jQuery('#shipping-phone').prop('required', true);
            jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');
            jQuery(shipping_block_wrap).after(map_button);

            if (apaczka_block.map_config.hasOwnProperty(apaczka_single.instance_id)) {
                var key = apaczka_single.instance_id;
                var shipping_config = apaczka_single.config[key];
                if( typeof shipping_config.geowidget_supplier != 'undefined' && shipping_config.geowidget_supplier !== null) {
                    apaczkaMap.setFilterSupplierAllowed(shipping_config.geowidget_supplier);
                }
                if( typeof shipping_config.geowidget_only_cod != 'undefined' && shipping_config.geowidget_only_cod !== null) {
                    if ('yes' === shipping_config.geowidget_only_cod) {
                        apaczka_only_cod = true;
                    } else {
                        apaczka_only_cod = false;
                    }
                }
            }

        }
        apaczka_show_missed_locker_message();

    } else {
        apaczka_reset_apm_data();
        jQuery('#apaczka_selected_point_data').remove();
        jQuery('#shipping-phone').prop('required', false);
        jQuery('label[for="shipping-phone"]').text('Telefon (opcjonalnie)');
        jQuery('.apaczka-woocommerce-checkout-block-alert').remove();
        jQuery('.wc-block-checkout__actions_row').show();
    }
});



document.addEventListener('change', function (e) {
    e = e || window.event;
    var target = e.target || e.srcElement;

    if ( target.classList.contains( 'wc-block-components-radio-control__input' ) ) {
        apaczkaMap = new ApaczkaMap({
            app_id: 'apaczka-woo-checkout',
            onChange: function (record) {
                if (record) {
                    apaczka_set_apm_data(record);
                }
            }
        });

        apaczka_reset_apm_data();

        let instance_id = null;
        let method_data = null;

        if ( target.checked && target.hasAttribute('id') )  {
            let id = target.getAttribute('id');
            if (typeof id != 'undefined' && id !== null) {
                method_data = id.split(":");
                instance_id = method_data[method_data.length - 1];
            }

            jQuery('#geowidget_show_map').remove();
            jQuery('#apaczka_selected_point_data').remove();

            if ( instance_id && apaczka_shipping_method_req_map(instance_id)) {
                if( ! jQuery.isEmptyObject(apaczka_block.map_config) ) {
                    if (apaczka_block.map_config.hasOwnProperty(instance_id)) {
                        var key = instance_id;
                        var shipping_config = apaczka_block.map_config[key];
                        apaczka_geowidget_supplier = shipping_config.geowidget_supplier;
                        if( typeof shipping_config.geowidget_supplier != 'undefined' && shipping_config.geowidget_supplier !== null) {
                            apaczkaMap.setFilterSupplierAllowed(shipping_config.geowidget_supplier);
                        }
                        if( typeof shipping_config.geowidget_only_cod != 'undefined' && shipping_config.geowidget_only_cod !== null) {
                            if ('yes' === shipping_config.geowidget_only_cod) {
                                apaczka_only_cod = true;
                            } else {
                                apaczka_only_cod = false;
                            }
                        }
                    }
                }

                let label = jQuery('label[for="' + id + '"]');
                jQuery(label).after(map_button);
                jQuery('#shipping-phone').prop('required', true);
                jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');
                apaczka_show_missed_locker_message();

            } else {

                jQuery('#geowidget_show_map').remove();
                jQuery('#apaczka_selected_point_data').remove();
                jQuery('#shipping-phone').prop('required', false);
                jQuery('label[for="shipping-phone"]').text('Telefon (opcjonalnie)');
                jQuery('.apaczka-woocommerce-checkout-block-alert').remove();
                jQuery('.wc-block-checkout__actions_row').show();

            }
        }
    }
});


document.addEventListener('click', function (e) {
    e = e || window.event;
    var target = e.target || e.srcElement;

    if ( target.hasAttribute('id') )  {
        if (target.getAttribute('id') === 'geowidget_show_map') {
            e.preventDefault();
            apaczka_reset_apm_data();
            if( apaczka_only_cod ) {
                apaczkaMap.show();
                apaczkaMap.filter_services_cod = true;
                jQuery('.apaczkaMapFilterCod').addClass('selected');
            } else {
                apaczkaMap.show();
                apaczkaMap.filter_services_cod = false;
                jQuery('.apaczkaMapFilterCod').removeClass('selected');
            }
        }
    }
});
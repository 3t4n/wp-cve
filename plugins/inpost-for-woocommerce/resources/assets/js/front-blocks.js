var geowidgetModal;
var geowidgetSelectedPoint;

function get_shipping_method_block() {
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

function changeReactInputValue(input,value) {

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



function show_missed_locker_message() {
    let point_value_input = jQuery('input[id="inpost-parcel-locker-id"]');
    if (typeof point_value_input != 'undefined' && point_value_input !== null) {
        if (jQuery(point_value_input).val() && jQuery(point_value_input).val().length < 3) {
            let alert = '<div class="easypack-woocommerce-checkout-block-alert">' +
                '<span style="color:red; font-size:24px;">' +
                'Musisz wybrać paczkomat!' +
                '</span>' +
                '</div>';
            jQuery('.wc-block-checkout__actions').prepend(alert);
            jQuery('.wc-block-checkout__actions_row').hide();
        }
    }
}

function show_prev_saved_point(saved_obj) {
    let point,
        selected_point_data,
        desc;

    let pointData = JSON.parse(saved_obj);

    if (typeof pointData.pointName != 'undefined' && pointData.pointName !== null) {
        point = pointData.pointName;
    }

    if (typeof pointData.pointDesc != 'undefined' && pointData.pointDesc !== null) {
        desc = pointData.pointDesc;
    } else {
        desc = '';
    }

    if (typeof pointData.pointAddDesc != 'undefined' && pointData.pointAddDesc !== null) {
        if (pointData.pointAddDesc.length > 0) {
            additional_desc = ' (' + pointData.pointAddDesc + ')';
        } else {
            additional_desc = '';
        }
    } else {
        additional_desc = '';
    }

    if (point) {
        let saved_point = '<div class="easypack_selected_point_data" id="easypack_selected_point_data">\n'
            + '<div id="selected-parcel-machine-id">' + point + '</div>\n'
            + '<span id="selected-parcel-machine-desc">' + desc + '</span>\n'
            + '<span id="selected-parcel-machine-desc1">' + additional_desc + '</span></div>';

        changeReactInputValue(document.getElementById('inpost-parcel-locker-id'), point);
        jQuery('#easypack_js_type_geowidget').after(saved_point);
    }
}

function selectPointCallback(point) {
    parcelMachineAddressDesc = point.address.line1;

    let selected_point_data = '';
    let EasyPackPointObject;

    changeReactInputValue(document.getElementById('inpost-parcel-locker-id'), point.name);

    jQuery('.easypack-woocommerce-checkout-block-alert').remove();
    jQuery('.wc-block-checkout__actions_row').show();

    if (point.location_description) {

        selected_point_data = '<div class="easypack_selected_point_data" id="easypack_selected_point_data">\n'
            + '<div id="selected-parcel-machine-id">' + point.name + '</div>\n'
            + '<span id="selected-parcel-machine-desc">' + point.address.line1 + '</span>\n'
            + '<span id="selected-parcel-machine-desc1">' + '(' + point.location_description + ')</span></div>';

    } else {
        selected_point_data = '<div class="easypack_selected_point_data" id="easypack_selected_point_data">\n'
            + '<div id="selected-parcel-machine-id">' + point.name + '</div>\n'
            + '<span id="selected-parcel-machine-desc">' + point.address.line1 + '</span></div>';
    }

    jQuery('#easypack_js_type_geowidget').after(selected_point_data);
    jQuery("#easypack_js_type_geowidget").text(easypack_block.button_text2);

    if (point.location_description) {
        EasyPackPointObject = {
            'pointName': point.name,
            'pointDesc': point.address.line1,
            'pointAddDesc': point.location_description
        };
    } else {
        EasyPackPointObject = {'pointName': point.name, 'pointDesc': point.address.line1, 'pointAddDesc': ''};
    }
    // Put the object into storage
    localStorage.setItem('EasyPackPointObject', JSON.stringify(EasyPackPointObject));

    geowidgetSelectedPoint = selected_point_data;
    geowidgetModal.close();

}

jQuery(document).ready(function() {

    setTimeout(function(){

        let token = easypack_block.geowidget_v5_token;
        let shipping_data = get_shipping_method_block();
        let config = 'parcelCollect';
        let single_inpost_method_req_map = false;
        let method = null;
        let postfix = null;

        if( jQuery.isEmptyObject(shipping_data) ) {
            if (easypack_single.need_map) {
                single_inpost_method_req_map = true;
            }
            if (easypack_single.config) {
                config = easypack_single.config;
            }

        } else {
            method = shipping_data.method;
            postfix = shipping_data.postfix;
        }

        if (typeof method != 'undefined' && method !== null) {
            if (method === 'easypack_parcel_machines_cod') {
                config = 'parcelCollectPayment';
            }
            if (method === 'easypack_shipping_courier_c2c') {
                config = 'parcelSend';
            }
            if (method === 'easypack_parcel_machines_weekend') {
                config = 'parcelCollect247';
            }
        }

        var wH = jQuery(window).height()-80;

        geowidgetModal = new jBox('Modal', {
            width: 800,
            height: wH,
            attach: '#eqasypack_show_geowidget',
            title: 'Wybierz paczkomat',
            content: '<inpost-geowidget id="inpost-geowidget" onpoint="selectPointCallback" token="' + token + '" language="pl" config="' + config + '"></inpost-geowidget>'
        });


        let map_button = '<button class="button alt easypack_show_geowidget" id="easypack_js_type_geowidget">\n' +
            easypack_block.button_text1 + '</button>';

        if ( (typeof method != 'undefined' && method !== null) ) {
            if (method.indexOf('easypack_parcel_machines') !== -1) {
                let selector = 'radio-control-0-' + method + ':' + postfix;
                let label = jQuery('label[for="' + selector + '"]');
                jQuery(label).after(map_button);
                jQuery('#shipping-phone').prop('required', true);
                jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');

                let EasyPackPointObject = localStorage.getItem('EasyPackPointObject');

                if (EasyPackPointObject !== null) {
                    show_prev_saved_point(EasyPackPointObject);
                }
                show_missed_locker_message();
            }

        } else if(single_inpost_method_req_map) {
            let shipping_block_wrap = jQuery('.wc-block-components-shipping-rates-control__package');
            if (typeof shipping_block_wrap != 'undefined' && shipping_block_wrap !== null) {
                jQuery(shipping_block_wrap).after(map_button);
                jQuery('#shipping-phone').prop('required', true);
                jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');

                let EasyPackPointObject = localStorage.getItem('EasyPackPointObject');

                if (EasyPackPointObject !== null) {
                    show_prev_saved_point(EasyPackPointObject);
                }
            }
            show_missed_locker_message();

        } else {
            jQuery('#easypack_selected_point_data').remove();
            jQuery('#shipping-phone').prop('required', false);
            jQuery('label[for="shipping-phone"]').text('Telefon (opcjonalnie)');
            changeReactInputValue(document.getElementById('inpost-parcel-locker-id'), '');
            jQuery('.easypack-woocommerce-checkout-block-alert').remove();
            jQuery('.wc-block-checkout__actions_row').show();
        }


        jQuery('input[name^="radio-control-0"]').on('change', function () {
            if (this.checked) {

                jQuery('#easypack_js_type_geowidget').remove();
                jQuery('#easypack_selected_point_data').remove();

                jQuery('.parcel_machine_id').val('');
                jQuery('.parcel_machine_desc').val('');

                if (jQuery(this).attr('id').indexOf('easypack_parcel_machines') !== -1) {
                    let label = jQuery(this).parent('label');
                    jQuery(label).after(map_button);
                    jQuery('#shipping-phone').prop('required', true);
                    jQuery('label[for="shipping-phone"]').text('Telefon (wymagany)');

                    let EasyPackPointObject = localStorage.getItem('EasyPackPointObject');

                    if (EasyPackPointObject !== null) {
                        show_prev_saved_point(EasyPackPointObject);
                    }

                    show_missed_locker_message();

                } else {

                    jQuery('#easypack_js_type_geowidget').remove();
                    jQuery('#easypack_selected_point_data').remove();

                    jQuery('.parcel_machine_id').val('');
                    jQuery('.parcel_machine_desc').val('');
                    changeReactInputValue(document.getElementById('inpost-parcel-locker-id'), '');
                    jQuery('#shipping-phone').prop('required', false);
                    jQuery('label[for="shipping-phone"]').text('Telefon (opcjonalnie)');

                    jQuery('.easypack-woocommerce-checkout-block-alert').remove();
                    jQuery('.wc-block-checkout__actions_row').show();

                }
            }
        });


    }, 500 );
});


document.addEventListener('click', function (e) {
    e = e || window.event;
    var target = e.target || e.srcElement;

    if ( target.hasAttribute('id') )  {
        if (target.getAttribute('id') == 'easypack_js_type_geowidget' || target.getAttribute('id') == 'inpost-parcel-locker-id') {
            e.preventDefault();
            jQuery('#easypack_selected_point_data').remove();
            geowidgetModal.open();
        }
    }
});
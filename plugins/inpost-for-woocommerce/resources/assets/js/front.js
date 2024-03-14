var geowidgetModal;

function get_shipping_method() {
    let data = {};
    let method = jQuery('input[name^="shipping_method"]:checked').val();
    let postfix = '';
    if('undefined' == typeof method || null === method ) {
        method = jQuery('input[name^="shipping_method"]').val();
    }
    if(typeof method != 'undefined' && method !== null) {
        if (method.indexOf(':') > -1) {
            let arr = method.split(':');
            method = arr[0];
            postfix = arr[1];
        }
    }
    data.method = method;
    data.postfix = postfix;

    return data;
}

function selectPointCallbackJSMode(point) {
    parcelMachineAddressDesc = point.address.line1;

    let EasyPackPointObject;
    let selected_point_data = '';

    if( point.location_description ) {

        selected_point_data = '<div class="easypack_selected_point_data" id="easypack_selected_point_data">\n'
            + '<div id="selected-parcel-machine-id">' + point.name + '</div>\n'
            + '<span id="selected-parcel-machine-desc">' + point.address.line1 + '</span>\n'
            + '<span id="selected-parcel-machine-desc1">' + '(' + point.location_description + ')</span>' +
            '<input type="hidden" id="parcel_machine_id"\n' +
            '                       name="parcel_machine_id" class="parcel_machine_id" value="'+point.name+'"/>\n' +
            '                <input type="hidden" id="parcel_machine_desc"\n' +
            '                       name="parcel_machine_desc" class="parcel_machine_desc" value="'+parcelMachineAddressDesc+'"/></div>\n';


    } else {
        selected_point_data = '<div class="easypack_selected_point_data" id="easypack_selected_point_data">\n'
            + '<div id="selected-parcel-machine-id">' + point.name + '</div>\n'
            + '<span id="selected-parcel-machine-desc">' + point.address.line1 + '</span>' +
            '<input type="hidden" id="parcel_machine_id"\n' +
            '                       name="parcel_machine_id" class="parcel_machine_id" value="'+point.name+'"/>\n' +
            '                <input type="hidden" id="parcel_machine_desc"\n' +
            '                       name="parcel_machine_desc" class="parcel_machine_desc" value="'+parcelMachineAddressDesc+'"/></div>';
    }

    jQuery('#easypack_js_type_geowidget').after(selected_point_data);
    jQuery("#easypack_js_type_geowidget").text(easypack_front_map.button_text2);

    if( point.location_description ) {
        EasyPackPointObject = { 'pointName': point.name, 'pointDesc': point.address.line1, 'pointAddDesc': point.location_description };
    } else {
        EasyPackPointObject = { 'pointName': point.name, 'pointDesc': point.address.line1, 'pointAddDesc': '' };
    }
    // Put the object into storage
    localStorage.setItem('EasyPackPointObject', JSON.stringify(EasyPackPointObject));

    geowidgetModal.close();
}


jQuery(document).ready(function() {

    // Prepare modal with map
    let token = easypack_front_map.geowidget_v5_token;
    let shipping_data = get_shipping_method();
    let method = shipping_data.method;
    let config = 'parcelCollect';

    if(typeof method != 'undefined' && method !== null) {
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
        attach: '#easypack_show_geowidget_modal',
        title: 'Wybierz paczkomat',
        content: '<inpost-geowidget id="inpost-geowidget" onpoint="selectPointCallbackJSMode" token="'+token+'" language="pl" config="'+config+'"></inpost-geowidget>'
    });

    jQuery( document.body ).on('update_checkout', function() {
        jQuery('.easypack_show_geowidget').each(function(ind, elem) {
            jQuery(elem).remove();
        });
        jQuery('.easypack_selected_point_data').each(function(ind, elem) {
            jQuery(elem).remove();
        });

    });

    jQuery( document.body ).on('updated_checkout', function() {

        let shipping_data = get_shipping_method();
        let method = shipping_data.method;
        let postfix = shipping_data.postfix;

        let selected_point_data = jQuery('#easypack_selected_point_data');

        if( typeof method != 'undefined' && method !== null ) {

            let selector = '#shipping_method_0_' + method + postfix;

            let map_button = '<div class="easypack_show_geowidget" id="easypack_js_type_geowidget">\n' +
                easypack_front_map.button_text1 + '</div>';

            let li = jQuery(selector).parent('li');

            if ( method.indexOf('easypack_parcel_machines') !== -1 ) {

                jQuery(li).after(map_button);
                jQuery('#ship-to-different-address').hide();

                let EasyPackPointObject = localStorage.getItem('EasyPackPointObject');

                if (EasyPackPointObject !== null) {
                    let point,
                        selected_point_data,
                        desc;

                    let pointData = JSON.parse(EasyPackPointObject);

                    if( typeof pointData.pointName != 'undefined' && pointData.pointName !== null ) {
                        point = pointData.pointName;
                    }

                    if( typeof pointData.pointDesc != 'undefined' && pointData.pointDesc !== null ) {
                        desc = pointData.pointDesc;
                    } else {
                        desc = '';
                    }

                    if( typeof pointData.pointAddDesc != 'undefined' && pointData.pointAddDesc !== null ) {
                        if (pointData.pointAddDesc.length > 0) {
                            additional_desc = ' (' + pointData.pointAddDesc + ')';
                        } else {
                            additional_desc = '';
                        }
                    } else {
                        additional_desc = '';
                    }

                    if( point ) {
                        selected_point_data = '<div class="easypack_selected_point_data" id="easypack_selected_point_data">\n'
                            + '<span class="font-height-600">' +  easypack_front_map.selected_text + '</span>\n'
                            + '<div id="selected-parcel-machine-id">' + point + '</div>\n'
                            + '<span id="selected-parcel-machine-desc">' + desc + '</span>\n'
                            + '<span id="selected-parcel-machine-desc1">' + additional_desc + '</span>'
                            + '<input type="hidden" id="parcel_machine_id" name="parcel_machine_id" class="parcel_machine_id" value="' + point + '"/>\n'
                            + '<input type="hidden" id="parcel_machine_desc" name="parcel_machine_desc" class="parcel_machine_desc" value="' + desc + '"/></div>';

                        jQuery('#easypack_js_type_geowidget').after(selected_point_data);
                        jQuery("#easypack_js_type_geowidget").text(easypack_front_map.button_text2);
                    }
                }

            } else {

                jQuery('.easypack_show_geowidget').each(function(ind, elem) {
                    jQuery(elem).remove();
                });

                jQuery('.easypack_selected_point_data').each(function(ind, elem) {
                    jQuery(elem).remove();
                });

                //empty hidden values of selected point
                jQuery('.parcel_machine_id').val('');
                jQuery('.parcel_machine_desc').val('');
                jQuery('#ship-to-different-address').show();
            }

            // open modal with map
            document.addEventListener('click', function (e) {
                e = e || window.event;
                var target = e.target || e.srcElement;

                if (target.hasAttribute('id') && target.getAttribute('id') == 'easypack_js_type_geowidget') {
                    e.preventDefault();
                    jQuery('#easypack_selected_point_data').remove();
                    geowidgetModal.open();
                }
            });
        }
    });
});

(function($){
    'use strict';

    let createShipmentParams = montonio_create_shipment;

    // Create shippment in Montonio
    $(document).on('click', '#montonio-shipping-create-shipment', function(e) {
        var data = {
            action: 'montonio_shipping_create_shipment',
            orderId: createShipmentParams.orderId
        };

        $('#montonio-shipping-create-shipment').attr('disabled', true);
        $('#montonio-shipping-create-shipment-spinner').addClass('is-active');

        $.post(createShipmentParams.createShipmentUrl, data, function() {
            if (wp && wp.data && wp.data.dispatch) {
                wp.data.dispatch('core/notices').createNotice(
                    'success',
                    'Shipment created successfully',
                );
            } else {
                alert('Shipment created successfully');
            }
        }).fail(function(response) {
            if (wp && wp.data && wp.data.dispatch) {
                wp.data.dispatch('core/notices').createNotice(
                    'error',
                    'Shipment creation failed',
                );
            } else {
                alert('Shipment creation failed');
            }
        }).always(function() {
            $('#montonio-shipping-create-shipment').attr('disabled', false);
            $('#montonio-shipping-create-shipment-spinner').removeClass('is-active');
        });
    });

    // Add pickup point dropdown in order view
    $(document).on('click', 'a.edit-order-item', function() {
        $('.shipping_method').trigger('change');
    });

    $(document).on('change', '.shipping_method', function() {
        let shippingMethodId = $(this).find(':selected').val(),
            data = {
                'action': 'get_country_select',
                'shipping_method_id': shippingMethodId,
            };

        $('.montonio-admin-pickup-point-country-select').remove();
        $('.montonio-admin-pickup-point-select').selectWoo('destroy');    
        $('.montonio-admin-pickup-point-select').remove();        
        
        if (shippingMethodId.includes('montonio_')) {
            $.post(woocommerce_admin_meta_boxes.ajax_url, data, function(response) {     
                if (response.success === true) {
                    $('.shipping_method').after(response.data);
                } else {
                    $('.shipping_method').after('<div class="montonio-admin-pickup-point-country-select">Sorry, we couldn\'t load pickup point list. Resave <a href="' + createShipmentParams.shippingSettingsUrl + '" target="_blank">Montonio shipping settings</a> to resync pickup point list.</a></div>');
                }
            });
        }
    });

    $(document).on('change', '.montonio-admin-pickup-point-country-select', function() {
        let optionName = $(this).find(':selected').val(),
            data = {
                'action': 'get_pickup_points_select',
                'option_name': optionName,
            };
        
        $('.montonio-admin-pickup-point-select').selectWoo('destroy');    
        $('.montonio-admin-pickup-point-select').remove();    

        $.post(woocommerce_admin_meta_boxes.ajax_url, data, function(response) {     
            if (response.success === true) {
                $('.montonio-admin-pickup-point-country-select').after(response.data);
                $('.montonio-admin-pickup-point-select').selectWoo({
                    width: '100%',
                });
            } else {
                $('.montonio-admin-pickup-point-country-select').after('<div class="montonio-admin-pickup-point-country-select">Sorry, we couldn\'t load pickup point list. Resave <a href="' + createShipmentParams.shippingSettingsUrl + '" target="_blank">Montonio shipping settings</a> to resync pickup point list.</a></div>');
            }
        });
    });

    $(document).on('items_saved', function(){
        let shippingMethodId = $('.shipping_method').find(':selected').val(),
            optionName = $('.montonio-admin-pickup-point-country-select').find(':selected').val(),
            pickupPointId = $('.montonio-admin-pickup-point-select').find(':selected').val(),
            data = {
                'action': 'process_selected_pickup_point',
                'order_id': woocommerce_admin_meta_boxes.post_id,
                'option_name': optionName,
                'pickup_point_uuid': pickupPointId,
            };

        if (shippingMethodId.includes('montonio_') && (shippingMethodId.includes('post_offices') || shippingMethodId.includes('parcel_machines'))) {
            $.post(woocommerce_admin_meta_boxes.ajax_url, data, function(response) {     
                if (response.success === true) {
                    $('#_shipping_address_1').val(response.data.name);
                    $('#_shipping_city').val(response.data.locality);
                    $('#_shipping_country').val(response.data.country).selectWoo();
                    $('#_shipping_address_2, #_shipping_postcode, #_shipping_state').val('');
                } else {
                    if (wp && wp.data && wp.data.dispatch) {
                        wp.data.dispatch('core/notices').createNotice(
                            'error',
                            'Sorry, we couldn\'t add selected pickup point to order meta.',
                        );
                    } else {
                        alert('Sorry, we couldn\'t add selected pickup point to order meta.');
                    }
                }
            });
        }
    });

})(jQuery);
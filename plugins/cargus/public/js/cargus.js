jQuery(function ($) {

    if (ajax_var.locationsDropdown) {
        // Replace the billing/sipping city field.
        let runningIndex = 0;
        const filterStates = function (section, initialRun) {
            initialRun = runningIndex === 0;

            const element = $('[name="' + section + '_city"]');
            if (element != null && ajax_var.locationsDropdown) {
                const type = element.prop('nodeName');
                const attr_class = element.attr('class');
                const attr_name = element.attr('name');
                const attr_id = element.attr('id');
                const attr_placeholder = element.attr('placeholder');
                const attr_autocomplete = element.attr('autocomplete');
                const elementValue = element.val();
                let defaultValue = false;
                const streetValue = $('[name="' + section + '_street"]').val();


                if ($('[name="' + section + '_country"]').val() == 'RO') {
                    $('select[name="' + attr_name + '"]').empty();

                    if ($('[name="' + section + '_street"]').parents(':eq(1)').is(":hidden")) {
                        $('[name="' + section + '_street"]').parents(':eq(1)').show();
                    }

                    if ($('[name="' + section + '_street_number"]').parents(':eq(1)').is(":hidden")) {
                        $('[name="' + section + '_street_number"]').parents(':eq(1)').show();
                    }

                    if (element.prop('nodeName') != 'SELECT') {
                        element.replaceWith('<select class="' + attr_class + '" name="' + attr_name + '"  id="' + attr_id + '"><option elementValue km="0">-</option></select>');
                    }

                    if ($('select[name="' + attr_name + '"]').hasClass('select2-hidden-accessible')) {
                        $('select[name="' + attr_name + '"]').select2('destroy');
                    }

                    $.ajax({
                        type: "POST",
                        url: ajax_var.url,
                        cache: true,
                        data: {
                            action: 'cargus_get_regions',
                            judet: $('[name="' + section + '_state"]').val(),
                            val: elementValue,
                            type: type,
                            security: ajax_var.nonce,
                        },
                        success: function (data) {
                            data = JSON.parse(data);
                            for (const [key, value] of Object.entries(data)) {
                                $('select[name="' + attr_name + '"]').append($("<option></option>").attr({
                                    'km': value[0],
                                    'locality-id': value[1],
                                    'value': value[2],
                                    'postal-code': value[3],
                                    'saturday-delivery': value[4]
                                }).text(key));

                                if (initialRun && elementValue == key) {
                                    defaultValue = true;
                                    $('[name="' + section + '_city"] option[value="' + value[2] + '"]').attr('selected', 'selected');
                                }
                            }

                            if ($('[name="' + section + '_street"]').prop('nodeName') == 'SELECT') {
                                $('[name="' + section + '_street"]').empty();
                                if ($('[name="' + section + '_street"]').hasClass('select2-hidden-accessible')) {
                                    $('[name="' + section + '_street"]').select2('destroy');
                                }
                            } else {
                                $('[name="' + section + '_street"]').val('');
                            }

                            if (!initialRun) {
                                $('[name="' + section + '_postcode"]').val('');
                                $('[name="' + section + '_street_number"]').val('');
                            }

                            // make city field select2.
                            $('select[name="' + attr_name + '"]').select2({
                                width: '100%'
                            });

                            if (initialRun && defaultValue) {
                                setTimeout(function () {
                                    /** Set billing city default value. */
                                    $('[name="' + section + '_city"]').val(elementValue).trigger('change', {'initial': true});
                                    $('body').trigger(section + '_default_load', {'streetName': streetValue});

                                }, 100);
                            }

                        }
                    });
                } else {
                    if (type != 'INPUT') {
                        if ($('[name="' + section + '_city"]').hasClass('select2-hidden-accessible')) {
                            $('[name="' + section + '_city"]').empty();
                            $('[name="' + section + '_city"]').select2('destroy');
                        }

                        $('[name="' + section + '_city"]').replaceWith('<input type="text" class="input-text" name="' + attr_name + '"  id="' + attr_id + '"' + (attr_placeholder ? ' placeholder="' + attr_placeholder + '"' : '') + ' autocomplete="' + attr_autocomplete + '" value="" />');
                    }

                    if ($('[name="' + section + '_street"]').prop('nodeName') != 'INPUT') {
                        if ($('[name="' + section + '_street"]').hasClass('select2-hidden-accessible')) {
                            $('[name="' + section + '_street"]').empty();
                            $('[name="' + section + '_street"]').select2('destroy');
                        }

                        $('[name="' + section + '_street"]').replaceWith('<input type="text" class="input-text" name="' + section + '_street"  id="' + section + '_street" value="" />');
                    }

                    $('[name="' + section + '_street"]').parents(':eq(1)').hide();
                    $('[name="' + section + '_street_number"]').val('');
                    $('[name="' + section + '_street_number"]').parents(':eq(1)').hide();

                }
            }

            runningIndex++;
        }

        // Replace the billing city field on billing state change.
        $(document).on('change', '[name="billing_state"]', function (e, params) {
            filterStates('billing');
        });

        // Replace the shipping city field on shipping state change.
        $(document).on('change', '[name="shipping_state"]', function () {
            filterStates('shipping');
        });

        // Replace the billing city field on billing country change.
        $(document).on('change', '[name="billing_country"]', function (e, params) {
            hideAddressField('billing');
            filterStates('billing');
        });

        // Replace the shipping city field on shipping country change.
        $(document).on('change', '[name="shipping_country"]', function () {
            hideAddressField('shipping');
            filterStates('shipping');
        });

        // Hide the address field 1 function.
        const hideAddressField = function (section) {
            if ($('[name="' + section + '_country"]').val() == 'RO') {
                $('#' + section + '_address_1_field').hide();
            } else {
                $('#' + section + '_address_1_field').show();
            }
        }

        if (ajax_var.streetDropdown) {
            // Hide the address field 1 on document ready.
            $(document).ready(function () {
                hideAddressField('billing');
                hideAddressField('shipping');
            });

            // Replace the billing/shipping street field.
            const filterCities = function (section, initialRun = false) {
                const street = $('[name="' + section + '_street"]');
                const type = street.prop('nodeName');
                const attr_class = street.attr('class');
                const attr_name = street.attr('name');
                const attr_id = street.attr('id');
                const attr_placeholder = street.attr('placeholder');

                const city = $('[name="' + section + '_city"]');
                const cityPostalCode = city.find('option:selected').attr('postal-code');

                if (street != null && $('[name="' + section + '_country"]').val() == 'RO') {
                    if (street.hasClass('select2-hidden-accessible') && street.prop('nodeName') == 'SELECT') {
                        street.empty();
                        street.select2('destroy');
                    }
                    if (jQuery.inArray(cityPostalCode, [undefined, '0', 'NULL']) !== -1) {
                        if (street.prop('nodeName') != 'SELECT') {
                            street.replaceWith('<select class="' + attr_class + '" name="' + attr_name + '"  id="' + attr_id + '"></select>');
                        }

                        $('select[name="' + section + '_street"]').select2({
                            language: {
                                searching: function () {
                                    return "Procesare...";
                                },
                                errorLoading: function () {
                                    return "Procesare...";
                                },
                                noResults: function () {
                                    return "Nu a fost gasit niciun rezultat.";
                                },
                                inputTooShort: function () {
                                    return "Introduceti cel putin 2 caractere pentru strazi.";
                                },
                            },
                            ajax: {
                                type: "POST",
                                url: ajax_var.url,
                                dataType: 'json',
                                delay: 100,

                                data: function (params) {
                                    return {
                                        q: params.term, // search term
                                        action: 'cargus_get_streets',
                                        city: $('[name="' + section + '_city"]').find('option:selected').attr('locality-id'),
                                        security: ajax_var.nonce,
                                        type: type,
                                    };
                                },
                                processResults: function (data) {
                                    let res = data.map(function (item) {
                                        return {
                                            id: item.StreetName,
                                            streetId: item.StreetId,
                                            text: item.StreetName,
                                            PostalNumbers: item.PostalNumbers
                                        };
                                    });
                                    return {
                                        results: res
                                    };
                                },
                                cache: true

                            },
                            minimumInputLength: 2,
                            placeholder: '-',
                            templateResult: formatOption,
                            templateSelection: formatOptionSelection,
                            width: '100%',
                        }).on('change', function (e) {
                            if ($(this).select2('data')[0].PostalNumbers) {
                                $(this).find('option:selected').attr('postal-numbers', $(this).select2('data')[0].PostalNumbers);
                                $(this).find('option:selected').attr('street-id', $(this).select2('data')[0].streetId);
                            }
                        });

                        if (!initialRun) {
                            $('[name="' + section + '_postcode"]').val('');
                        }

                    } else {
                        street.replaceWith('<input type="text" class="input-text" name="' + attr_name + '"  id="' + attr_id + '"' + (attr_placeholder ? ' placeholder="' + attr_placeholder + '"' : '') + ' autocomplete="street" value="" />');
                        $('[name="' + section + '_postcode"]').val($('[name="' + section + '_city"]').find('option:selected').attr('postal-code'));
                    }

                    if (!initialRun) {
                        $('[name="' + section + '_street_number"]').val('');
                    }

                    function formatOption(city) {
                        let $container = $(
                            "<div class='select2-result-title'>" + city.text + "</div>"
                        );

                        return $container;
                    }

                    function formatOptionSelection(city) {
                        return city.text;
                    }
                } else {
                    street.replaceWith('<input type="text" class="input-text" name="' + attr_name + '"  id="' + attr_id + '"' + (attr_placeholder ? ' placeholder="' + attr_placeholder + '"' : '') + ' autocomplete="street" value="" />');
                }
            }

            // Replace the shipping street field on shipping_city field change.
            $(document).on('change', '[name="shipping_city"]', function (e, params) {
                if (params && params.initial) {
                    filterCities('shipping', true);
                } else {
                    filterCities('shipping');
                }

                // set the additional cargus delivery status.
                sendAdditionalDelivery('shipping');
            });

            // Replace the billing street field on billing_city field change.
            $(document).on('change', '[name="billing_city"]', function (e, params) {
                if (params && params.initial) {
                    filterCities('billing', true);
                } else {
                    filterCities('billing');
                }

                // set the additional cargus delivery status.
                sendAdditionalDelivery('billing');
            });

            // Add saturday delivery.
            const sendAdditionalDelivery = function (section) {
                const city = $('#' + section + '_city')
                if (city.prop('nodeName') === 'SELECT') {
                    const citySaturdayDelivery = city.find('option:selected').attr('saturday-delivery');
                    const cityId               = city.find('option:selected').attr('locality-id');
                    const cityName             = city.find('option:selected').val();
                    if ( $( 'input[name="' + section + '_city_saturday_delivery"]' ).length ) {
                        $( 'input[name="' + section + '_city_saturday_delivery"]' ).val( citySaturdayDelivery );
                    } else {
                        $('[name="checkout"]').append("<input type='hidden' name='" + section + "_city_saturday_delivery' value='" + citySaturdayDelivery + "' />");
                    }

                    // Ajax request for city saturday delivery. TO DO.
                    // $.ajax({
                    //     type: 'POST',
                    //     url: ajax_var.url,
                    //     data: {
                    //         'action': 'cargus_saturday_delivery',
                    //         'saturday_delivery': citySaturdayDelivery,
                    //         'security': ajax_var.nonce,
                    //     },
                    //     success: function (result) {
                    //     }
                    // });

                    let cityPre10Delivery = false;
                    let cityPre12Delivery = false;
                    //Ajax request for city pre10 and pre12 delivery.
                    $.ajax({
                        type: 'POST',
                        url: ajax_var.url,
                        data: {
                            'action': 'cargus_pre_delivery',
                            'locality_id': cityId,
                            'locality_name': cityName,
                            'security': ajax_var.nonce,
                        },
                        success: function (result) {
                            result = JSON.parse(result);
                            cityPre10Delivery = result.pre10;
                            cityPre12Delivery = result.pre12;

                            if ( $( 'input[name="' + section + '_city_pre10_delivery"]' ).length ) {
                                $( 'input[name="' + section + '_city_pre10_delivery"]' ).val( cityPre10Delivery );
                            } else {
                                $('[name="checkout"]').append("<input type='hidden' name='" + section + "_city_pre10_delivery' value='" + cityPre10Delivery + "' />");
                            }

                            if ( $( 'input[name="' + section + '_city_pre12_delivery"]' ).length ) {
                                $( 'input[name="' + section + '_city_pre12_delivery"]' ).val( cityPre12Delivery );
                            } else {
                                $('[name="checkout"]').append("<input type='hidden' name='" + section + "_city_pre12_delivery' value='" + cityPre12Delivery + "' />");
                            }
                        }
                    });


                }

                return true;
            }

            // Replace the billing street field on billing_cities_generated event.
            $('body').on('billing_default_load', function (e, params) {
                setTimeout(function () {
                    streetAddAjaxOption('billing', params);
                }, 50);
            });

            $('body').on('shipping_default_load', function (e, params) {
                setTimeout(function () {
                    streetAddAjaxOption('shipping', params);
                }, 50);
            });

            // Add and select the default value if there is one.
            const streetAddAjaxOption = function (section, params) {
                $.ajax({
                    type: "POST",
                    url: ajax_var.url,
                    dataType: 'json',
                    data: {
                        action: 'cargus_get_streets',
                        city: $('[name="billing_city"]').find('option:selected').attr('locality-id'),
                        security: ajax_var.nonce,
                        val: params.streetName
                    },
                    success: function (data) {
                        if ( null !== data ) {
                            const option = new Option(data[0].StreetName, data[0].StreetName, true, true);
                            $('[name="' + section + '_street"]').append(option);
                            $('[name="' + section + '_street"] option').attr('postal-numbers', data[0].PostalNumbers);
                            $('[name="' + section + '_street"] option').attr('street-id', data[0].StreetId);

                            $('[name="' + section + '_street"]').val(data[0].StreetName).trigger('change');

                            // manually trigger the `select2:select` event
                            $('[name="' + section + '_street"]').trigger({
                                type: 'select2:select',
                                params: {
                                    data: data[0]
                                }
                            });
                        }
                    },
                });
            }

            // Fill in the postal code field.
            const filterStreetNumbers = function (section) {
                if ($('[name="' + section + '_country"]').val() != 'RO') {
                    //country is not Romania, no need for more searches
                    return true;
                }
                const element = $('[name="' + section + '_street_number"]');
                const strNumber = parseInt(element.val());

                if (jQuery.inArray($('[name="' + section + '_city"]').find('option:selected').attr('postal-code'), ['0', 'NULL']) === -1) {
                    //city has postal code, no need for more searches
                    return true;
                }

                const postalNumbers = $('[name="' + section + '_street"]').find('option:selected').attr('postal-numbers');

                if (postalNumbers != undefined) {
                    let noFound = 0;
                    let indexes = [];
                    $.each(JSON.parse(postalNumbers), function (key, value) {
                        if (
                            parseInt(value.FromNo) <= strNumber && parseInt(value.ToNo) >= strNumber ||
                            parseInt(value.FromNo) == strNumber && parseInt(value.ToNo) == 10000
                        ) {
                            indexes.push({'key': key, 'value': value.FromNo});
                            noFound++;
                        }
                    });

                    let index = 0;
                    if (noFound > 1) {
                        if (0 === strNumber % 2) {
                            index = getIndexByValue(indexes, index, true);
                            $('[name="' + section + '_postcode"]').val(JSON.parse(postalNumbers)[index].PostalCode);
                        } else {
                            index = getIndexByValue(indexes, index, false);
                            $('[name="' + section + '_postcode"]').val(JSON.parse(postalNumbers)[index].PostalCode);
                        }
                    } else if (noFound === 1) {
                        $('[name="' + section + '_postcode"]').val(JSON.parse(postalNumbers)[indexes[0]['key']].PostalCode);
                    }
                }
            }

            // Get the array item index by array item value.
            const getIndexByValue = function (array, index, odd = true) {
                $.each(array, function (key, value) {
                    if (odd) {
                        if (value.value % 2 == 0) {
                            index = value.key;
                        }
                    } else if (!odd) {
                        if (value.value % 2 != 0) {
                            index = value.key;
                        }
                    }
                });

                return index;
            }

            // Set the postal code and change the billing_address_1 value on billing street field change.
            $(document).on('change', '[name="billing_street"]', function () {
                setAddressValue('billing');
                sendStreetId('billing');
            });

            // Set the postal code and change the billing_address_1 value on billing street number field blur.
            $(document).on('blur', '[name="billing_street_number"]', function () {
                setAddressValue('billing');
                filterStreetNumbers('billing');
            });

            // Set the postal code and change the shipping_address_1 value on shipping street field change.
            $(document).on('change', '[name="shipping_street"]', function () {
                setAddressValue('shipping');
                sendStreetId('shipping');
            });

            // Set the postal code and change the shipping_address_1 value on shipping street number field blur.
            $(document).on('blur', '[name="shipping_street_number"]', function () {
                setAddressValue('shipping');
                filterStreetNumbers('shipping');
            });

            // Set the shipping/billing address 1 value function.
            const setAddressValue = function (section) {
                if ('' != $('[name="' + section + '_street"]').val()) {
                    if ('SELECT' == $('[name="' + section + '_street"]').prop('nodeName')) {
                        $('[name="' + section + '_address_1"]').val('Str. ' + $('[name="' + section + '_street"] option:selected').text());
                    } else {
                        $('[name="' + section + '_address_1"]').val($('[name="' + section + '_street"]').val());
                    }
                }

                if ('' != $('[name="' + section + '_street_number"]').val()) {
                    $('[name="' + section + '_address_1"]').val($('[name="' + section + '_address_1"]').val() + ', Nr. ' + $('[name="' + section + '_street_number"]').val());
                }
            }

            // Set the shipping/billing address 1 value function.
            $(document).on('submit', 'form.woocommerce-checkout', function () {
                setAddressValue('billing');
                setAddressValue('shipping');
            });

            // Add Street ID to form data.
            const sendStreetId = function (section) {
                const street = $('#' + section + '_street')
                if (street.prop('nodeName') === 'SELECT') {
                    const streetId = street.find('option:selected').attr('street-id');

                    $('[name="checkout"]').append("<input type='hidden' name='" + section + "_street_id' value='" + streetId + "' />");
                }

                return true;
            }
        }
    }

    // Change billing or shipping address field in order to force recalculate shipping fee.
    const forceShippingRecalculation = function () {
        // fac asta pentru ca daca nu modific un string din adresa, nu se recalculeaza transpprtul.
        if ($('#ship-to-different-address-checkbox:checked').length > 0) {
            const shipping_address = $('#shipping_address_1').val();
            const shipping_last = shipping_address.substr(shipping_address.length - 1);
            if (shipping_last == '.') {
                $('#shipping_address_1').val($('#shipping_address_1').val().slice(0, -1));
            } else {
                $('#shipping_address_1').val(shipping_address + '.');
            }
        } else {
            const billing_address = $('#billing_address_1').val();
            const billing_last = billing_address.substr(billing_address.length - 1);
            if (billing_last == '.') {
                $('#billing_address_1').val($('#billing_address_1').val().slice(0, -1));
            } else {
                $('#billing_address_1').val(billing_address + '.');
            }
        }

        // trigger functia care face refresh checkout-ului
        $('body').trigger('update_checkout');
    }

    // Force shipping recalculation only if the cargus fixed price is not set, and we have cargus selected as a shipping method.
    $(document).on('change', '[name="payment_method"]', function () {
        if ($('input[name^="shipping_method"]:checked').val() !== 'cargus') {
            //exit if shipping method is other than cargus shipping.
            return;
        }

        if (ajax_var.freeShippingPrice !== '' && ajax_var.freeShippingPrice < ajax_var.cartSubtotal) {
            //exit if cart subtotal is bigger than free shipping price.
            return;
        }

        const addressCheckbox = $('ship-to-different-address-checkbox');
        if (ajax_var.fixedPrice !== '' ||
            (!addressCheckbox.is(":checked") && $('input#billing_state').val() === 'B' && ajax_var.fixedPriceBucharest !== '') ||
            (addressCheckbox.is(":checked") && $('input#shipping_state').val() === 'B' && ajax_var.fixedPriceBucharest !== '')
        ) {
            //exit if I have fixe shipping price.
            return;
        }

        // force shipping price recalculation.
        forceShippingRecalculation();
    });
});

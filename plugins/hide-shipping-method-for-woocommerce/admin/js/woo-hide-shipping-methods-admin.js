(function ($) {
    'use strict';
    jQuery('.multiselect2').select2();
    function allowSpeicalCharacter(str) {
        return str.replace('&#8211;', '–').replace('&gt;', '>').replace('&lt;', '<').replace('&#197;', 'Å');
    }
    $('.shipping_method_list').select2({
        placeholder: coditional_vars.select_shipping
    });
    $('#sm_select_day_of_week').select2({
        placeholder: coditional_vars.select_days
    });
    function productFilter() {
        jQuery('.product_fees_conditions_values_product').each(function () {
            $('.product_fees_conditions_values_product').select2({
                placeholder: coditional_vars.select_product,
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    minimumInputLength: 3,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'whsma_product_fees_conditions_values_product_ajax',
                            _page: params.page || 1,
                            posts_per_page: 10 
                        };
                    },
                    processResults: function( data ) {
                        var options = [], more = true;
                        if ( data ) {
                            $.each( data, function( index, text ) {
                                options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
                            } );
                        }
                        //for stop paination on all data laod 
                        if( 0 === options.length ){ 
                            more = false; 
                        }
                        return {
                            results: options,
                            pagination: {
                                more: more
                            }
                        };
                    }
                }
            });
        });
    }

    
    function setAllAttributes(element, attributes) {
        Object.keys(attributes).forEach(function (key) {
            element.setAttribute(key, attributes[key]);
            // use val
        });
        return element;
    }

    $(window).load(function () {
        $('.multiselect2').select2();

        $('.shipping_method_list').select2({
            placeholder: coditional_vars.select_shipping
        });
        $('#sm_select_day_of_week').select2({
            placeholder: coditional_vars.select_days
        });
 
        $('.hide_shipping').hide();

        var current_val = $('input[name="shipping_method_option"]:checked').val();
        if (current_val === 'main_shipping_method') {
            $('#shipping_list_tr').show();
            $('#custom_shipping_list_tr').hide();
        } else {
            $('#shipping_list_tr').hide();
            $('#custom_shipping_list_tr').show();
        }

        $('body').on('click', 'input[name="shipping_method_option"]', function () {
            if ($(this).val() === 'main_shipping_method') {
                $('#shipping_list_tr').show();
                $('#custom_shipping_list_tr').hide();
            } else {
                $('#shipping_list_tr').hide();
                $('#custom_shipping_list_tr').show();
            }
        });
        
        


        var ele = $('#total_row').val();
        var count;
        if (ele > 2) {
            count = ele;
        } else {
            count = 2;
        }
        $('body').on('click', '#fee-add-field', function () {
            var fee_add_field = $('#tbl-shipping-method tbody').get(0);

            var tr = document.createElement('tr');
            tr = setAllAttributes(tr, {'id': 'row_' + count});
            fee_add_field.appendChild(tr);

            // generate th of condition
            var th = document.createElement('td');
            th = setAllAttributes(th, {
                'class': 'titledesc th_product_fees_conditions_condition'
            });
            tr.appendChild(th);
            var conditions = document.createElement('select');
            conditions = setAllAttributes(conditions, {
                'rel-id': count,
                'id': 'product_fees_conditions_condition_' + count,
                'name': 'fees[product_fees_conditions_condition][]',
                'class': 'product_fees_conditions_condition'
            });
            conditions = insertOptions(conditions, get_all_condition());
            th.appendChild(conditions);
            // th ends

            // generate td for equal or no equal to
            var td = document.createElement('td');
            td = setAllAttributes(td, {
                class: 'select_condition_for_in_notin'
            });
            tr.appendChild(td);
            var conditions_is = document.createElement('select');
            conditions_is = setAllAttributes(conditions_is, {
                'name': 'fees[product_fees_conditions_is][]',
                'class': 'product_fees_conditions_is product_fees_conditions_is_' + count
            });
            conditions_is = insertOptions(conditions_is, condition_types(false));
            td.appendChild(conditions_is);
            // td ends

            // td for condition values
            td = document.createElement('td');
            td = setAllAttributes(td, {
                'id': 'column_' + count,
                'class': 'condition-value'
            });
            tr.appendChild(td);
            condition_values(jQuery('#product_fees_conditions_condition_' + count));

            var condition_key = document.createElement('input');
            condition_key = setAllAttributes(condition_key, {
                'type': 'hidden',
                'name': 'condition_key[value_' + count + '][]',
                'value': '',
            });
            td.appendChild(condition_key);
            jQuery('.product_fees_conditions_values_' + count).trigger('chosen:updated');
            // td ends

            // td for delete button
            td = document.createElement('td');
            tr.appendChild(td);
            var delete_button = document.createElement('a');
            delete_button = setAllAttributes(delete_button, {
                'id': 'fee-delete-field',
                'rel-id': count,
                'title': coditional_vars.delete,
                'class': 'delete-row',
                'href': 'javascript:;'
            });
            var deleteicon = document.createElement('i');
            deleteicon = setAllAttributes(deleteicon, {
                'class': 'dashicons dashicons-trash'
            });
            delete_button.appendChild(deleteicon);
            td.appendChild(delete_button);
            // td ends

            count++;

            // Enable/disable first row delete button
            let allDeleteRow = $('.shipping-method-rules .delete-row');
            if ( allDeleteRow.length === 1 ) {
                allDeleteRow.addClass('disable-delete-icon');
            } else {
                allDeleteRow.removeClass('disable-delete-icon');
            }
        });

        $('body').on('change', '.product_fees_conditions_condition', function () {
            condition_values(this);
        });

        /* description toggle */
        $('span.woo_hide_shipping_methods_tab_description').click(function (event) {
            event.preventDefault();
            $(this).next('p.description').toggle();
        });

        

        //remove tr on delete icon click
        $('body').on('click', '.delete-row', function () {
            $(this).parent().parent().remove();

            // Enable/disable first row delete button
            let allDeleteRow = $('.shipping-method-rules .delete-row');
            if ( allDeleteRow.length === 1 ) {
                allDeleteRow.addClass('disable-delete-icon');
            } else {
                allDeleteRow.removeClass('disable-delete-icon');
            }
        });

        function insertOptions(parentElement, options) {
            var option;
            for (var i = 0; i < options.length; i++) {
                if (options[i].type === 'optgroup') {
                    var optgroup = document.createElement('optgroup');
                    optgroup = setAllAttributes(optgroup, options[i].attributes);
                    for (var j = 0; j < options[i].options.length; j++) {
                        option = document.createElement('option');
                        option = setAllAttributes(option, options[i].options[j].attributes);
                        option.textContent = options[i].options[j].name;
                        optgroup.appendChild(option);
                    }
                    parentElement.appendChild(optgroup);
                } else {
                    option = document.createElement('option');
                    option = setAllAttributes(option, options[i].attributes);
                    option.textContent = allowSpeicalCharacter(options[i].name);
                    parentElement.appendChild(option);
                }

            }
            return parentElement;

        }

        function get_all_condition() {
            var flag = $('.whsm-flag').val();
            if ( '1' === flag ) {
                return [
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.location_specific},
                        'options': [
                            {'name': coditional_vars.country, 'attributes': {'value': 'country'}},
                            {'name': coditional_vars.city, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.state, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.postcode, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.zone, 'attributes': {'value': '', 'disabled': 'disabled'}},
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.product_specific},
                        'options': [
                            {'name': coditional_vars.cart_contains_product, 'attributes': {'value': 'product'}},
                            {
                                'name': coditional_vars.cart_contains_variable_product,
                                'attributes': {'value': '', 'disabled': 'disabled'}
                            },
                            {'name': coditional_vars.cart_contains_category_product, 'attributes': {'value': 'category'}},
                            {'name': coditional_vars.cart_contains_tag_product, 'attributes': {'value': 'tag'}},
                            {'name': coditional_vars.cart_contains_sku_product, 'attributes': {'value': '', 'disabled': 'disabled'}},
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.attribute_specific},
                        'options': [
                            {'name': coditional_vars.attribute_list, 'attributes': {'value': '', 'disabled': 'disabled'}},
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.user_specific},
                        'options': [
                            {'name': coditional_vars.user, 'attributes': {'value': 'user'}},
                            {'name': coditional_vars.user_role, 'attributes': {'value': '', 'disabled': 'disabled'}}
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.cart_specific},
                        'options': [
                            {'name': coditional_vars.cart_subtotal_before_discount, 'attributes': {'value': 'cart_total'}},
                            {
                                'name': coditional_vars.cart_subtotal_after_discount,
                                'attributes': {'value': '', 'disabled': 'disabled'}
                            },
                            {'name': coditional_vars.quantity, 'attributes': {'value': 'quantity'}},
                            {'name': coditional_vars.weight, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.length, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.width, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.height, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.volume, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.coupon, 'attributes': {'value': '', 'disabled': 'disabled'}},
                            {'name': coditional_vars.shipping_class, 'attributes': {'value': '', 'disabled': 'disabled'}}
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.checkout_specific},
                        'options': [
                            {
                                'name': coditional_vars.payment_method,
                                'attributes': {'value': '', 'disabled': 'disabled'}
                            },
                        ]
                    },
                ];
            } else {
                return [
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.location_specific},
                        'options': [
                            {'name': coditional_vars.country, 'attributes': {'value': 'country'}},
                            {'name': coditional_vars.city, 'attributes': {'value': 'city'}},
                            {'name': coditional_vars.state, 'attributes': {'value': 'state'}},
                            {'name': coditional_vars.postcode, 'attributes': {'value': 'postcode'}},
                            {'name': coditional_vars.zone, 'attributes': {'value': 'zone'}},
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.product_specific},
                        'options': [
                            {'name': coditional_vars.cart_contains_product, 'attributes': {'value': 'product'}},
                            {
                                'name': coditional_vars.cart_contains_variable_product,
                                'attributes': {'value': 'variableproduct'}
                            },
                            {'name': coditional_vars.cart_contains_category_product, 'attributes': {'value': 'category'}},
                            {'name': coditional_vars.cart_contains_tag_product, 'attributes': {'value': 'tag'}},
                            {'name': coditional_vars.cart_contains_sku_product, 'attributes': {'value': 'sku'}},
                            {'name': coditional_vars.product_qty, 'attributes': {'value': 'product_qty'}},
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.attribute_specific},
                        'options': JSON.parse(coditional_vars.attribute_list)
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.user_specific},
                        'options': [
                            {'name': coditional_vars.user, 'attributes': {'value': 'user'}},
                            {'name': coditional_vars.user_role, 'attributes': {'value': 'user_role'}}
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.cart_specific},
                        'options': [
                            {'name': coditional_vars.cart_subtotal_before_discount, 'attributes': {'value': 'cart_total'}},
                            {
                                'name': coditional_vars.cart_subtotal_after_discount,
                                'attributes': {'value': 'cart_totalafter'}
                            },
                            {'name': coditional_vars.quantity, 'attributes': {'value': 'quantity'}},
                            {'name': coditional_vars.weight, 'attributes': {'value': 'weight'}},
                            {'name': coditional_vars.length, 'attributes': {'value': 'length'}},
                            {'name': coditional_vars.width, 'attributes': {'value': 'width'}},
                            {'name': coditional_vars.height, 'attributes': {'value': 'height'}},
                            {'name': coditional_vars.volume, 'attributes': {'value': 'volume'}},
                            {'name': coditional_vars.coupon, 'attributes': {'value': 'coupon'}},
                            {'name': coditional_vars.shipping_class, 'attributes': {'value': 'shipping_class'}}
                        ]
                    },
                    {
                        'type': 'optgroup',
                        'attributes': {'label': coditional_vars.checkout_specific},
                        'options': [
                            {
                                'name': coditional_vars.payment_method,
                                'attributes': {'value': 'payment_method'}
                            },
                        ]
                    },
                ];
            }
        }

        function condition_values(element) {
            var condition = $(element).val();
            var count = $(element).attr('rel-id');
            var column = jQuery('#column_' + count).get(0);
            jQuery(column).empty();
            var loader = document.createElement('img');
            loader = setAllAttributes(loader, {'src': coditional_vars.plugin_url + 'images/ajax-loader.gif'});
            column.appendChild(loader);

            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'whsma_product_fees_conditions_values_ajax',
                    'condition': condition,
                    'count': count
                },
                contentType: 'application/json',
                success: function (response) {
                    var condition_values;
                    jQuery('.product_fees_conditions_is_' + count).empty();
                    var column = jQuery('#column_' + count).get(0);
                    var condition_is = jQuery('.product_fees_conditions_is_' + count).get(0);
                    if (condition === 'cart_total'
                        || condition === 'quantity'
                        
                    ) {
                        condition_is = insertOptions(condition_is, condition_types(true));
                    } else {
                        condition_is = insertOptions(condition_is, condition_types(false));
                    }
                    jQuery('.product_fees_conditions_is_' + count).trigger('change');
                    jQuery(column).empty();

                    var condition_values_id = '';
                    var extra_class = '';
                    if ( condition === 'product' ) {
                        condition_values_id = 'product-filter-' + count;
                        extra_class = 'product_fees_conditions_values_product';
                    }
                    
                    if ( condition === 'user' ) {
                        condition_values_id = 'user-filter-' + count;
                        extra_class = 'product_fees_conditions_values_user';
                    }

                    if (isJson(response)) {
                        condition_values = document.createElement('select');
                        condition_values = setAllAttributes(condition_values, {
                            'name': 'fees[product_fees_conditions_values][value_' + count + '][]',
                            'class': 'whsm_select product_fees_conditions_values product_fees_conditions_values_' + count + ' multiselect2 ' + extra_class + ' multiselect2_' + count + '_' + condition,
                            'multiple': 'multiple',
                            'id': condition_values_id,
                        });
                        column.appendChild(condition_values);
                        var data = JSON.parse(response);
                        condition_values = insertOptions(condition_values, data);
                    } else {
                        let fieldPlaceholder;
                        if ( condition === 'city' ) {
                            fieldPlaceholder = coditional_vars.select_city;
                        } else if ( condition === 'postcode' ) {
                            fieldPlaceholder = coditional_vars.select_postcode;
                        } else if ( condition === 'product_qty' || condition === 'quantity' ) {
                            fieldPlaceholder = coditional_vars.select_integer_number;
                        } else {
                            fieldPlaceholder = coditional_vars.select_float_number;
                        }

                        condition_values = document.createElement(jQuery.trim(response));
                        condition_values = setAllAttributes(condition_values, {
                            'name': 'fees[product_fees_conditions_values][value_' + count + ']',
                            'class': 'product_fees_conditions_values',
                            'type': 'text',
                            'placeholder': fieldPlaceholder

                        });
                        column.appendChild(condition_values);
                    }
                    column = $('#column_' + count).get(0);
                    var input_node = document.createElement('input');
                    input_node = setAllAttributes(input_node, {
                        'type': 'hidden',
                        'name': 'condition_key[value_' + count + '][]',
                        'value': ''
                    });
                    column.appendChild(input_node);

                    

                    let searchAttribute = 'pa_';
                    if (condition.indexOf(searchAttribute) !== -1) {
                        $( '.multiselect2_' + count + '_' + condition ).select2({
                            placeholder: coditional_vars.select_attribute
                        });
                    } else {
                        let selectCoundition = coditional_vars['select_' + condition];
                        $( '.multiselect2_' + count + '_' + condition ).select2({
                            placeholder: selectCoundition
                        });
                    }

                    productFilter();

                    
                    numberValidateForAdvanceRules();
                }
            });
        }

        function condition_types(text) {
            if (text === true) {
                return [
                    {'name': coditional_vars.equal_to, 'attributes': {'value': 'is_equal_to'}},
                    {'name': coditional_vars.less_or_equal_to, 'attributes': {'value': 'less_equal_to'}},
                    {'name': coditional_vars.less_than, 'attributes': {'value': 'less_then'}},
                    {'name': coditional_vars.greater_or_equal_to, 'attributes': {'value': 'greater_equal_to'}},
                    {'name': coditional_vars.greater_than, 'attributes': {'value': 'greater_then'}},
                    {'name': coditional_vars.not_equal_to, 'attributes': {'value': 'not_in'}},
                ];
            } else {
                return [
                    {'name': coditional_vars.equal_to, 'attributes': {'value': 'is_equal_to'}},
                    {'name': coditional_vars.not_equal_to, 'attributes': {'value': 'not_in'}},
                ];

            }

        }

        productFilter();

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (err) {
                return false;
            }
            return true;
        }
    });
    jQuery(window).on('load', function () {
        // Active menu item
        $( 'a[href="admin.php?page=whsm-start-page"]' ).parents().addClass( 'current wp-has-current-submenu' );
        $( 'a[href="admin.php?page=whsm-start-page"]' ).addClass( 'current' );

        jQuery('.multiselect2').select2();
        jQuery( '.product_fees_conditions_values_country' ).select2({
            placeholder: coditional_vars.select_country
        });

        jQuery('.shipping_method_list').select2({
            placeholder: coditional_vars.select_shipping
        });
        jQuery('#sm_select_day_of_week').select2({
            placeholder: coditional_vars.select_days
        });

        jQuery('#tbl-shipping-method tr').each(function() {
            var allCondition = jQuery(this).find('.th_product_fees_conditions_condition select').val();
            var get_placehoder = coditional_vars['select_'+ allCondition];

            if( jQuery(this).find('.condition-value select').length ){
                let searchAttribute = 'pa_';
                if (allCondition.indexOf(searchAttribute) !== -1) {
                    jQuery(this).find('.condition-value select').select2({
                        placeholder: coditional_vars.select_attribute
                    });
                } else {
                    jQuery(this).find('.condition-value select').select2({
                        placeholder: get_placehoder
                    });
                }
            } else {
                jQuery(this).find('.product_fees_conditions_values').attr('placeholder', get_placehoder);
            }
        });

        productFilter();
        
        
    });
    $(document).ready(function() {
        // Enable/disable first row delete button
        let allDeleteRow = $('.shipping-method-rules .delete-row');
        if ( allDeleteRow.length === 1 ) {
            allDeleteRow.addClass('disable-delete-icon');
        }
        
        /** tiptip js implementation */
        $( '.woocommerce-help-tip' ).tipTip( {
            'attribute': 'data-tip',
            'fadeIn': 50,
            'fadeOut': 50,
            'delay': 200,
            'keepAlive': true
        } );

        // script for plugin rating
        jQuery(document).on('click', '.dotstore-sidebar-section .content_box .et-star-rating label', function(e){
            e.stopImmediatePropagation();
            var rurl = jQuery('#et-review-url').val();
            window.open( rurl, '_blank' );
        });
        jQuery('span.advance_hide_shipping_tab_description').click(function (event) {
            event.preventDefault();
            jQuery(this).next('p.description').toggle();
        });

        $(document).on('click', 'td.status.column-status input[name="sm_status"]', function () {
			var current_shipping_id = $(this).attr('data-smid');
			var current_value = $(this).prop('checked');
			
			$.ajax({
				type: 'GET',
				url: coditional_vars.ajaxurl,
				data: {
					'action': 'whsm_change_status_from_list_section',
					'current_shipping_id': current_shipping_id,
					'current_value': current_value,
				}, beforeSend: function () {
					var div = document.createElement('div');
					div = setAllAttributes(div, {
						'class': 'loader-overlay',
					});
					
					var img = document.createElement('img');
					img = setAllAttributes(img, {
						'id': 'before_ajax_id',
						'src': coditional_vars.ajax_icon
					});
					
					div.appendChild(img);
					var tBodyTrLast = document.querySelector('.whsm-section-left');
					tBodyTrLast.appendChild(div);
				}, complete: function () {
					jQuery('.whsm-section-left .loader-overlay').remove();
				}, success: function (response) {
					jQuery('.active_list').text(response.active_count);
				}
			});
		});
        
        $('.delete a').click(function(){
            if( confirm(coditional_vars.delete_confirm) ) {
                return true; 
            } else {
                return false; 
            } 
        });

        /** Upgrade Dashboard Script START */
        // Dashboard features popup script
        $(document).on('click', '.dotstore-upgrade-dashboard .unlock-premium-features .feature-box', function (event) {
            let $trigger = $('.feature-explanation-popup, .feature-explanation-popup *');
            if(!$trigger.is(event.target) && $trigger.has(event.target).length === 0){
                $('.feature-explanation-popup-main').not($(this).find('.feature-explanation-popup-main')).hide();
                $(this).find('.feature-explanation-popup-main').show();
                $('body').addClass('feature-explanation-popup-visible');
            }
        });
        $(document).on('click', '.dotstore-upgrade-dashboard .popup-close-btn', function () {
            $(this).parents('.feature-explanation-popup-main').hide();
            $('body').removeClass('feature-explanation-popup-visible');
        });
        /** Upgrade Dashboard Script End */

        /** Plugin Setup Wizard Script START */
        // Hide & show wizard steps based on the url params 
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('require_license')) {
            $('.ds-plugin-setup-wizard-main .tab-panel').hide();
            $( '.ds-plugin-setup-wizard-main #step5' ).show();
        } else {
            $( '.ds-plugin-setup-wizard-main #step1' ).show();
        }
        
        // Plugin setup wizard steps script
        $(document).on('click', '.ds-plugin-setup-wizard-main .tab-panel .btn-primary:not(.ds-wizard-complete)', function () {
            var curruntStep = $(this).closest('.tab-panel').attr('id');
            var nextStep = 'step' + ( parseInt( curruntStep.slice(4,5) ) + 1 ); // Masteringjs.io

            if( 'step5' !== curruntStep ) {
                $( '#' + curruntStep ).hide();
                $( '#' + nextStep ).show();   
            }
        });

        // Get allow for marketing or not
        if ( $( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
            $('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
        } else {
            $('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
        }

        // Get allow for marketing or not on change     
        $(document).on( 'change', '.ds-plugin-setup-wizard-main .ds_count_me_in', function() {
            if ( this.checked ) {
                $('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
            } else {
                $('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
            }
        });

        // Complete setup wizard
        $(document).on( 'click', '.ds-plugin-setup-wizard-main .tab-panel .ds-wizard-complete', function() {
            if ( $( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
                $( '.fs-actions button'  ).trigger('click');
            } else {
                $('.fs-actions #skip_activation')[0].click();
            }
        });

        // Send setup wizard data on Ajax callback
        $(document).on( 'click', '.ds-plugin-setup-wizard-main .fs-actions button', function() {
            var wizardData = {
                'action': 'whsm_plugin_setup_wizard_submit',
                'survey_list': $('.ds-plugin-setup-wizard-main .ds-wizard-where-hear-select').val(),
                'nonce': coditional_vars.setup_wizard_ajax_nonce
            };

            $.ajax({
                url: coditional_vars.ajaxurl,
                data: wizardData,
                success: function ( success ) {
                    console.log(success);
                }
            });
        });
        /** Plugin Setup Wizard Script End */

        /** Dynamic Promotional Bar START */
        //set cookies
        function setCookie(name, value, minutes) {
            var expires = '';
            if (minutes) {
                var date = new Date();
                date.setTime(date.getTime() + (minutes * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }
        
        $(document).on('click', '.dpbpop-close', function () {
            var popupName       = $(this).attr('data-popup-name');
            setCookie( 'banner_' + popupName, 'yes', 60 * 24 * 7);
            $('.' + popupName).hide();
        });

        $(document).on('click', '.dpb-popup .dpb-popup-meta a', function () {
            var promotional_id = $(this).parents().find('.dpbpop-close').attr('data-bar-id');

            //Create a new Student object using the values from the textfields
            var apiData = {
                'bar_id' : promotional_id
            };

            $.ajax({
                type: 'POST',
                url: coditional_vars.dpb_api_url + 'wp-content/plugins/dots-dynamic-promotional-banner/bar-response.php',
                data: JSON.stringify(apiData),// now data come in this function
                dataType: 'json',
                cors: true,
                contentType:'application/json',
                
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                }
             });
        });
        /** Dynamic Promotional Bar END */

        // Advanced settings toggle
        $('.whsm_chk_advanced_settings').click(function(){
            $('.whsm_advanced_setting_section').toggle();
        });

        // script for updagrade to pro modal
        $(document).on('click', '#dotsstoremain .whsm-pro-feature', function(){
            $('body').addClass('whsm-modal-visible');
        });

        $(document).on('click', '#dotsstoremain .modal-close-btn', function(){
            $('body').removeClass('whsm-modal-visible');
        });
    });
})(jQuery);


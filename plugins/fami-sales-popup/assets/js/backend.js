jQuery(document).ready(function ($) {
    "use strict";
    
    var products_data_array = {};
    
    // Auto complete
    function famisp_auto_complete_search_instant() {
        if (!$('#famisp-keyword').length) {
            return false;
        }
        $('#famisp-keyword').on('focus', function (e) {
            $('.famisp-search-product-wrapper').addClass('show-search-results');
            var cur_key = $.trim($(this).val());
            if (cur_key == '') {
                $('#famisp-results').html('<div class="results-inner"></div>');
            }
            famisp_update_products_ids();
            if ($.isEmptyObject(products_data_array)) {
                if ($('#famisp-keyword').is('.loading')) {
                    return false;
                }
                $('#famisp-keyword').addClass('loading');
                var data = {
                    action: 'famisp_auto_complete_search_data_via_ajax',
                    security: famisp['security']
                };
                $.post(famisp.ajaxurl, data, function (response) {
                    $('#famisp-keyword').focus();
                    products_data_array = response['array'];
                    if ($.trim(response['success']) == 'yes') {
                        $(document).on('keyup', '#famisp-keyword', function (e) {
                            
                            var $this = $(this);
                            var $searchResult = $('#famisp-results');
                            var $searchWrap = $this.closest('.famisp-inner-wrapper');
                            var search_key = $.trim($this.val());
                            if (products_data_array && search_key != '') {
                                var search_results = famisp_search_json(search_key, products_data_array);
                                if (search_results) {
                                    $searchResult.html('<div class="results-inner"></div>');
                                    var max_instant_search_results = 9999; // parseInt(famisp['max_instant_search_results']);
                                    if (isNaN(max_instant_search_results) || max_instant_search_results <= 0) {
                                        max_instant_search_results = 9999;
                                    }
                                    for (var i = 0; i < search_results.length && i < max_instant_search_results; i++) {
                                        $searchWrap.find('.results-inner').append(search_results[i]['post_html']);
                                    }
                                }
                            }
                            else {
                                $searchResult.html('');
                            }
                            famisp_update_products_ids();
                        });
                        
                        $('#famisp-keyword').trigger('keyup');
                    }
                    
                    $('#famisp-keyword').removeClass('loading');
                });
            }
        });
    }
    
    famisp_auto_complete_search_instant();
    
    // Click on search result items
    $(document).on('click', '.famisp-results .product-item', function (e) {
        var $this = $(this);
        var product_id = $this.attr('data-product_id');
        var min_price = $this.attr('data-min_price');
        var max_price = $this.attr('data-max_price');
        var inner_html = $this.find('.product-inner').html();
        
        if (!$('.famisp-selected-products-list .selected-product-item[data-product_id="' + product_id + '"]').length) {
            var product_html = '<div class="selected-product-item" data-product_id="' + product_id + '" data-min_price="' + min_price + '" data-max_price="' + max_price + '">' +
                '<div class="product-inner">' + inner_html + '</div>' +
                '<a href="#" class="remove-btn" title="Remove">x</a>' +
                '</div>';
            $('.famisp-selected-products-list').append(product_html);
            $this.addClass('famisp-hidden');
        }
        famisp_update_products_ids();
    });
    
    function famisp_update_products_ids() {
        if ($('.famisp-selected-products-list .selected-product-item').length) {
            var product_ids = '';
            
            $('.famisp-selected-products-list .selected-product-item').each(function () {
                var this_product_id = $(this).attr('data-product_id');
                if (product_ids == '') {
                    product_ids += this_product_id;
                }
                else {
                    product_ids += ',' + this_product_id;
                }
                $('.famisp-results .product-item[data-product_id="' + this_product_id + '"]').addClass('famisp-hidden');
            });
            
            $('input[name="famisp_products_ids"]').val(product_ids);
        }
        else {
            $('input[name="famisp_products_ids"]').val('');
        }
    }
    
    famisp_update_products_ids();
    
    // Enable/Disable Sales Popup
    $(document).on('change', '.fami-all-settings-form input[type="checkbox"]', function () {
        var $this = $(this);
        var this_name = $this.attr('name');
        if (typeof this_name == 'undefined' || this_name == false) {
            return false;
        }
        var hidden_name = this_name.slice(0, -3);
        if ($(this).is(':checked')) {
            $('input[name="' + hidden_name + '"]').val('yes');
        }
        else {
            $('input[name="' + hidden_name + '"]').val('no');
        }
    });
    
    // Add new address
    $(document).on('submit', 'form[name="famisp-address-form"]', function () {
        var $thisForm = $(this);
        var new_address = $thisForm.find('.famisp-address-input').val();
        var err = false;
        
        // Escape html
        new_address = $("<div>").text(new_address).html().replace(/\"/g, '').replace(/\'/g, '');
        
        if ($.trim(new_address) == '') {
            err = true;
            $thisForm.find('.famisp-address-input').addClass('error');
        }
        else {
            $thisForm.find('.famisp-address-input').removeClass('error');
        }
        
        if (err) {
            return false;
        }
        
        var new_address_html = '<div class="famisp-address-item" data-address="' + new_address + '"><div class="famisp-item-inner">' + new_address + '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
        $('.famisp-addresses-list-wrap').append(new_address_html);
        $thisForm.find('.famisp-address-input').val('').focus();
        
        return false;
    });
    
    // Add new addresses
    $(document).on('submit', 'form[name="famisp-addresses-form"]', function () {
        var $thisForm = $(this);
        var new_addresses = $thisForm.find('.famisp-addresses-textarea').val();
        var err = false;
        
        // Escape html
        new_addresses = $("<div>").text(new_addresses).html().replace(/\"/g, '').replace(/\'/g, '');
        
        if ($.trim(new_addresses) == '') {
            err = true;
            $thisForm.find('.famisp-addresses-textarea').addClass('error');
        }
        else {
            $thisForm.find('.famisp-addresses-textarea').removeClass('error');
        }
        
        if (err) {
            return false;
        }
        
        $.each(new_addresses.split(/\n/), function (i, new_address) {
            var trim_first_word = $thisForm.find('.famisp-trim-first-words').is(':checked');
            if (trim_first_word) {
                new_address = new_address.substr(new_address.indexOf(" ") + 1);
            }
            
            if (new_address && !famisp_address_exists(new_address)) {
                var new_address_html = '<div class="famisp-address-item" data-address="' + new_address + '"><div class="famisp-item-inner">' + new_address + '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
                $('.famisp-addresses-list-wrap').append(new_address_html);
            }
        });
        
        $thisForm.find('.famisp-addresses-textarea').val('').focus();
        
        return false;
    });
    
    function famisp_address_exists(address) {
        var text_exists = false;
        var current_addresses_list_html = $('.famisp-addresses-list-wrap').html();
        // console.log(current_addresses_list_html);
        if (current_addresses_list_html.indexOf('<div class="famisp-item-inner">' + address + '</div>') >= 0) {
            console.log(address);
            text_exists = true;
        }
        
        return text_exists;
    }
    
    // Remove selected product
    $(document).on('click', '.famisp-selected-products-list .selected-product-item .remove-btn', function (e) {
        var product_id = $(this).closest('.selected-product-item').attr('data-product_id');
        $('.famisp-results .product-item[data-product_id="' + product_id + '"]').removeClass('famisp-hidden');
        $(this).closest('.selected-product-item').remove();
        famisp_update_products_ids();
        e.preventDefault();
    });
    
    // Remove an address
    $(document).on('click', '.famisp-addresses-list-wrap .famisp-address-item .remove-btn', function (e) {
        $(this).closest('.famisp-address-item').remove();
        e.preventDefault();
    });
    
    // Min/Max input group
    function famisp_input_min_max() {
        $('.famisp-input-min-max-group').each(function () {
            var $this = $(this);
            var $minInput = $this.find('.famisp-input-num-link-min');
            var $maxInput = $this.find('.famisp-input-num-link-max');
            var min_val = parseFloat($minInput.val());
            var max_val = parseFloat($maxInput.val());
            
            if (isNaN(min_val)) {
                min_val = 0;
            }
            if (isNaN(max_val)) {
                max_val = min_val;
            }
            
            $maxInput.attr('min', min_val);
            
            if (max_val < min_val) {
                max_val = min_val;
            }
            $minInput.val(min_val);
            $maxInput.val(max_val);
        });
    }
    
    famisp_input_min_max();
    $(document).on('change', '.famisp-input-min-max-group input', function () {
        famisp_input_min_max();
    });
    
    // Click outside the search
    $(document).on('click', function (e) {
        var target = e.target;
        if (!$(target).is('.famisp-search-product-wrapper') && !$(target).closest('.famisp-search-product-wrapper').length) {
            $('.famisp-search-product-wrapper').removeClass('show-search-results');
        }
    });
    
    function famisp_search_json(search_key, json_args) {
        var all_results = Array();
        $.each(json_args, function (i, v) {
            var regex = new RegExp(search_key, "i");
            if (v.post_title.search(new RegExp(regex)) != -1) {
                all_results.push(v);
            }
        });
        
        return all_results;
    }
    
    // Tabs
    function famisp_show_active_tab_content() {
        $('.famisp-tabs').each(function () {
            var $thisTabs = $(this);
            var tab_id = $thisTabs.find('.nav-tab.nav-tab-active').attr('data-tab_id');
            $thisTabs.find('.tab-content').removeClass('tab-content-active');
            $thisTabs.find('.tab-content#' + tab_id).addClass('tab-content-active');
        });
    }
    
    famisp_show_active_tab_content();
    
    $(document).on('click', '.famisp-tabs .nav-tab', function (e) {
        var $this = $(this);
        var $thisTabs = $this.closest('.famisp-tabs');
        if ($this.is('.nav-tab-active')) {
            return false;
        }
        $thisTabs.find('.nav-tab').removeClass('nav-tab-active');
        $this.addClass('nav-tab-active');
        famisp_show_active_tab_content();
        e.preventDefault();
    });
    
    // Save all settings
    $(document).on('click', '.famisp-save-all-settings', function (e) {
        var $this = $(this);
        if ($this.is('.processing')) {
            return false;
        }
        $('.fami-all-settings-form').find('.famisp-message').remove();
        $this.addClass('processing disabled').prop('disabled', true);
        var all_addresses = Array();
        var famisp_settings = Array();
        var all_settings = {
            all_addresses: all_addresses,
            famisp_settings: famisp_settings
        };
        $('.fami-all-settings-form .famisp-field').each(function () {
            var setting_key = $(this).attr('name');
            var setting_val = $(this).val();
            famisp_settings.push(
                {
                    'setting_key': setting_key,
                    'setting_val': setting_val
                }
            );
        });
        
        $('.famisp-addresses-list-wrap .famisp-address-item').each(function () {
            all_addresses.push($(this).attr('data-address'));
        });
        
        all_settings['all_addresses'] = all_addresses;
        all_settings['famisp_settings'] = famisp_settings;
        
        var data = {
            action: 'famisp_save_all_settings_via_ajax',
            all_settings: all_settings,
            nonce: famisp['security']
        };
        
        $.post(famisp['ajaxurl'], data, function (response) {
            famisp_display_multi_messages($('.fami-all-settings-form'), response, 'bottom');
            $this.removeClass('processing disabled').prop('disabled', false);
        });
        
        e.preventDefault();
    });
    
    // jQuery Accordions
    function famisp_init_accordions() {
        $('.famisp-accordions:not(.famisp-initiated-accordions)').each(function () {
            $(this).accordion({
                collapsible: true,
                heightStyle: 'content'
            }).addClass('famisp-initiated-accordions');
        })
    }
    
    famisp_init_accordions();
    
    // jQuery Tabs (Fami Tabs 2)
    function famisp_init_tabs() {
        if ($('.famisp-tab2').length) {
            $('.famisp-tab2:not(.famisp-initiated-tabs)').tabs().addClass('famisp-initiated-tabs');
        }
    }
    
    famisp_init_tabs();
    
    /**
     *
     * @param $form
     * @param response
     * @param position  top or bottom.
     */
    function famisp_display_multi_messages($form, response, position) {
        $form.find('.famisp-message').remove();
        
        var msg_class = '';
        
        if (response['err'] === 'yes') {
            msg_class += 'alert-danger notice notice-error';
        }
        else {
            msg_class += 'alert-success updated notice notice-success';
        }
        
        if ($.type(response['message']) === 'string') {
            if (response['message'] !== '') {
                if (position === 'top') {
                    $form.prepend('<div class="famisp-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
                else {
                    $form.append('<div class="famisp-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
            }
        }
        else {
            $.each(response['message'], function (index, item) {
                if (position === 'top') {
                    $form.prepend('<div class="famisp-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
                else {
                    $form.append('<div class="famisp-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
            });
        }
    }
    
    
});
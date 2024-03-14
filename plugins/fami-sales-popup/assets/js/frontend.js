jQuery(document).ready(function ($) {
    "use strict";
    
    var famisp_min_time = parseInt(famisp['sales_popup_data']['famisp_min_time']); // In millisecond
    var famisp_max_time = parseInt(famisp['sales_popup_data']['famisp_max_time']); // In millisecond
    var famisp_display_time = 15000; // 15 seconds
    
    function famisp_show_sales_popup_random() {
        if (famisp['sales_popup_data']['famisp_enable_sales_popup'] != 'yes') {
            return;
        }
        
        if ($('.famisp-sales-popup-wrap').is('.famisp-showing')) {
            return;
        }
        
        var sales_popup_data = famisp['sales_popup_data'];
        
        var toal_addresses = sales_popup_data['all_addresses'].length;
        var total_products = sales_popup_data['famisp_products'].length;
        var enable_buy_time_args = Array();
        if (sales_popup_data['famisp_enable_ran_buy_time_in_sec'] == 'yes') {
            enable_buy_time_args.push('famisp_enable_ran_buy_time_in_sec');
        }
        if (sales_popup_data['famisp_enable_ran_buy_time_in_min'] == 'yes') {
            enable_buy_time_args.push('famisp_enable_ran_buy_time_in_min');
        }
        if (sales_popup_data['famisp_enable_ran_buy_time_in_hour'] == 'yes') {
            enable_buy_time_args.push('famisp_enable_ran_buy_time_in_hour');
        }
        if (sales_popup_data['famisp_enable_ran_buy_time_in_day'] == 'yes') {
            enable_buy_time_args.push('famisp_enable_ran_buy_time_in_day');
        }
        var total_buy_times = enable_buy_time_args.length;
        if (total_buy_times <= 0 || toal_addresses <= 0 || total_products <= 0) {
            return;
        }
        
        var buy_time_index = famisp_get_random_num(0, total_buy_times - 1);
        var address_index = famisp_get_random_num(0, toal_addresses - 1);
        var product_index = famisp_get_random_num(0, total_products - 1);
        var buy_time_in = enable_buy_time_args[buy_time_index];
        var buy_time_unit_key = '';
        var buy_time_min = 0;
        var buy_time_max = 0;
        switch (buy_time_in) {
            case 'famisp_enable_ran_buy_time_in_sec':
                buy_time_min = sales_popup_data['famisp_min_random_buy_time_in_sec'];
                buy_time_max = sales_popup_data['famisp_max_random_buy_time_in_sec'];
                buy_time_unit_key = 'second';
                break;
            case 'famisp_enable_ran_buy_time_in_min':
                buy_time_min = sales_popup_data['famisp_min_random_buy_time_in_min'];
                buy_time_max = sales_popup_data['famisp_max_random_buy_time_in_min'];
                buy_time_unit_key = 'minute';
                break;
            case 'famisp_enable_ran_buy_time_in_hour':
                buy_time_min = sales_popup_data['famisp_min_random_buy_time_in_hour'];
                buy_time_max = sales_popup_data['famisp_max_random_buy_time_in_hour'];
                buy_time_unit_key = 'hour';
                break;
            case 'famisp_enable_ran_buy_time_in_day':
                buy_time_min = sales_popup_data['famisp_min_random_buy_time_in_day'];
                buy_time_max = sales_popup_data['famisp_max_random_buy_time_in_day'];
                buy_time_unit_key = 'day';
                break;
        }
        buy_time_min = parseInt(buy_time_min);
        buy_time_max = parseInt(buy_time_max);
        
        if (buy_time_max < buy_time_min) {
            buy_time_max = buy_time_min;
        }
        
        if (buy_time_max == 0) {
            buy_time_in = 'famisp_min_random_buy_time_in_min';
            buy_time_max = sales_popup_data['famisp_min_random_buy_time_in_min'];
            if (buy_time_max < buy_time_min || buy_time_max <= 0) {
                buy_time_max = 59;
            }
        }
        
        var purchased_time = famisp_get_random_num(buy_time_min, buy_time_max);
        if (purchased_time != 1) {
            buy_time_unit_key = buy_time_unit_key + 's';
        }
        var time_unit = famisp['text'][buy_time_unit_key];
        
        if (!$('body .famisp-sales-popup-wrap').length) {
            $('body').append('<div class="famisp-sales-popup-wrap"></div>');
        }
        
        var product = sales_popup_data['famisp_products'][product_index];
        var address = sales_popup_data['all_addresses'][address_index];
        var product_name = '<a class="famisp-product-name" href="' + product['url'] + '">' + product['product_name'] + '</a>';
        var popup_text = sales_popup_data['famisp_popup_text'].replace('{address}', address).replace('{product_name}', product_name).replace('{purchased_time}', purchased_time).replace('{time_unit}', time_unit);
        var popup_thumb = '<a class="famisp-product-thumb" href="' + product['url'] + '"><img width="' + product['img']['width'] + '" height="' + product['img']['height'] + '" src="' + product['img']['url'] + '" /></a>'
        var html = '<div class="famisp-sales-popup">' + popup_thumb + ' <p>' + popup_text + '</p> <span class="famisp-close">x</span></div>';
        
        $('.famisp-sales-popup-wrap').html(html).addClass('famisp-showing').fadeIn().delay(9000).fadeOut(function () {
            $('.famisp-sales-popup-wrap').removeClass('famisp-showing');
        });
    }
    
    $(document).on('click', '.famisp-sales-popup .famisp-close', function (e) {
        $('.famisp-sales-popup-wrap').removeClass('famisp-showing').css({
            'display': 'none'
        });
        e.preventDefault();
    });
    
    var count_run = 0;
    (function famisp_loop_randomize() {
        count_run++;
        var rand = famisp_get_random_num(famisp_min_time, famisp_max_time); // Math.round(Math.random() * famisp_min_time) + famisp_max_time;
        setTimeout(function () {
            famisp_show_sales_popup_random();
            famisp_loop_randomize();
        }, rand);
    }());
    
    function famisp_get_random_num(min_num, max_num) {
        return Math.floor(Math.random() * (max_num - min_num + 1) + min_num);
    }
    
});
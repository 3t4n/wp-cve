jQuery(document).ready(function ($) {
    'use strict';
    $('.vi-ui.checkbox').checkbox();
    $('select.vi-ui.dropdown').dropdown({placeholder: ''});

    $('#vicaca-clear-by-cache-size').on('change', function () {
        if ($(this).prop('checked')) {
            $('#vicaca-cache-size').closest('tr').fadeIn(200);
        } else {
            $('#vicaca-cache-size').closest('tr').fadeOut(200);
        }
    }).trigger('change');

    $('#vicaca-clear-by-time-interval').on('change', function () {
        if ($(this).prop('checked')) {
            $('#vicaca-cache-interval').closest('tr').fadeIn(200);
        } else {
            $('#vicaca-cache-interval').closest('tr').fadeOut(200);
        }
    }).trigger('change');

    $('#vicaca-cache-size-unit').on('change', function () {
        if ($(this).val() === 'percent') {
            $('#vicaca-cache-size').attr('max', 100);
        } else {
            $('#vicaca-cache-size').attr('max', '');
        }
    }).trigger('change');

    $('#vicaca-execution-link').on('change', function () {
        if ($(this).prop('checked')) {
            $('#vicaca-execution-link-url').closest('tr').fadeIn(200);
        } else {
            $('#vicaca-execution-link-url').closest('tr').fadeOut(200);
        }
    }).trigger('change');
    $('.vicaca-execution-link-url-copy').on('click', function () {
        $('#vicaca-execution-link-url').select();
        document.execCommand('copy');
        alert(vicaca_settings_params.i18n_url_copied);
    });
    $('#vicaca-execution-link-url').on('click', function () {
        $(this).select();
    });
    $('.vicaca-execution-link-secret-refresh').on('click', function () {
        let $secret = $('#vicaca-execution-link-secret'), $url = $('#vicaca-execution-link-url');
        let a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split(""), b = [];
        for (let i = 0; i < 32; i++) {
            let j = (Math.random() * (a.length - 1)).toFixed(0);
            b[i] = a[j];
        }
        let secret = b.join("");
        $secret.val(secret);
        let execution_link_url = new URL(vicaca_settings_params.execution_link_url);
        execution_link_url.searchParams.set('secret', secret);
        $url.val(execution_link_url);
    });
});
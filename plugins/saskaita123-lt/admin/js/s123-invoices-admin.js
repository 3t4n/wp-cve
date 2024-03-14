/* Write admin page custom scripts here */

jQuery(document).ready(function ($) {

    const s123_error_alert = $("#s123-error-alert");
    const s123_success_alert = $("#s123-success-alert");

    function s123_show_success_alert(message) {
        s123_error_alert.addClass('hidden');
        s123_success_alert.find('p').text(message);
        s123_success_alert.removeClass('hidden');
        s123_success_alert.addClass('slide-in');

        setTimeout(function() {
            s123_success_alert.addClass('hidden');
        }, 4000);
    }

    function s123_show_error_alert(message) {
        s123_success_alert.addClass('hidden');
        s123_error_alert.find('p').text(message);
        s123_error_alert.removeClass('hidden');
        s123_error_alert.addClass('slide-in');

        setTimeout(function() {
            s123_error_alert.addClass('hidden');
        }, 4000);
    }

    // tabs
    $('#s123-tabs li a').click(function (e) {
        e.preventDefault();
        const tab = $(this);

        $('.tab-pane.active').removeClass("active");
        $('#s123-tabs a.active').removeClass("active");

        $(this).addClass("active");
        $(tab.attr('href')).addClass("active");
    });

    // api key form submit
    $('#apiKeySubmitForm').submit(function (e) {
        e.preventDefault();
        const form_data = $(this).serializeArray();

        $.ajax({
            url: s123.base_url,
            type: 'post',
            data: form_data,
            success: function (response) {
                if (response.success) {
                    s123_show_success_alert(response.message);
                    location.reload();
                } else {
                    s123_show_error_alert(response.message);
                }
            },
            fail: function (err) {
                //
            }
        });
    });

    // invoice settings form submit
    $('#invoiceSettingsSubmitForm').submit(function (e) {
        e.preventDefault();
        const form_data = $(this).serializeArray();

        $.ajax({
            url: s123.base_url,
            type: 'post',
            data: form_data,
            success: function (response) {
                if (response.success) {
                    s123_show_success_alert(response.message);
                } else {
                    s123_show_error_alert(response.message);
                }
            },
            fail: function (err) {
                //
            }
        });
    });

    $('#showApiKey').click(function () {
        const api_key_input = $('#s123_api_key');
        if (api_key_input.prop("type") !== "text") {
            api_key_input.prop("type", "text");
        } else {
            api_key_input.prop("type", "password");
        }
    });
});
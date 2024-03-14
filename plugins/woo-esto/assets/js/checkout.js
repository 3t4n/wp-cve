jQuery(document).ready(function ($) {
    $('body').on('change', '.esto-pay-countries', function () {
        var country_code = $(this).val();
        var selected_country = $('.esto-pay-logos__country--' + country_code);
        if (!selected_country.is('visible')) {
            $('.esto-pay-logos__country').hide();
            selected_country.show();
            $('div.esto-pay-logo.selected').removeClass('selected');
        }
    }).on('click', '.esto-pay-logo', function () {
        if (!$(this).hasClass('selected')) {
            $('input[name="esto_pay_bank_selection"]').val($(this).attr('data-bank-id'));
            $(this).addClass('selected');
            $(this).siblings().removeClass('selected');
        }
    });
});
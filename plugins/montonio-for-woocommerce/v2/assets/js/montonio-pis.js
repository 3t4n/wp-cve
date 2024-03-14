jQuery(document).ready(function($) {
	'use strict'; 

    $(document).on('updated_checkout', function(){
        if(sessionStorage.getItem('montonioPreferredCountry')){
            $('.montonio-payments-country-dropdown').val(sessionStorage.getItem('montonioPreferredCountry')).change();
        }

        if(sessionStorage.getItem('montonioPreferredProvider')){
            var selectedBank = sessionStorage.getItem('montonioPreferredProvider');

            if (!$('.montonio-bank-item[data-bank="' + selectedBank + '"]').hasClass('montonio-bank-item--hidden')) {
                $('.montonio-bank-item[data-bank="' + selectedBank + '"]').addClass('active').siblings().removeClass('active');
                $('#montonio_payments_preselected_bank').val(selectedBank);
            }
        }
    });

    $(document).on('change', '.montonio-payments-country-dropdown', function(e) {
        var selectedRegion = this.value;

        sessionStorage.setItem('montonioPreferredCountry', selectedRegion);
        $('.montonio-bank-item').addClass('montonio-bank-item--hidden');
        $('.bank-region-' + selectedRegion).removeClass('montonio-bank-item--hidden');

        $('#montonio_payments_preselected_bank').val('');
        $('.montonio-bank-item').removeClass('active');
    });

    $(document).on('click', '.montonio-bank-item', function() {
        var selectedBank = $(this).data('bank');

        sessionStorage.setItem('montonioPreferredProvider', selectedBank);
        $(this).addClass('active').siblings().removeClass('active');
        $('#montonio_payments_preselected_bank').val(selectedBank);
    });
});
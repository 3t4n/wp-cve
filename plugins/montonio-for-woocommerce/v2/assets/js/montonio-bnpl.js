jQuery(document).ready(function($) {
	'use strict'; 

    function selectBnplPeriod() {
        let selectedPeriod = sessionStorage.getItem('montonioBnplPeriod');

        if(selectedPeriod && $('.montonio-bnpl-item[data-bnpl-period="' + selectedPeriod + '"]').length && !$('.montonio-bnpl-item[data-bnpl-period="' + selectedPeriod + '"]').hasClass('montonio-bnpl-item--disabled') ) {          
            $('.montonio-bnpl-item[data-bnpl-period="' + selectedPeriod + '"]').addClass('active').siblings().removeClass('active');
            $('#montonio_bnpl_period').val(selectedPeriod);
        }
    }

    selectBnplPeriod();

    $(document).on('updated_checkout', function() {
        selectBnplPeriod();
    });

    $(document).on('click', '.montonio-bnpl-item', function() {
        let selectedPeriod = $(this).data('bnpl-period');

        sessionStorage.setItem('montonioBnplPeriod', selectedPeriod);
        $(this).addClass('active').siblings().removeClass('active');
        $('#montonio_bnpl_period').val(selectedPeriod);
    });
});
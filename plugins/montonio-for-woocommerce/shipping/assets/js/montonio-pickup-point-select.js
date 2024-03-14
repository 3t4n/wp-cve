jQuery(document).ready(function($) {
    'use strict';

    function setupMontonioPickupPoints() {
        if ($().selectWoo) {
            var select = $('.montonio-pickup-point-select');
            select.selectWoo({
                width: '100%',
            });
        }
    }

    function customCheckoutCompatibility() {
        if ($('.montonio-pickup-point-select').length) {
                $('.montonio_pickup_point_value').val('');
                
                if ($('form[name="checkout"] [name="montonio_pickup_point"]').length == 0) {
                    $('form[name="checkout"]').append('<input type="hidden" class="montonio_pickup_point_value" name="montonio_pickup_point" value="">');

                    $(document).on('change', '.montonio-pickup-point-select', function() {
                        $('.montonio_pickup_point_value').val( $(this).val() );
                    });
                }
        } else {
            $('form[name="checkout"] .montonio_pickup_point_value').remove();
        }
    }

    $(document).on('updated_checkout', function(){
        setupMontonioPickupPoints();
        customCheckoutCompatibility();

        if ($('input[name="shipping_method[0]"]').is(':radio')) {
            var selected_service = $('input[name^="shipping_method"]:checked').val();
        } else {
            var selected_service = $('input[name="shipping_method[0]"]').val();
        }

        if (selected_service && sessionStorage.getItem('montonioPreferredPickupPoint')){
            try {
                if(JSON.parse(sessionStorage.getItem('montonioPreferredPickupPoint'))[selected_service]) {
                    $('.montonio-pickup-point-select').val(JSON.parse(sessionStorage.getItem('montonioPreferredPickupPoint'))[selected_service]).change();
                }
            } catch(err) {}
        }
    });

    $(document).on('change', '.montonio-pickup-point-select', function(){
        try {
            let storage = JSON.parse(sessionStorage.getItem('montonioPreferredPickupPoint')) || {}
            var selected_pickup_point = $(this).find(':selected').map(function(){ return $(this).val(); }).get(0);

            if ($('input[name="shipping_method[0]"]').is(':radio')) {
                var selected_service = $('input[name^="shipping_method"]:checked').val();
            } else {
                var selected_service = $('input[name="shipping_method[0]"]').val();
            }

            storage[selected_service] = selected_pickup_point;
            sessionStorage.setItem('montonioPreferredPickupPoint', JSON.stringify(storage));
        } catch(err) {}

        $('.montonio-pickup-point-select').not(this).val(selected_pickup_point).selectWoo();
    });

    $(document).on('select2:open', '.montonio-pickup-point', function() {
        setTimeout(function() {
            $('.select2-container--open').addClass('montonio-pickup-point-container');
            document.querySelector('.select2-container--open .select2-search__field').focus();
        }, 10);
    });
});
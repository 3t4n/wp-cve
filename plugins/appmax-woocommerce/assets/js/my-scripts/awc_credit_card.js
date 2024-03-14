(function( $ ) {
    'use strict';

    $( function() {

        $("#billing_phone").mask('(99) 99999-9999');
        $("#billing_postcode").mask("99999-999");
        $("#card_number").mask("9999 9999 9999 9999");
        $("#card_cpf").mask("999.999.999-99");

        $('.tab-2').prev().remove();

    });

}( jQuery ));
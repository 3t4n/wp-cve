(function ($) {

    $(document).ready(function () {

        // console.log(subtotal_object);

        var findIt = ".hidden_price";

        // Sub Total Part on change quantity field
        $('input[class="input-text qty text"]').on('change', function () {

            var price, qty = $(this).val();

            price = $(this).closest('.variant tbody tr').find(findIt).val();

            var variant_subtotal = parseFloat(qty * price).toFixed(subtotal_object.number_of_decimals)
                                    .replace('.',subtotal_object.decimal_sep)
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, subtotal_object.thousand_sep);

            $(this).closest('.variant tbody tr').find('.pvtfw_subtotal').html(variant_subtotal);

        });

        // On load calculate subtotal 
        $('table.variant tr').each(function () {

            var price, qty = $('input[class="input-text qty text"]').val();

            price = $(this).closest('.variant tbody tr').find(findIt).val();

            // console.log(parseFloat(price));

            var variant_subtotal = parseFloat(qty * price).toFixed(subtotal_object.number_of_decimals);

            // @since 1.4.17. Checking if the quantity field is disabled then price will return undefined
            // So, we will display the out of stock text in subtotal column for this variation
            if( typeof( price ) === 'undefined' ){ 
                $(this).closest('.variant tbody tr').find('.pvt-subtotal-wrapper').html( $("p.stock.out-of-stock").html() ); 
            }
            else{
                $(this).closest('.variant tbody tr').find('.pvtfw_subtotal')
                                    .append(variant_subtotal.replace('.',subtotal_object.decimal_sep)
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, subtotal_object.thousand_sep));
            }

        });

    });

}(jQuery));
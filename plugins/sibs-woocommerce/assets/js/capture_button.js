(function( $ ) {
    'use strict';

    $('.inside').on('click','#my_special_button', function(){

        // get the order_id from the button tag
        var order_id = $(this).data('order_id');

        // get the product_id from the button tag
        var product_id = $(this).data('product_id');
		
		// get the amount to be captured
		var total_amount = $('input[data-scenario=scenario1]').val();

        // send the data via ajax to the sever
        $.ajax({
            type: 'POST',
            url: mb_script_var.ajaxurl,
            dataType: 'json',
            data: {
                action: 'add_my_product_to_order',
                order_id: order_id, 
                product_id: product_id,
				amount_id: total_amount
            },
            success: function (data, textStatus, XMLHttpRequest) {

                // show the control message
                alert(data.msg);

                if(data.error == 0)
                { 
                    location.href = location.href; // Refresh the page
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

    });


})( jQuery );

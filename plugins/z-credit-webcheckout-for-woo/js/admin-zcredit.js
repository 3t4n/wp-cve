(function($){
    var $document = $(document);
    var required_fields;
    var $iframe,
		$payments_type,
		$payment_authorized,
        iframe_height,
        iframe_width,
        min_payments_number,
        max_payments_number;

    $document.ready(function(){
        required_fields = $('.required-field.zcredit-field');
        payments_fields = $('.payments-field');
		steps_fields = $('.steps-fields');

        $iframe = $('#woocommerce_zcredit_checkout_payment_iframe');
        iframe_height = $('#woocommerce_zcredit_checkout_payment_iframe_height');
        iframe_width = $('#woocommerce_zcredit_checkout_payment_iframe_width');
		
		$payments_type = $('#woocommerce_zcredit_checkout_payment_payments_type');
		$payments_steps_chk = $('#woocommerce_zcredit_checkout_payment_use_installments_steps');
		$payment_authorized = $('#woocommerce_zcredit_checkout_payment_payment_authorized');
/*         min_payments_number = $('#woocommerce_zcredit_checkout_payment_min_payments_number');
        max_payments_number = $('#woocommerce_zcredit_checkout_payment_max_payments_number'); */
        terminal_number = $('#woocommerce_zcredit_checkout_payment_terminal_number');
        terminal_password = $('#woocommerce_zcredit_checkout_payment_password');
        Hide_Amount = $('#woocommerce_zcredit_checkout_payment_Hide_Amount');

        $iframe.change(function(){
            is_iframe($(this));
        });
		
		$payments_type.change(function(){
            //is_payments_type($(this));
			Handle_Payments_Fields($(this));
        });

		$payments_steps_chk.change(function(){
			Handle_Payments_steps_Fields($(this));
        });
		
		$payment_authorized.change(function(){
            is_payments_authorized($(this));
        });

        required_fields.change(function(){
            check_required_fields();
        });

		$('#mainform').submit(function(){
			//console.log("in: " + ($payments_type.val()));
			if($payments_type.val() != "none"){
				if(max_payments_number.hasClass('error') || min_payments_number.hasClass('error')){
					console.log("submit false");
					return false;
				}
			}
			//console.log("submit true");
			return true;
		});
		

        $document.on('click', '.zc-capture-payment', function(){
            var is_right = true;
            var order_id = $(this).attr('data-id');
            var sum = $(this).attr('data-sum');
            var ordersum = $(this).attr('data-ordersum');
            var message = $(this).attr('data-message');
            if( parseFloat(sum) < parseFloat(ordersum) ) {
                is_right = confirm(message);
            }
            $('.zc-err-payment').remove();
            if(is_right){
                send_authorized_payment(order_id);
            }
        });

        is_iframe($iframe);
		//is_payments_type($payments_type);
		Handle_Payments_Fields($payments_type);
		Handle_Payments_steps_Fields($payments_steps_chk);
		is_payments_authorized($payment_authorized);
        check_required_fields();
    });


    function is_iframe(el){
        if(el.val() == "1"){
            iframe_height.parents('tr').eq(0).css({'display': 'table-row'});
            iframe_width.parents('tr').eq(0).css({'display': 'table-row'});
            //hide_customer.parents('tr').eq(0).css({'display': 'none'});
        }
        else{
            iframe_height.parents('tr').eq(0).css({'display': 'none'});
            iframe_width.parents('tr').eq(0).css({'display': 'none'});
            //hide_customer.parents('tr').eq(0).css({'display': 'table-row'});
        }
    }
	
/* 	function is_payments_type(el){
        if(el.val() != "none"){
            min_payments_number.parents('tr').eq(0).css({'display': 'table-row'});
            max_payments_number.parents('tr').eq(0).css({'display': 'table-row'});
            //hide_customer.parents('tr').eq(0).css({'display': 'none'});
        }
        else{
            min_payments_number.parents('tr').eq(0).css({'display': 'none'});
            max_payments_number.parents('tr').eq(0).css({'display': 'none'});
            //hide_customer.parents('tr').eq(0).css({'display': 'table-row'});
        }
    } */
	
	function Handle_Payments_Fields(el) {
		//console.log("In Handle_Payments_Fields");
        payments_fields.each(function(){
			
			if(el.val() != "none"){
				$(this).parents('tr').eq(0).css({'display': 'table-row'});
				if ($(this).hasClass( "payments-title" )) {
					$(this).eq(0).css({'display': 'block'});
				}
			}
			else{
				$(this).parents('tr').eq(0).css({'display': 'none'});
				if ($(this).hasClass( "payments-title" )) {
					$(this).eq(0).css({'display': 'none'});
				}
			}
        });
		
		Handle_Payments_steps_Fields($payments_steps_chk);
    }
	
	function Handle_Payments_steps_Fields(el) {
        steps_fields.each(function(){
			
			if(el.is(":checked")){
				console.log("in1");
				$(this).parents('tr').eq(0).css({'display': 'table-row'});
				if ($(this).hasClass( "payments-title" )) {
					$(this).eq(0).css({'display': 'block'});
				}
			}
			else{
				console.log("in2");
				$(this).parents('tr').eq(0).css({'display': 'none'});
				if ($(this).hasClass( "payments-title" )) {
					$(this).eq(0).css({'display': 'none'});
				}
			}
        });
    }
	
	function is_payments_authorized(el){
		//console.log("In is_payments_authorized");
        if(el.val() != "regular"){
            Hide_Amount.parents('tr').eq(0).css({'display': 'table-row'});
            //terminal_password.parents('tr').eq(0).css({'display': 'table-row'});
            //hide_customer.parents('tr').eq(0).css({'display': 'none'});
        }
        else{
            Hide_Amount.parents('tr').eq(0).css({'display': 'none'});
            //terminal_password.parents('tr').eq(0).css({'display': 'none'});
            //hide_customer.parents('tr').eq(0).css({'display': 'table-row'});
        }
    }

    function check_required_fields() {
        required_fields.each(function(){
            var $this = $(this);
            var $parent = $this.parent();
            var validate = false;
            if($this.val()){
                validate = true;
				if(($this.attr('id') == min_payments_number.attr('id')) || 
				   ($this.attr('id') == max_payments_number.attr('id')) 
				   && $payments_type.val() != "none"){
					   
                    if($this.val() < 1 || $this.val() > 99){
                        validate = false;
                    }
                }
            }
            else{
                validate = false;
            }
			
            if(validate){
                $this.removeClass('error');
                $parent.find('.description.error').remove();
            }
            else{
                $this.addClass('error');
                var error = '<p class="description error zitem">' + $this.attr('data-error') + '</p>';
                if(!$parent.find('.description.error').length){
                    $this.after(error);
                }
            }
        });
    }

    function send_authorized_payment(order_id) {
        $.ajax({
            type: 'post',
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: 'send_authorized_payment',
                order_id: order_id
            },
            beforeSend: function(){
                $('#woocommerce-order-items').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            },
            success: function(data){
                console.log('success ', data);
                $('#woocommerce-order-items').unblock();
                if(data.success){
                    $('#order_status').val('wc-processing').change();
                    location.reload();
                }
                else if(data.message){
                    var err_message = typeof data.error_code != 'undefined' ?  data.message + ' #' + data.error_code : data.message;
                    $('.zc-capture-payment').parents('.wc-order-data-row').eq(0).append('<span class="zc-err-payment">' + err_message + '</span>');
                }
            }
        });
    }

})(jQuery);
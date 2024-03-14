jQuery(document).ready(function($){
	// open price note panel
	$("#awspn-open-panel").on('click', function(){
		$('.awspn-woo-price-note_tab a').trigger('click');
	});


	// toggle order/emails box
	$("#awspn_show_on_order_and_email").change(function() {
		
    	if($('#awspn_product_price_note').val() == ''){
    		alert("Please enter price note first.");
    		$(this).attr("checked", false);
    	}

    	if(this.checked) {
        	$('.awspn_show_on_order_and_email_box').show();
        	
        	$('.awspn-oe-sep').text($('#awspn_product_price_note_separator').val());

        	if($('#awspn_product_price_note_oe_texts').val() == ''){
        		$('.awspn-oe-texts').text($('#awspn_product_price_note').val());
        	}else{
        		$('.awspn-oe-texts').text($('#awspn_product_price_note_oe_texts').val());        		
        	}

        	if($('#awspn_product_price_note_oe_label').val() !== ''){
        		$('.awspn-oe-label').text($('#awspn_product_price_note_oe_label').val());
        	}

    	} else {
    		$('.awspn_show_on_order_and_email_box').hide();
    	}
	});

	// toggle price for order/emails
	$("#awspn_excl_price_on_order_and_email").change(function() {
		
    	if(this.checked) {
    		$('.awspn-oe-price').hide();
    	} else {
        	$('.awspn-oe-price').show();
    	}
	});

	// toggle separator
	$("#awspn_excl_sep_on_order_and_email").change(function() {
		
    	if(this.checked) {
    		$('.awspn-oe-sep').hide();
    	} else {
        	$('.awspn-oe-sep').show();
    	}
	});

	// change label
	$('#awspn_product_price_note_oe_label').keyup(function () {
		  $('.awspn-oe-label').text($(this).val());
	});

	// change texts
	$('#awspn_product_price_note_oe_texts').keyup(function () {
		 $('.awspn-oe-texts').text($(this).val());
	});

	// check if value of custom label is empty
	$('#awspn_product_price_note_oe_label').on('blur', function(){
		var label = $(this).val();
		if( label == '' ){
			$('.awspn-oe-label').text('Price note');
		}
	});

	// check if value of custom text is empty
	$('#awspn_product_price_note_oe_texts').on('blur', function(){
		var label = $(this).val();
		if( label == '' ){
			$('.awspn-oe-texts').text($('#awspn_product_price_note').val());
		}
	});
});
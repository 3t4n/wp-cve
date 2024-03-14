// JavaScript Document
jQuery(document).ready(function($){
	var btn_str = 'form.variations_form.cart input[type="submit"], form.variations_form.cart button[type="submit"]';
	var woo_csn_related = false;
	
	$('input[name="variation_id"]').on('change', function(){
		var variation_id = $(this).val();
		if(variation_id){			
			if(woo_cs_obj.coming_soon[variation_id]=='yes'){
				$(btn_str).addClass('woo_csn_disabled').prop('disabled', true);
				$('form.variations_form.cart').append(woo_cs_obj.woo_csn_notice);
				woo_csn_related = true;
			}else{
				$(btn_str).removeClass('woo_csn_disabled').prop('disabled', false);
				$('form.variations_form.cart .woo_csn_notices').remove();
			}
		}else{
			$('form.variations_form.cart .woo_csn_notices').remove();
		}
	});
	
	$('body').on('change', $(btn_str), function(){
		if(woo_csn_related && $(this).prop('disabled'==true)){
			
		}
	});
	
});
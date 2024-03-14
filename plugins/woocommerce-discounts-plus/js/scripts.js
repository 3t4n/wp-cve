// JavaScript Document
jQuery(document).ready(function($){
	
	$('.wpd_need_discount, .wpd_discounts_agent').on('click', function(){
		
		if($('.cart_item.active').length==0)
		$('.cart_item').eq(0).click();
		
	});
	
	$('.wpd_discounts li').on('click', function(){
		var obj = $('.cart_item.active');
		var qty = $(this).attr('data-qty');
		$(obj).find('input.qty').val(qty);
		$('.wpd_discounts').slideUp();
		$('input[name="update_cart"]').click();
		
	});
	
	$('.cart_item').on('click', function(){
		var product_key = $(this).find('input.qty').attr('name').replace('cart[', '').replace('][qty]','');
		var product_name = $(this).find('.product-name a').html();
		var prod_div = '.wpd_discounts div#discount_product_'+product_key;
		$('.wpd_discounts').show();
		$('.wpd_discounts div').hide();		
		$(prod_div).fadeIn();
		$(prod_div+' .wdp_availble_discounts').fadeIn();
		$(prod_div+' .wdp_availble_discounts span').html(product_name);
		$('.cart_item').removeClass('active');
		$(this).addClass('active');
		
	});
	
	$('body').on('change', '.product-quantity input[id^="quantity_"]', function(){
		
		var obj = $(this).parents().eq(2).find('.plus_discount_type small');
		
		if($(this).val()>1){
			obj.fadeIn();
		}else{
			obj.hide();
		}
		
		obj.html('x'+$(this).val());
	});
	
});

var wdp = {
	load_discount_character: function(){
		//jQuery('.woocommerce form').eq(0).prepend('<div class="wpd_discounts_agent"><div class="wpd-discount-wrap"><div class="wpd-character-with-board"></div><div class="wpd-character-face face-rotatation"></div><div class="wpd-character-arm arm-rotatation"></div></div></div>').addClass('wdp-form');
	}
};

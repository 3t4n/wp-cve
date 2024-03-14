// JavaScript Document
function plus_discount_type_refresh($){
	
	if($('label[for^="woocommerce_tiers"]').length>0){

	}
	
	if($('label[for^="plus_discount_quantity"]').length>0){
		var plus_discount_type = $('.plus_discount_type_field input[name="plus_discount_type"]:checked').val();
	
		var label = '';
		switch(plus_discount_type){
			default:
			case 'quantity':
				label = 'Quantity (min.)';
			break;
			case 'weight':
				label = 'Weight ('+wdp_obj.woocommerce_weight_unit+')';
			break;
		}
	
		$('label[for^="plus_discount_quantity"]').each(function(){
			$(this).html(label);
		});
	}
	
	if($('input[name^="wdct[qty]"]').length>0){
		var plus_discount_type = $('select[name="woocommerce_plus_discount_type"]').val();
		
		var label = '';
		var type = '';
		switch(plus_discount_type){
			default:
			case 'quantity':
				label = 'Qty.';
				type = 'number';
			break;
			case 'weight':
				label = 'Weight ('+wdp_obj.woocommerce_weight_unit+')';
				type = 'text';
			break;
		}
	
		$('input[name^="wdct[qty]"]').each(function(){
			$(this).attr({'placeholder':label});
		});
	}	
	

}


jQuery(document).ready(function($){
	
	if(wcdp_obj.sale_applied=="false"){
		$('#_sale_price').on('blur, change', function(){
			
			var sale_price = $(this).val();
			
			if(sale_price>0){
				alert("Discounts plus will not apply when using sale price.");
				$('.discounts_plus_tab.discounts_plus_options > a').addClass('disabled');
			}else{
				$('.discounts_plus_tab.discounts_plus_options > a').removeClass('disabled');
			}
		});
	}
	
	setTimeout(function(){

		if($('#_sale_price').length>0){
			var sale_price = $('#_sale_price').val();
			if(sale_price>0 && wcdp_obj.sale_applied=="false")
			$('.discounts_plus_tab.discounts_plus_options > a').addClass('disabled').attr('title', 'Discounts plus will not apply when using sale price.');
		}

		$('#select2-woocommerce_discount_type-container').parent().addClass('woocommerce_discount_type');
		
		$('label[for^="woocommerce_tiers"]').parents().eq(2).addClass('woocommerce_tiers_wrappers');
		$('label[for="woocommerce_show_discounted_price_shop"]').parents().eq(2).addClass('woocommerce_show_discounted_price_shop_wrapper');
		$('label[for="woocommerce_show_discounted_price_sp"]').parents().eq(2).addClass('woocommerce_show_discounted_price_sp_wrapper');
		$('label[for="woocommerce_show_discounted_price"]').parents().eq(2).addClass('woocommerce_show_discounted_price_wrapper');
		$('label[for="woocommerce_cart_info"]').parents().eq(1).addClass('woocommerce_cart_info_wrapper');
		$('label[for="woocommerce_css_old_price"]').parents().eq(1).addClass('woocommerce_css_old_price_wrapper');
		$('label[for="woocommerce_css_new_price"]').parents().eq(1).addClass('woocommerce_css_new_price_wrapper');
		$('label[for="woocommerce_show_on_subtotal"]').parents().eq(2).addClass('woocommerce_show_on_subtotal_wrapper');


		if($('#woocommerce_discount_type').val()=='flat'){
			
		}else{
			$('.woocommerce_tiers_wrappers').find('input[type="checkbox"]').removeAttr('checked');
			$('.woocommerce_tiers_wrappers').hide();
		}
		
		
		if($('.wdp-guy').length>0){
			$('.notice').fadeOut();
		}
		
		
	}, 1000);

	$('body').on('click', '.wdp-guy', function(){
		$(this).fadeOut();
	});
	
	$('body').on('click', '.wdp-optional-wrappers, .wcdp-optional-wrappers', function(){

		$('tr[class$="_wrapper"]:hidden').show();
		$('div[class$="_wrapper"]:hidden').show(function () {

			$(this).css('display', 'flex');
		});
		// console.log($('div[class$="_wrapper"]:hidden'));
		if($('#gj_logic_status').is(':checked')){
			$('div.row.wpdp_special_offer_wrapper').show(function(){

				$(this).css('display','flex');
			});
		}else{

			setTimeout(function(){
				$('div.row.wpdp_special_offer_wrapper').hide(function () {
				});

			});

		}

		$(this).fadeOut();
	});
	
	$('body').on('change', 'select[name="s2_role"]', function(){
		//console.log($(this).attr('data-val'));
	
		var option = $(this).find('option:selected').data('val');
		//console.log(option);
		$('input[name="s2_role_discount"]').val(option);
		$('.s2_role_discount_type').html(option);
	});
	
	$('.plus_discount_type_field input[name="plus_discount_type"]').on('click', function(){
		plus_discount_type_refresh($);
	});
	plus_discount_type_refresh($);

	$('select[name="woocommerce_plus_discount_type"]').on('change', function(){
		plus_discount_type_refresh($);
	});	
	
	$('input[name="plus_discount_enabled"]').on('click', function(){
		if($(this).val()=="yes"){
			
		}
	});
	if($('.wcdp_disabled').length>0){
		$.each($('.wcdp_disabled'), function(){
			$(this).prop('disabled', true);
		});
	}
	
	if($('.wcdp_disable').length>0){
		$.each($('.wcdp_disable'), function(){
			var str = $(this).parent().html();
			if(~str.indexOf(wcdp_obj.wdp_premium_check)){
				$(this).prop('disabled', true);
			}
		});
	}

	if($('.wcdp_cart_disable').length>0){
		$.each($('.wcdp_cart_disable'), function(){
			$(this).prop('disabled', true);
		});
	}


	$('.wcdp.wrap a.nav-tab').click(function(){
		$(this).siblings().removeClass('nav-tab-active wdp');
		$(this).addClass('nav-tab-active wdp');
		$('.nav-tab-content').hide();
		$('.nav-tab-content').eq($(this).index()).show();
		window.history.replaceState('', '', wcdp_obj.this_url+'&t='+$(this).index());
		$('form input[name="wcdp_tab"]').val($(this).index());
		$('.wrap.wcdp').attr('class', 'wrap wcdp tab-'+$(this).index());
		wcdp_obj.wcdp_tab = $(this).index();
	});

	$('.plus_discount_enabled_field ul li').eq(0).append(' <a class="wcdp_manage_link" href="'+wcdp_obj.this_url+'&t=0" target="_blank">(Manage)</a>');
	$('.plus_discount_enabled_field ul li').eq(1).append(' <a class="wcdp_manage_link" href="'+wcdp_obj.this_url+'&t=2" target="_blank">(Manage)</a>');
	$('.plus_discount_enabled_field ul li').eq(2).append(' <a class="wcdp_cart_manage_link" href="'+wcdp_obj.this_url+'&t=0&cart" target="_blank">(Change)</a>');

	$('.plus_discount_enabled_field input:radio').on('change', function(){

		$('.plus_discount_enabled_field ul li a.wcdp_manage_link').hide();
		$('.plus_discount_enabled_field input:radio:checked').parents('li').find('a.wcdp_manage_link').show();

		if($(this).val() == 'cart_based'){


		}

	});

	$('.plus_discount_enabled_field input:radio').change();

	$('.wcdp_error_message_link').on('click', function(e){

		e.preventDefault();

		let searchParams = new URLSearchParams($(this).prop('href'));

		let t = searchParams.get('t');

		$('.nav-tab-wrapper .nav-tab:nth-child('+t+')').click();

		$('html').animate({scrollTop: $("html").offset().top}, 100);

	});






	$('body').on('click','.wpd_tab_switch', function(){

		var this_button = $(this);
		var switch_tab = this_button.data('tab');
		var target_url = wcdp_obj.this_url+"&wcdp_tab="+switch_tab;
		window.open(target_url, '_blank');



	});

	$('.wpd_tab_switch').on('mouseover', function(){

		$(this).removeClass('ld');

	});


	setTimeout(function(){
		$('.wpd_tab_btn_group').removeClass('ld');
		$('.wpd_tab_btn_group .btn:first').addClass('ld');
	}, 3000)


});	 
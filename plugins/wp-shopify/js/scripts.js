// JavaScript Document
jQuery(document).ready(function($){
	
	$('.wp_shopify_product .prod-gallery ul li a').on('click', function(event){
		  event.preventDefault();
		  $('.wp_shopify_product .prod-left > a img').attr('src', $(this).find('img').attr('src'));
	});
	
	$('.wpsy_settings_div').on('click', 'h2.nav-tab-wrapper a.nav-tab', function(){
	
		var tab_data = $(this).data('tab');
	
		if($('.nav-tab-content.tab-'+tab_data).length>0){
			$(this).siblings().removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			$('.nav-tab-content, form:not(.wrap.wc_settings_div .nav-tab-content):not(.ignore)').hide();
			$('.nav-tab-content.tab-'+tab_data).removeClass('hides').show();
			
		}
	});			
		

});
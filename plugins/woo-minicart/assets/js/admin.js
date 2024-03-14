jQuery( 'input[name="minicart-icon"]' ).change(function(){
	var selected_icon = jQuery(this).attr('data-class');
	jQuery('.cart-active').removeClass('cart-active');
	jQuery('.' + selected_icon).addClass('cart-active');
});
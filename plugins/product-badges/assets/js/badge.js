jQuery(document).ready(function(){
	if ( jQuery('.single-product .woocommerce-product-gallery__wrapper .lion-badge').length > 0 ) {
		jQuery('.single-product .woocommerce-product-gallery__wrapper .lion-badge').detach().appendTo('.woocommerce-product-gallery');
	}
});
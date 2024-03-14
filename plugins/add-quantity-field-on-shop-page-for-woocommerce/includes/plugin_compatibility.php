<?php

// Astra compatibility
if ( wc_is_active_theme( 'astra' ) ) {
	add_action( 'init', function () {
		remove_action( 'woocommerce_after_shop_loop_item', array(
			Woo_Add_Quantity_Field_on_Shop_Page::instance(),
			'quantity_field'
		), 9 );

		add_action( 'astra_woo_shop_add_to_cart_before', array(
			Woo_Add_Quantity_Field_on_Shop_Page::instance(),
			'quantity_field'
		) );
	} );
}
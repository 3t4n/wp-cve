var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew product Add to cart
	bewwoocart();
} );

$j( document ).ajaxComplete( function() {
	"use strict";
	// Bew product Add to cart
	bewwoocart();
} );

// Make sure you run this code under Elementor..
$j( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew-woo-menu-cart-lite.default', function() {
		"use strict";
	// Bew product Add to cart
	bewwoocart();
	});
	} );
	

/* ==============================================
WOOCOMMERCE PRODUCT ADD TO CART
============================================== */	


function bewwoocart() {
	
	
	// product Add to cart pass php variables
		if( typeof passed_object != 'undefined' && passed_object) {		
		var icon_type = passed_object.icon_type;
		var type_classes = passed_object.type_classes;		
		}
		else {
		var qty = $j(".woo-cart-quantity").text();	
		var icon_type = $j('.woo-header-cart').data('icon');
		var type_classes = $j('.woo-header-cart').data('type');		
		}
				
		$j('div.woo-header-cart a' ).addClass(type_classes);
		$j('div.woo-header-cart a i' ).addClass(icon_type);
		$j('div.woo-header-cart a span' ).addClass(type_classes);
	
};
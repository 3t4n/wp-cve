jQuery( function( $ ) {
// For a widget without a skin (skin = default)
 elementorFrontend.hooks.addAction( 'frontend/element_ready/product-gallery.default', function( $scope ) {
		/*
		 * Initialize all galleries on page.
		 */
		$( '.woocommerce-product-gallery' ).each( function() {
			$( this ).wc_product_gallery();
		} );
} );

} );
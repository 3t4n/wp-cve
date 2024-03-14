jQuery( function( $ ) {

	$( 'button.add_attribute_group' ).on( 'click', function() {
		var size         = $( '.product_attributes .woocommerce_attribute' ).length;
		var group_term_id    = $( 'select.attribute_group' ).val();
		var $wrapper     = $( this ).closest( '#product_attributes' );
		var $attributes  = $wrapper.find( '.product_attributes' );
		var original_data = $( '.product_attributes' ).find( 'input, select, textarea' );
		var product_type = $( 'select#product-type' ).val();
		var data         = {
			action:   'wugrat_add_attribute_group',
			group_term_id: group_term_id,
			i:        size,
			data:     original_data.serialize(),
			security: woocommerce_admin_meta_boxes.add_attribute_nonce
		};

		$wrapper.block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		$.post( ajaxurl, data, function( response ) {
			$attributes.append( response );

			if ( 'variable' !== product_type ) {
				$attributes.find( '.enable_variation' ).hide();
			}

			$( document.body ).trigger( 'wc-enhanced-select-init' );

			$( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
				$( '.attribute_position', el ).val( parseInt( $( el ).index( '.product_attributes .woocommerce_attribute' ), 10 ) );
			});

			$attributes.find( '.woocommerce_attribute' ).last().find( 'h3' ).click();

			$wrapper.unblock();

			$( document.body ).trigger( 'woocommerce_added_attribute' );
		});

		$( 'select.attribute_group' ).val( '' );

		return false;
	});

});


;(function ( $, window, document, undefined ) {

	/*
	 * VariationForm class which handles variation forms and attributes.
	 */
	var VariationForm = function( $form ) {

		var self = this;

		self.$form    = $form;
		self.$product = $form.closest( '.product' );

		if ( self.productID = $form.attr( 'data-product_id' ) ) {

			if ( self.varsMetaData = window[ 'wcmd_vars_metadata_prod_id_' + self.productID ] ) {

				$form.on( 'reset_data', { variationForm: self }, self.onResetDisplayedVariation );

				$form.on( 'found_variation', { variationForm: self }, self.onFoundVariation );
			}
		}
	};

	VariationForm.prototype.onResetDisplayedVariation = function( event ) {

		var form          = event.data.variationForm;
		var cssItemPrefix = '.woocommerce-product-attributes-item';
		var cssItemValue  = cssItemPrefix + '__value .wcmd_vars_metadata_value';

		for ( var varID in form.varsMetaData ) {

			if ( varID && ! isNaN( varID ) ) {	// Just in case.

				for ( var metaDataKey in form.varsMetaData[ varID ] ) {

					var cssMetaData = cssItemPrefix + '--' + metaDataKey;

					form.$product.find( cssMetaData + ' ' + cssItemValue ).wcmd_reset_metadata();
				}
			}
		}
	}

	VariationForm.prototype.onFoundVariation = function( event, variation ) {

		var form          = event.data.variationForm;
		var varID         = variation.variation_id;
		var cssItemPrefix = '.woocommerce-product-attributes-item';
		var cssItemValue  = cssItemPrefix + '__value .wcmd_vars_metadata_value';

		/*
		 * Some variations may not have a metadata value, so reset the values to reload the parent metadata between
		 * variation to variation switches.
		 */
		form.onResetDisplayedVariation( event );

		if ( form.varsMetaData[ varID ] ) {

			for ( var metaDataKey in form.varsMetaData[ varID ] ) {

				var cssMetaData = cssItemPrefix + '--' + metaDataKey;
				var metaDataVal = form.varsMetaData[ varID ][ metaDataKey ];

				form.$product.find( cssMetaData + ' ' + cssItemValue ).wcmd_set_variation_metadata( metaDataVal );
			}
		}
	}

	$.fn.wcmd_reset_metadata = function() {

		if ( undefined !== this.attr( 'data-o_content' ) ) {

			this.html( this.attr( 'data-o_content' ) );
		}

		this.removeClass( 'is_variation_metadata' );
	};

	$.fn.wcmd_set_variation_metadata = function( content ) {

		if ( undefined === this.attr( 'data-o_content' ) ) {

			this.attr( 'data-o_content', this.html() );
		}

		this.html( content );

		this.addClass( 'is_variation_metadata' );
	};

	$.fn.wcmd_variation_form = function() {

		new VariationForm( this );

		return this;
	};

	$(function() {

		if ( 'undefined' !== typeof wc_add_to_cart_variation_params ) {

			$( '.variations_form' ).each( function() {

				$( this ).wcmd_variation_form();
			});
		}
	});

})( jQuery, window, document );

(function($) {
	$(document).ready( function() {
		$( '.psttcsv_select_all' ).on( 'change', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( 'fieldset' )
					.find( 'input[type="checkbox"]:visible:not(.psttcsv_select_all)' )
					.attr( 'checked', true )
					.trigger( 'change' );
			} else {
				$( this ).closest( 'fieldset' )
					.find( 'input[type="checkbox"]:visible:not(.psttcsv_select_all)' )
					.removeAttr( 'checked' )
					.trigger( 'change' );
			}
		} );

		$( '[name="psttcsv_post_type[]"], [name="psttcsv_fields[]"], [name="psttcsv_status[]"], [name="psttcsv_comment_fields[]"], [name="psttcsv_comment_fields[]"], [name="psttcsv_status_woocommerce[]"], [name="psttcsv_product_type_woocommerce[]"], [name="psttcsv_product_category_woocommerce[]"]' ).on( 'change', function() {
			var $element = $( this ),
				$parent = $element.closest( 'fieldset' ),
				$checkboxes = $parent.find( 'input[type="checkbox"]:not(.psttcsv_select_all)' );
				$enabled_checkboxes = $checkboxes.filter( ':checked' );
				if ( $checkboxes.length && $checkboxes.length == $enabled_checkboxes.length ) {
					$parent.find( '.psttcsv_select_all' ).attr( 'checked', true );
				} else {
					$parent.find( '.psttcsv_select_all' ).removeAttr( 'checked' );
				}
		} ).trigger( 'change' );

		$( '.psttcsv_checkbox_select' ).on( 'change', function() {
			var $checkbox = $( this ),
				$parent_elem = $checkbox.closest( 'fieldset' ),
				$all_checkboxes = $parent_elem.find( 'input[type="checkbox"]:not(.psttcsv_select_all)' ),
				$visible_checkboxes;

			if(  $( '#psttcsv-show-hidden-meta' ).is( ':checked' )  ) {
				$visible_checkboxes = $all_checkboxes;
			} else {
				$visible_checkboxes  = $all_checkboxes.filter( function() {
					if( $( this ).closest( 'div' ).hasClass( 'psttcsv-hidden-option' ) ) {
						return 0;
					} else {
						return 1;
					}
				});
			}

			var $enabled_checkboxes = $visible_checkboxes.filter( ':checked' );
			if ( $visible_checkboxes && $enabled_checkboxes.length == $visible_checkboxes.length ) {
				$parent_elem.find( '.psttcsv_select_all' ).attr( 'checked', true );
			} else {
				$parent_elem.find( '.psttcsv_select_all' ).removeAttr( 'checked' );
			}
		} ).trigger( 'change' );

		$( '#psttcsv-show-hidden-meta' ).on( 'change', function () {
			if ( $( this ).is( ':checked' ) ) {
				$( '.psttcsv-hidden-option' ).show();
			} else {
				$( '.psttcsv-hidden-option' ).hide();
			}
			$( '.psttcsv_checkbox_select' ).trigger( 'change' );
		} ).trigger( 'change' );

		/* Custom Fields tab accordion */
		$( function() {
			$( "#psttcsv-accordion" ).accordion( {
				heightStyle: "content",
				active: false,
				collapsible: true
			} );
		} );

		/* Custom Fields tab accordion */
		$( function() {
			$( "#psttcsv-accordion-woocommerce" ).accordion( {
				heightStyle: "content",
				active: false,
				collapsible: true
			} );
		} );


		/* Custom Fields tab accordion */
		$( function() {
			$( "#psttcsv-accordion-taxonomies" ).accordion( {
				heightStyle: "content",
				active: false,
				collapsible: true
			} );
		} );

		$( 'input[name="psttcsv_export_type"]' ).on( 'change', function () {
			if ( $( this ).is( ':checked' ) ) {
				switch ( $( this ).val() ) {
					case 'post_type' :
						$( '#psttcsv-taxonomies-block' ).hide();
						$( '#psttcsv-posttype-block' ).show();
						break;
					case 'taxonomy' :
						$( '#psttcsv-posttype-block' ).hide();
						$( '#psttcsv-taxonomies-block' ).show();
						break;
				}
			}
		} ).trigger( 'change' );

		$( '.bws_form input, .bws_form textarea, .bws_form select' ).bind( "change paste select", function() {
			if ( $( this ).attr( 'type' ) != 'submit' ) {
				$ ( '.psttcsv_export_notice' ).show();
			}
		});
		$( 'form.bws_form' ).on( 'submit', function() {
			$ ( '.psttcsv_error' ).hide();
		});
	} );
} )(jQuery);
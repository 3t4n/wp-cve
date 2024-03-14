jQuery( document ).ready( function( $ ) {

	var $api_key = $( '[name="fca_eoi[getresponse_api_key]"]' );
	var $lists = $( '[name="fca_eoi[getresponse_list_id]"]' );
	var $lists_wrapper = $( '#getresponse_list_id_wrapper' );
	var $double_opt_in_wrapper = $( '#getresponse_double_opt_in_wrapper' );
            
	fca_eoi_provider_status_setup( 'getresponse', $api_key );

	$api_key.bind( 'input', function() {
		if ( ! fca_eoi_provider_is_value_changed( $( this ) ) ) {
			return;
		}

		fca_eoi_provider_status_set( 'getresponse', fca_eoi_provider_status_codes.loading );

		var data = {
			'action': 'fca_eoi_getresponse_get_lists', /* API action name, do not change */
			'getresponse_api_key' : $api_key.val().trim()
		};

		$.post( ajaxurl, data, function( response ) {

			var lists = JSON.parse( response );

			fca_eoi_provider_status_set( 'getresponse', Object.keys(lists).length > 1
				? fca_eoi_provider_status_codes.ok
				: fca_eoi_provider_status_codes.error );

			var $lists = $( '<select class="select2" style="width: 27em;" name="fca_eoi[getresponse_list_id]" >' );
			
			for ( list_id in lists ) {
				$lists.append( '<option value="' + list_id + '">' + lists[ list_id ] + '</option>' );
			}

			// Set first list as selected
			$( 'option:eq(1)', $lists ).prop( 'selected', true );

			// Replace dropdown with new list of lists, apply Select2 then show
			$( '[name="fca_eoi[getresponse_list_id]"]' ).select2( 'destroy' );
			$( '[name="fca_eoi[getresponse_list_id]"]' ).replaceWith( $lists );
			$( '[name="fca_eoi[getresponse_list_id]"]' ).select2();
			getresponse_toggle_fields();
		} );
	})

	/**
	 * Show/hide some fields if there are/aren't list options
	 *
	 * Don't forget that there is always the option "Not Set", 
	 * so take it into consideration when cheking the number of options
	 */
	function getresponse_toggle_fields() {

		var options = $( 'option', '[name="fca_eoi[getresponse_list_id]"]' );

		if( options.length > 1 ) {
			$()
				.add( $double_opt_in_wrapper )
				.add( $lists_wrapper )
				.show( 'fast' )
			;
		} else {
			$()
				.add( $double_opt_in_wrapper )
				.add( $lists_wrapper )
				.hide( )
			;
		}
	}
	getresponse_toggle_fields();

   // toggle getresponse double opt in fearure once  send_opt_int_bait is selected
	$( 'input[name="fca_eoi[is_send_opt_int_bait]"]' ).on('click', function(e) {
		if( $( 'input[name="fca_eoi[is_send_opt_int_bait]"]' ).is(":checked") ) {
			$( 'select[name="fca_eoi[getresponse_double_opt_in]"]' ).select2('val', 'false');
		} else {
			$(  'select[name="fca_eoi[getresponse_double_opt_in]"]' ).select2('val', 'true');
		}
	}); 
});

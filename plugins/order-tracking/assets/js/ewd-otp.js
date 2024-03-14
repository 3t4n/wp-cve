jQuery( document ).ready( function() {

	jQuery( '.ewd-otp-tracking-form' ).on( 'submit', function( event ) {

		if ( jQuery( this ).parent().parent().hasClass( 'ewd-otp-disable-ajax' ) ) { return; }

		event.preventDefault();

		var order_number = jQuery( 'input[name="ewd_otp_identifier_number"]' ).val();
		var order_email = jQuery( 'input[name="ewd_otp_form_email"]' ).val();
		
		jQuery( '.ewd-otp-tracking-results' ).html( '<h3>' + ewd_otp_php_data.retrieving_results + '</h3>' );

		var params = {
			order_number: order_number,
			order_email: order_email,
			customer_notes_label: ewd_otp_php_data.customer_notes_submit,
			action: 'ewd_otp_get_order',
			nonce: ewd_otp_php_data.nonce
		};

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function( response ) {

			jQuery( '.ewd-otp-tracking-results' ).html( response.data.output );

			ewd_otp_resizeimage();
			ewd_otp_enable_note_click();
		});
	});

	jQuery( '.ewd-otp-customer-form' ).on( 'submit', function( event ) {

		if ( jQuery( this ).parent().parent().hasClass( 'ewd-otp-disable-ajax' ) ) { return; }

		event.preventDefault();

		var customer_number = jQuery( 'input[name="ewd_otp_identifier_number"]' ).val();
		var customer_email = jQuery( 'input[name="ewd_otp_form_email"]' ).val();
		
		jQuery( '.ewd-otp-tracking-results' ).html( '<h3>' + ewd_otp_php_data.retrieving_results + '</h3>' );

		var params = {
			customer_number: customer_number,
			customer_email: customer_email,
			action: 'ewd_otp_get_customer_orders',
			nonce: ewd_otp_php_data.nonce
		};

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function( response ) { console.log( response );

			jQuery( '.ewd-otp-tracking-results' ).html( response.data.output );

			ewd_otp_enable_individual_results_click();
		});
	});

	jQuery( '.ewd-otp-sales-rep-form' ).on( 'submit', function( event ) {

		if ( jQuery( this ).parent().parent().hasClass( 'ewd-otp-disable-ajax' ) ) { return; }

		event.preventDefault();

		var sales_rep_number = jQuery( 'input[name="ewd_otp_identifier_number"]' ).val();
		var sales_rep_email = jQuery( 'input[name="ewd_otp_form_email"]' ).val();
		
		jQuery( '.ewd-otp-tracking-results' ).html( '<h3>' + ewd_otp_php_data.retrieving_results + '</h3>' );

		var params = {
			sales_rep_number: sales_rep_number,
			sales_rep_email: sales_rep_email,
			action: 'ewd_otp_get_sales_rep_orders',
			nonce: ewd_otp_php_data.nonce
		};

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function( response ) { console.log( response );

			jQuery( '.ewd-otp-tracking-results' ).html( response.data.output );

			ewd_otp_enable_individual_results_click();
		});
	});

	jQuery(document).on('click', '.ewd-otp-print-results', function() {
		var css_url = jQuery(this).data('cssurl');
		jQuery('head').append('<link rel="stylesheet" href="' + css_url + '" type="text/css" />');


		setTimeout(function() {window.print(); jQuery("LINK[href='" + css_url + "']").remove();}, 500);
	});

    /*var minDate = jQuery('.ewd-otp-customer-order-datepicker').attr('min');
    var maxDate = jQuery('.ewd-otp-customer-order-datepicker').attr('max');
    jQuery('.ewd-otp-customer-order-datepicker').datepicker({
        dateFormat : "yy-mm-dd",
        minDate: minDate,
        maxDate: maxDate
    });*/

    jQuery( '#customer_order input[type="submit"]' ).on( 'click', function() {

    	jQuery( '#customer_order input[type="checkbox"]' ).each( function() {

    		if ( ! jQuery( this ).prop( 'required' ) ) { return; }

    		var checkbox_group = jQuery( '#customer_order input:checkbox[name="' + jQuery( this ).attr( 'name' ) + '"]' );
    		if ( checkbox_group.is( ':checked' ) ) { checkbox_group.prop( 'required', false ); }
    	})
    });

    ewd_otp_enable_note_click();

    ewd_otp_enable_individual_results_click();
});

function ewd_otp_enable_individual_results_click() {

	jQuery( '.ewd-otp-tracking-table-order' ).each( function() {

		if ( jQuery( this ).parent().parent().hasClass( 'ewd-otp-disable-ajax' ) ) { return; }

		jQuery( this ).css( 'cursor', 'pointer' );
		jQuery( this ).addClass( 'ewd-otp-order-table-clickable-row' );
	});

	jQuery( '.ewd-otp-tracking-table-order' ).on( 'click', function() {

		if ( jQuery( this ).parent().parent().hasClass( 'ewd-otp-disable-ajax' ) ) { return; }

		event.preventDefault();

		var order_number = jQuery( this ).data( 'order_number' );
		var order_email = jQuery( this ).data( 'order_email' );
		
		jQuery( '.ewd-otp-tracking-results' ).html( '<h3>' + ewd_otp_php_data.retrieving_results + '</h3>' );

		var params = {
			order_number: order_number,
			order_email: order_email,
			customer_notes_label: ewd_otp_php_data.customer_notes_submit,
			action: 'ewd_otp_get_order',
			nonce: ewd_otp_php_data.nonce
		};

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function( response ) {

			jQuery( '.ewd-otp-tracking-results' ).html( response.data.output );

			ewd_otp_resizeimage();
			ewd_otp_enable_note_click();
		});

	});
}

function ewd_otp_enable_note_click() {

	jQuery( '#ewd-otp-customer-notes-form' ).on( 'submit', function( event ) {

		jQuery( 'input[name="ewd_otp_customer_notes_submit"]' ).prop( 'disabled', true );

		event.preventDefault();
		
		var order_number = jQuery( 'input[name="ewd_otp_order_number"]' ).val();
		var order_id = jQuery( 'input[name="ewd_otp_order_id"]' ).val();
		var customer_notes = jQuery('textarea[name="ewd_otp_customer_notes"]').val();

		var params = {
			order_number: order_number,
			order_id: order_id,
			customer_notes: customer_notes,
			action: 'ewd_otp_update_customer_note',
			nonce: ewd_otp_php_data.nonce
		};

		var data = jQuery.param( params );
		jQuery.post(ajaxurl, data, function(response) {

			jQuery( '<div class="ewd-otp-customer-note-response">' + response.data.output + '</div>' ).prependTo( '#ewd-otp-customer-notes' ).delay( 3000 ).fadeOut( 400 );
	
			jQuery( '.ewd-otp-customer-notes-value' ).html( customer_notes );

			jQuery( 'input[name="ewd_otp_customer_notes_submit"]' ).prop( 'disabled', false );
		});

		return false;
	});
}

function ewd_otp_resizeimage() {

	var graphic_div = jQuery( '.ewd-otp-tracking-graphic' );

	if ( graphic_div.hasClass( 'ewd-otp-default' ) || graphic_div.hasClass( 'ewd-otp-streamlined' ) || graphic_div.hasClass( 'ewd-otp-sleek' ) ) {

		var img_empty = jQuery( '.ewd-otp-empty-display > img' );
		var img_full = jQuery( '.ewd-otp-full-display > img' );

		img_full.width( img_empty.width() );

		if ( jQuery( window ).width() > 600 ) { var div_height = Math.max( img_empty.height(), 150 ); }

		jQuery( '.ewd-otp-tracking-graphic' ).height( div_height );
	}
} 
jQuery( window ).resize( ewd_otp_resizeimage );
jQuery( document ).ready( ewd_otp_resizeimage );
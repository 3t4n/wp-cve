( function($) {
	"use strict";

	function cf7pe_sandbox_validate() {
		if ( jQuery( '.cf7pe-settings #cf7pe_use_paypal' ).prop( 'checked' ) == true && jQuery( '.cf7pe-settings #cf7pe_mode_sandbox' ).prop( 'checked' ) != true ) {
			jQuery( '.cf7pe-settings #cf7pe_live_client_id, .cf7pe-settings #cf7pe_live_client_secret' ).prop( 'required', true );
		} else {
			jQuery( '.cf7pe-settings #cf7pe_live_client_id, .cf7pe-settings #cf7pe_live_client_secret' ).removeAttr( 'required' );
		}
	}

	function cf7pe_live_validate() {
		if ( jQuery( '.cf7pe-settings #cf7pe_use_paypal' ).prop( 'checked' ) == true && jQuery( '.cf7pe-settings #cf7pe_mode_sandbox' ).prop( 'checked' ) == true ) {
			jQuery( '.cf7pe-settings #cf7pe_sandbox_client_id, .cf7pe-settings #cf7pe_sandbox_client_secret' ).prop( 'required', true );
		} else {
			jQuery( '.cf7pe-settings #cf7pe_sandbox_client_id, .cf7pe-settings #cf7pe_sandbox_client_secret' ).removeAttr( 'required' );
		}
	}

	jQuery( document ).on( 'change', '.cf7pe-settings .enable_required', function() {
		if ( jQuery( this ).prop( 'checked' ) == true ) {
			jQuery( '.cf7pe-settings #cf7pe_amount' ).prop( 'required', true );
			
		} else {
			jQuery( '.cf7pe-settings #cf7pe_amount' ).removeAttr( 'required' );
		}

		cf7pe_live_validate();
		cf7pe_sandbox_validate();

	} );

	jQuery( document ).on( 'change', '.cf7pe-settings #cf7pe_mode_sandbox', function() {
		cf7pe_live_validate();
		cf7pe_sandbox_validate();
	} );



	jQuery( document ).on( 'input', '.cf7pe-settings .required', function() {
		cf7pe_live_validate();
		cf7pe_sandbox_validate();
	} );


	function check_paypal_field_validation(){		

		jQuery( 'body .wp-pointer-buttons .close' ).trigger( 'click' );

		if ( jQuery( '.cf7pe-settings #cf7pe_mode_sandbox' ).prop( 'checked' ) == true ) {

			if(
				jQuery( '.cf7pe-settings #cf7pe_sandbox_client_id' ).val() == '' ||
				jQuery( '.cf7pe-settings #cf7pe_sandbox_client_secret').val() == ''
			){
				jQuery("#paypal-extension-tab .ui-tabs-anchor").find('span').remove();
				jQuery("#paypal-extension-tab .ui-tabs-anchor").append('<span class="icon-in-circle" aria-hidden="true">!</span>');
			}else{
				jQuery("#paypal-extension-tab .ui-tabs-anchor").find('span').remove();
			}
		}

		if ( jQuery( '.cf7pe-settings #cf7pe_mode_sandbox' ).prop( 'checked' ) != true ) {
			if(
				jQuery('.cf7pe-settings #cf7pe_live_client_id' ).val() == '' ||
				jQuery('.cf7pe-settings #cf7pe_live_client_secret').val() == ''
			){
				jQuery("#paypal-extension-tab .ui-tabs-anchor").find('span').remove();
				jQuery("#paypal-extension-tab .ui-tabs-anchor").append('<span class="icon-in-circle" aria-hidden="true">!</span>');
			}else{
				jQuery("#paypal-extension-tab .ui-tabs-anchor").find('span').remove();
			}
		}


		if( jQuery( '.cf7pe-settings #cf7pe_use_paypal' ).prop( 'checked' ) == true ){
				
			jQuery('.cf7pe-settings .form-required-fields').each(function() {
				if (jQuery.trim(jQuery(this).val()) == '') {
				  jQuery("#paypal-extension-tab .ui-tabs-anchor").find('span').remove();
				  jQuery("#paypal-extension-tab .ui-tabs-anchor").append('<span class="icon-in-circle" aria-hidden="true">!</span>');
			   }
			});
		   
	   }else{
		   jQuery("#paypal-extension-tab .ui-tabs-anchor").find('span').remove();
	   }
				
	}

	jQuery( document ).ready( function() { check_paypal_field_validation() });
	jQuery( document ).on('click',".ui-state-default",function() { check_paypal_field_validation() });

} )( jQuery );
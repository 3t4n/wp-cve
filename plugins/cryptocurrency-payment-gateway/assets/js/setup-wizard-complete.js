/** Javascript to run on the Settings page after the Setup Wizard has completed. */

jQuery( document ).ready( function ($) {

	/**
	 * When the Setup Wizard has completed, enable the payment gateway but do not save the setting.
	 */
	document.querySelector( "#cryptowoo_payments-enabled .cb-enable" ).classList.add( 'selected' );
	document.querySelector( "#cryptowoo_payments-enabled .cb-disable" ).classList.remove( 'selected' );
	document.querySelector( "#cryptowoo_payments-enabled #enabled" ).value = '1';

	// Also change the submit button text from "Save Changes" to "Enable Now".
	document.getElementById( "redux_top_save" ).value    = 'Save Settings';
	document.getElementById( "redux_bottom_save" ).value = 'Save Settings';
});
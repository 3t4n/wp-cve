/*! Terms & Conditions Per Product
 */

/**
 * @summary     Terms & Conditions Per Product
 * @description This plugin allows you to set custom Terms and Conditions per WooCommerce product.
 * @version     1.0.0
 * @file        terms-per-product
 * @author      Giannis Kipouros
 * @contact     https://tacpp-pro.com
 *
 */


(function( $ ) {
	'use strict';

	// On load
	$(document).ready(function($) {

		/**
		 * Enable checkboxes only after opening the terms link
		 */
		if ( $('.extra-terms .must-open-url').length > 0 ) {
			$(document).on('click touched', '.extra-terms .must-open-url .terms-url', function (){
				$(this).closest('label').find('.input-checkbox:disabled').prop('disabled', false);
				console.log($(this));
			});
		}
    }); // End document ready
})( jQuery );

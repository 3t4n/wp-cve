(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 
	$(function() {
		
		if ($('#ratcw_remove_cart_button_by_user_role').length > 0) {
			$('#ratcw_remove_cart_button_by_user_role').select2({
				placeholder: "Select user roles",
				multiple: true,
				width: '50%',
			});
			$('#ratcw_remove_cart_button_by_user_role option:eq(0)').prop('selected',false);
		}
		
		
		if ($('#ratcw_remove_cart_button_by_country').length > 0) {
			$('#ratcw_remove_cart_button_by_country').select2({
				placeholder: "Select countries",
				multiple: true,
				width: '50%',
			});
			$('#ratcw_remove_cart_button_by_country option:eq(0)').prop('selected',false);
		}
		
		if ($('p.ratcw_remove_cart_button_for_field').length > 0) {
			
			var ratcw_remove_cart_button_for = $("#ratcw_remove_cart_button_for").val();
			if ( ratcw_remove_cart_button_for == 'hide-by-user-rule' ) {
				$('p.ratcw_remove_cart_button_by_country_field').css('display', 'none');
				$('p.ratcw_remove_cart_button_by_user_role_field').css('display', 'block');
			} else if ( ratcw_remove_cart_button_for == 'hide-by-country' ) {
				$('p.ratcw_remove_cart_button_by_country_field').css('display', 'block');
				$('p.ratcw_remove_cart_button_by_user_role_field').css('display', 'none');
			} else {
				$('p.ratcw_remove_cart_button_by_country_field').css('display', 'none');
				$('p.ratcw_remove_cart_button_by_user_role_field').css('display', 'none');
			}
			
			if ( ratcw_remove_cart_button_for == 'hide-only-for-visitor' ) {
				$('p.ratcw_show_login_btn_when_cart_button_hidden_field').css('display', 'block');
			}
			
			
		}
		
		$('select#ratcw_remove_cart_button_for').on('change', function() {

			if ( this.value == 'hide-by-user-rule' ) {
				$('p.ratcw_remove_cart_button_by_country_field').css('display', 'none');
				$('p.ratcw_remove_cart_button_by_user_role_field').css('display', 'block');
			} else if ( this.value == 'hide-by-country' ) {
				$('p.ratcw_remove_cart_button_by_country_field').css('display', 'block');
				$('p.ratcw_remove_cart_button_by_user_role_field').css('display', 'none');
			} else {
				$('p.ratcw_remove_cart_button_by_country_field').css('display', 'none');
				$('p.ratcw_remove_cart_button_by_user_role_field').css('display', 'none');
			}
			
			if ( this.value == 'hide-only-for-visitor' ) {
				$('p.ratcw_show_login_btn_when_cart_button_hidden_field').css('display', 'block');
			} else {
				$('p.ratcw_show_login_btn_when_cart_button_hidden_field').css('display', 'none');
			}
			
		});

	});

})( jQuery );

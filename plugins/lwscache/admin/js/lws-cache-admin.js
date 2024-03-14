/**
 * File to add JavaScript for LWSCache.
 *
 * @package LWSCache
 */

function callPopup(type, content) {
	let modal = document.getElementById('modal_popup');
	let text_content = document.getElementById('container_content');
	if (modal === null ||  text_content === null) {
		console.log(JSON.stringify({'code':"POPUP_FAIL", 'data':"Failed to find modal or its elements"}));
		return 1;
	}

	text_content.innerHTML = content;
	modal.setAttribute('data-result', type);
	jQuery('#modal_popup').modal('toggle');

}

function closemodal() {
	let modal = document.getElementById('modal_popup');
	if (modal === null) {
		console.log(JSON.stringify({'code':"POPUP_FAIL", 'data':"Failed to find modal"}));
		return 1;
	}
	jQuery('#modal_popup').modal('hide');
}



(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */
	$(
		function () {

			var news_section = jQuery( '#latest_news' );

			if ( news_section.length > 0 ) {

				var args = {
					'action': 'rt_get_feeds'
				};

				jQuery.get(
					ajaxurl,
					args,
					function( data ) {
						/**
						 * Received markup is safe and escaped appropriately.
						 *
						 * File: admin/class-lws-cache-admin.php
						 * Method: lws_cache_get_feeds();
						 */

						// phpcs:ignore -- WordPressVIPMinimum.JS.HTMLExecutingFunctions.append
						news_section.find( '.inside' ).empty().append( data );
					}
				);

			}

			jQuery( "form#purgeall a" ).click(
				function (e) {

					if ( confirm( lws_cache.purge_confirm_string ) === true ) {
						// Continue submitting form.
					} else {
						e.preventDefault();
					}

				}
			);

			/**
			 * Show OR Hide options on option checkbox
			 *
			 * @param {type} selector Selector of Checkbox and PostBox
			 */
			function nginx_show_option( selector ) {

				jQuery( '#' + selector ).on(
					'change',
					function () {

						if ( jQuery( this ).is( ':checked' ) ) {

							jQuery( '.' + selector ).show();

							if ( 'cache_method_redis' === selector ) {
								jQuery( '.cache_method_fastcgi' ).hide();
							} else if ( selector === 'cache_method_fastcgi' ) {
								jQuery( '.cache_method_redis' ).hide();
							}

						} else {
							jQuery( '.' + selector ).hide();
						}

					}
				);

			}

			/* Function call with parameter */
			nginx_show_option( 'cache_method_fastcgi' );
			nginx_show_option( 'cache_method_redis' );
			nginx_show_option( 'enable_map' );
			nginx_show_option( 'enable_log' );
			nginx_show_option( 'enable_purge' );

		}
	);
})( jQuery );

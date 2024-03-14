/**
 * alg-wpfh-theme-manage-key-links.js
 *
 * @version 1.3.0
 * @since   1.1.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function() {

	/**
	 * inArray.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function inArray( needle, haystack ) {
		var length = haystack.length;
		for ( var i = 0; i < length; i++ ) {
			if ( haystack[ i ] == needle ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Main function.
	 *
	 * @version 1.3.0
	 * @since   1.1.0
	 */
	jQuery( 'div.theme-actions' ).each( function() {
		var theme_slug = jQuery( this ).parents( 'div.theme' ).attr( 'data-slug' );
		if ( inArray( theme_slug, alg_wpfh_object.themes_to_update ) ) {
			jQuery( this ).append( '<a' +
					' title="' + alg_wpfh_object.status_messages[ theme_slug ] + '"' +
					' class="button alg_manage_key_theme"' +
					' href="' + alg_wpfh_object.admin_url + 'options-general.php?page=wpcodefactory-helper&item_type=theme&item_slug=' + theme_slug + '">' +
				alg_wpfh_object.manage_key_text + '</a>' );
		}
	} );

} );

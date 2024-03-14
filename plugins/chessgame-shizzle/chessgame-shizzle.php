<?php
/*
Plugin Name: Chessgame Shizzle
Plugin URI: https://wordpress.org/plugins/chessgame-shizzle/
Description: Chessgame Shizzle is a nice way to integrate chessgames into your WordPress website. Ideal for chess clubs, your chess blog, or any chess related website.
Version: 1.2.8
Author: Marcel Pol
Author URI: https://timelord.nl
License: GPLv2 or later
Text Domain: chessgame-shizzle
Domain Path: /lang/


Copyright 2017 - 2023  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version
define('C_SHIZZLE_VER', '1.2.8');


/*
 * TODO:
 * - Frontend Widgets:
 *   - form/button: upload chessgame (taking you to page with shortcode) ("Send in chessgame").
 * - Shortcodes:
 *   - chessgame archive list (cs_list).
 * - Single view:
 *   - Consider adding Arrow plugin for pgn4web.
 * - Frontend Form:
 *   - Think of way to generate the ECO code from a PGN textarea.
 *   - Use pgnParser in JS to fill in fields from the PGN automatically.
 * - Admin:
 *   - Settings page:
 *     - Setting for order of content/meta.
 *     - Settings for pgn4web.js, which parts to show, and the several JS options.
 * - Analyzer:
 *   - Add analyzer with Stockfish.js.
 *   - Upload from that analyzer to the upload form with $_POST as transport.
 *   - Support link from chessgame to view it in analyzer.
 * - Add tag taxonomy for ECO codes. Or use a page with wp_query for meta. Or shortcode [opening_explorer]. Model after A-Z listings.
 * - "Add tag from players name". Same for ECO code.
 * - use details element:
 *   https://developer.mozilla.org/en-US/docs/Web/HTML/Element/details
 * - Check lazy loading for iframes in WP 5.7
 * - Consider board with notation.
 * - Lessons:
 *   - Store played games on post_id in user profile or/and in cookie for non-loggedin user.
 *   - Option to clear that history from the user profile page.
 *   - Check pgn4web for trying out moves in puzzle, they are either good and the move is played, or incorrect with a message (callback function), preferable per move, not all at once.
 *   - Consider text (content and pgn) next to chessboard instead of below. Not sure if this is feasible next to iframe with another AJAX call.
 * - In upload form, have a regex button depending on language that translates the move notation.
 * - Export:
 *   - Options for terms.
 *   - Maybe meta field as well.
 *   - Find a way to make sure that all data is from the latest content in WP post and meta, not from the older full pgn.
 *
 */


/*
 * Definitions
 */
define('C_SHIZZLE_FOLDER', plugin_basename(dirname( __FILE__ )));
define('C_SHIZZLE_URL', WP_PLUGIN_URL . '/' . C_SHIZZLE_FOLDER);
define('C_SHIZZLE_DIR', WP_PLUGIN_DIR . '/' . C_SHIZZLE_FOLDER);


// Functions for the frontend
require_once C_SHIZZLE_DIR . '/frontend/cs-ajax-mfen.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-hooks.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-rss.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-chessgame.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-form.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-form-post.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-lessons.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-list.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-simple-list.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-simple-list-pagination.php';
require_once C_SHIZZLE_DIR . '/frontend/cs-shortcode-simple-list-search.php';

// Frontend Widgets
require_once C_SHIZZLE_DIR . '/frontend/widgets/cs-widget-featured-chessgame.php';
require_once C_SHIZZLE_DIR . '/frontend/widgets/cs-widget-newest-chessgame.php';
require_once C_SHIZZLE_DIR . '/frontend/widgets/cs-widget-recent-chessgames.php';
require_once C_SHIZZLE_DIR . '/frontend/widgets/cs-widget-search.php';

// Functions and pages for the backend
if ( is_admin() ) {
	require_once C_SHIZZLE_DIR . '/admin/cs-ajax-mfen.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-hooks.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-list-table-featured-image.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-menu-counter.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-meta-box-preview.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-meta-box.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-page-about.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-page-import.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-page-export.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-page-lessons.php';
	require_once C_SHIZZLE_DIR . '/admin/cs-page-settings.php';

	// Tabs for Settings Page
	require_once C_SHIZZLE_DIR . '/admin/tabs/cs-themes.php';
	require_once C_SHIZZLE_DIR . '/admin/tabs/cs-antispam.php';
	require_once C_SHIZZLE_DIR . '/admin/tabs/cs-email.php';
	require_once C_SHIZZLE_DIR . '/admin/tabs/cs-misc.php';
}

// General Functions
require_once C_SHIZZLE_DIR . '/chessgame-shizzle-hooks.php';
require_once C_SHIZZLE_DIR . '/functions/cs-ajax-lesson.php';
require_once C_SHIZZLE_DIR . '/functions/cs-cache.php';
require_once C_SHIZZLE_DIR . '/functions/cs-content-filters.php';
require_once C_SHIZZLE_DIR . '/functions/cs-dropdown-openingcodes.php';
require_once C_SHIZZLE_DIR . '/functions/cs-formatting.php';
require_once C_SHIZZLE_DIR . '/functions/cs-help-text.php';
require_once C_SHIZZLE_DIR . '/functions/cs-iframe.php';
require_once C_SHIZZLE_DIR . '/functions/cs-lessons.php';
require_once C_SHIZZLE_DIR . '/functions/cs-mail.php';
require_once C_SHIZZLE_DIR . '/functions/cs-messages.php';
require_once C_SHIZZLE_DIR . '/functions/cs-pgn.php';
require_once C_SHIZZLE_DIR . '/functions/cs-post-meta.php';
require_once C_SHIZZLE_DIR . '/functions/cs-post-types.php';
require_once C_SHIZZLE_DIR . '/functions/cs-privacy.php';
require_once C_SHIZZLE_DIR . '/functions/cs-settings.php';
require_once C_SHIZZLE_DIR . '/functions/cs-themes.php';
require_once C_SHIZZLE_DIR . '/functions/cs-user.php';

// General Classes
require_once C_SHIZZLE_DIR . '/functions/cs-class-mfen.php';

// Thirdparty
require_once C_SHIZZLE_DIR . '/thirdparty/chessParser/cs-chessparser-include.php';
require_once C_SHIZZLE_DIR . '/thirdparty/pgn4web/cs-pgn4web.php';


/*
 * Trigger an install/upgrade function when the plugin is activated.
 *
 * @since 1.0.8
 */
function chessgame_shizzle_activation( $networkwide ) {
	global $wpdb;

	$current_version = get_option( 'chessgame_shizzle-version', false );

	if ( is_multisite() ) {
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blogids as $blog_id) {
			switch_to_blog($blog_id);
			if ( $current_version === false ) {
				chessgame_shizzle_set_defaults();
			} else if ($current_version !== C_SHIZZLE_VER) {
				chessgame_shizzle_set_defaults();
			}
			restore_current_blog();
		}
	} else {
		if ( $current_version === false ) {
			chessgame_shizzle_set_defaults();
		} else if ($current_version !== C_SHIZZLE_VER) {
			chessgame_shizzle_set_defaults();
		}
	}
}
register_activation_hook( __FILE__, 'chessgame_shizzle_activation' );

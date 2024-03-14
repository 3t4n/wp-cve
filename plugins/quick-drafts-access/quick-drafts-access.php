<?php
/**
 * Plugin Name: Quick Drafts Access
 * Version:     2.3.1
 * Plugin URI:  https://coffee2code.com/wp-plugins/quick-drafts-access/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: quick-drafts-access
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Adds a link to Drafts under the Posts, Pages, and other custom post type sections in the admin menu.
 *
 * Compatible with WordPress 4.6 through 6.3+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/quick-drafts-access/
 *
 * @package Quick_Drafts_Access
 * @author  Scott Reilly
 * @version 2.3.1
 */

/*
	Copyright (c) 2010-2023 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_QuickDraftsAccess' ) ) :

class c2c_QuickDraftsAccess {

	/**
	 * Returns version of the plugin.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public static function version() {
		return '2.3.1';
	}

	/**
	 * Initializes the plugins.
	 */
	public static function init() {

		// Hook the admin init to load textdomain.
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		// Hook the admin menu to add links to drafts.
		add_action( 'admin_menu', array( __CLASS__, 'quick_drafts_access' ) );

		// Hook the post table actions to add dropdown to filter for draft author.
		add_action( 'restrict_manage_posts', array( __CLASS__, 'filter_drafts_by_author' ), 10, 2 );

	}

	/**
	 * Adds hooks associated with the admin_init action.
	 *
	 * @since 2.0.1
	 */
	public static function admin_init() {
		// Load textdomain.
		load_plugin_textdomain( 'quick-drafts-access' );
	}

	/**
	 * Returns the supported post types.
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */
	public static function get_post_types() {
		// Get a list of all post types with a UI.
		$post_types = (array) get_post_types( array( 'public' => true ), 'object' );
		unset( $post_types[ 'attachment' ] );

		/**
		 * Customizes the list of post_types for which the draft links will be shown.
		 *
		 * @param array $post_types The post types.
		 */
		return (array) apply_filters( 'c2c_quick_drafts_access_post_types', $post_types );
	}

	/**
	 * Adds the drafts link(s) to the admin menu.
	 */
	public static function quick_drafts_access() {

		$post_types = self::get_post_types();

		// Memoized post status object.
		$post_status = null;

		// Iterate through all post types.
		foreach ( $post_types as $post_type ) {

			// If a post type doesn't look like a post type object, throw notice and skip it.
			if ( ! is_object( $post_type ) || ! property_exists( $post_type, 'name' ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					__( 'The "c2c_quick_drafts_access_post_types" filter should be passed an array of post type objects.', 'quick-drafts-access' ),
					'2.0'
				);
				continue;
			}

			// Post type name.
			$name = $post_type->name;

			// Path base.
			$path = 'edit.php';

			// Specify post type if not 'post'.
			if ( 'post' != $name ) {
				$path .= '?post_type=' . $name;
			}

			// Array for query vars.
			$query_vars = array(
				'post_status' => 'draft',
			);

			// Get post status object if it hasn't been gotten already.
			if ( ! $post_status ) {
				$post_status = get_post_status_object( 'draft' );
			}

			// Permit override of default view state for draft links.

			/**
			 * Customizes whether the 'All Drafts' link will appear at all for a post
			 * type. If true, then the 'c2c_quick_drafts_access_show_if_empty' filter
			 * would ultimately determine if the link should appear based on the
			 * presence of actual drafts.
			 *
			 * @param bool   $allow     Show the "All Drafts" link in admin menu? Default true.
			 * @param object $post_type The post type object.
			 */
			$show_all_drafts = (bool) apply_filters( 'c2c_quick_drafts_access_show_all_drafts_menu_link', true, $post_type );

			/**
			 * Customizes whether the 'My Drafts' link will appear at all for a post
			 * type. If true, then the 'c2c_quick_drafts_access_show_if_empty' filter
			 * would ultimately determine if the link should appear based on the
			 * presence of actual drafts.
			 *
			 * @param bool   $allow     Show the "My Drafts" link in admin menu? Default true.
			 * @param object $post_type The post type object.
			 */
			$show_my_drafts = (bool) apply_filters( 'c2c_quick_drafts_access_show_my_drafts_menu_link',  true, $post_type );

			// Count of all drafts the user has for this post type.
			if ( $show_my_drafts ) {
				$num_my_drafts = count( $x = get_posts( array_merge(
					$query_vars,
					array(
						'author'         => get_current_user_id(),
						'fields'         => 'ids',
						'post_type'      => $name,
						'posts_per_page' => -1,
					)
				) ) );
			} else {
				// If not showing the 'My Drafts' link, the exact count doesn't matter.
				$num_my_drafts = false;
			}

			// If the 'All Drafts' link hasn't been disabled via filter.
			if ( $show_all_drafts ) {

				// Count of all drafts readable by the user.
				$num_all_drafts = (int) wp_count_posts( $name, 'readable' )->draft;

				/**
				 * Customizes whether the 'All Drafts' and/or 'My Drafts' links will appear
				 * for a post type when that post type currently has no drafts.
				 *
				 * @param bool   $show           Show drafts link if there aren't any
				 *                               drafts? Default false.
				 * @param string $post_type_name The post type name.
				 * @param string $post_type      The post type object.
				 * @param string $menu_type      The type of draft menu link. Either 'all'
				 *                               for 'All Drafts' or 'my' for 'My Drafts'.
				 */
				$show_if_empty = (bool) apply_filters( 'c2c_quick_drafts_access_show_if_empty', false, $name, $post_type, 'all' );

				// Show the 'All Drafts' link if there are drafts, or if forced to do so via filter.
				if ( ( $num_all_drafts > 0 ) || $show_if_empty ) {

					// Show the menu link unless 'My Drafts' is also being shown AND the user is responsible for all drafts
					if ( ! ( $show_my_drafts && $num_all_drafts === $num_my_drafts ) ) {

						// Link label.
						if ( 0 === $num_all_drafts ) {
							$menu_text = __( 'All Drafts', 'quick-drafts-access' );
						} else {
							$menu_text = sprintf( __( 'All Drafts (%s)', 'quick-drafts-access' ), number_format_i18n( $num_all_drafts ) );
						}

						// Add the menu link.
						add_submenu_page(
							$path,
							'', // page title is not applicable
							$menu_text,
							$post_type->cap->edit_posts,
							esc_url( add_query_arg( $query_vars, $path ) )
						);

					}

				}

			}

			// If the 'My Drafts' link hasn't been disabled via filter.
			if ( $show_my_drafts ) {

				// Ensure an int value for count of user drafts.
				$num_my_drafts = (int) $num_my_drafts;

				// Limit query to those posts authored by the current user.
				$query_vars['author'] = get_current_user_id();

				/** This filter is documented in quick-drafts-access.php */
				$show_if_empty = (bool) apply_filters( 'c2c_quick_drafts_access_show_if_empty', false, $name, $post_type, 'my' );

				// Show the 'My Drafts' link if there are drafts, or if forced to do so via filter.
				if ( ( $num_my_drafts > 0 ) || $show_if_empty ) {

					// Link label.
					if ( 0 === $num_my_drafts ) {
						$menu_text = __( 'My Drafts', 'quick-drafts-access' );
					} else {
						$menu_text = sprintf( __( 'My Drafts (%s)', 'quick-drafts-access' ), number_format_i18n( $num_my_drafts ) );
					}

					// Add the menu link.
					add_submenu_page(
						$path,
						'', // page title is not applicable
						$menu_text,
						$post_type->cap->edit_posts,
						esc_url( add_query_arg( $query_vars, $path ) )
					);

				}

			}

		}

	}

	/**
	 * Displays a dropdown for filtering the draft posts list table by author.
	 *
	 * @since 2.2.0
	 *
	 * @param string $post_type The post type slug.
	 * @param string $top       The location of the extra table nav markup.
	 */
	public static function filter_drafts_by_author( $post_type, $which ) {
		global $wpdb;

		if (
			'top' !== $which
		||
			! isset( $_GET['post_status'] )
		||
			'draft' !== $_GET['post_status']
		||
			/**
			 * Filter for removal of 'Drafts By' dropdown from drafts post list table.
			 *
			 * @since 2.2.0
			 *
			 * @param bool   $disable   Disable the 'drafts by' dropdown? Default false.
			 * @param string $post_type The post type slug.
			 */
			(bool) apply_filters( 'c2c_quick_drafts_access_disable_filter_dropdown', false, $post_type )
		||
			// Ensure post type is supported.
			! in_array( $post_type, array_keys( self::get_post_types() ) )
		) {
			return;
		}

		// Ensure there are other draft authors to filter on.
		$draft_authors = $wpdb->get_col( "SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status = 'draft'" );
		if ( ! $draft_authors ) {
			return;
		}

		// Get usernames.
		$users = array();
		foreach ( $draft_authors as $user_id ) {
			$users[ $user_id ] = new WP_User( $user_id );
		}
		uasort( $users, function ( $a, $b ) { return strnatcmp( $a->display_name, $b->display_name ); } );

		$curr_draft_author = isset( $_GET['author'] ) ? (int) $_GET['author'] : 0;

		?>
		<label for="filter-by-draft-author" class="screen-reader-text"><?php _e( 'Filter by author', 'quick-drafts-access' ); ?></label>
			<select name="author" id="filter-by-draft-author">
				<option<?php selected( $curr_draft_author, 0 ); ?> value="0"><?php _e( 'All Draft Authors',  'quick-drafts-access' ); ?></option>
				<?php
				foreach ( $users as $author_id => $author ) {
					if ( 0 == $author_id ) {
						continue;
					}

					printf(
						"<option%s value=\"%d\">%s</option>\n",
						selected( $curr_draft_author, $author_id, false ),
						$author_id,
						esc_html( $author->display_name )
					);
				}
				?>
			</select>
<?php
	}

}

add_action( 'plugins_loaded', array( 'c2c_QuickDraftsAccess', 'init' ) );

endif;

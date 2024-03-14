<?php
/**
 * Popup Tags Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Popupaoc_Popup_Tags {

	function __construct() {

		// Action to display general tags
		add_action( 'popupaoc_general_tags', array($this, 'popupaoc_general_tags_html') );
	}

	/**
	 * Function to display general tags 
	 * 
	 * @since 1.4
	 */
	function popupaoc_general_tags_html() { ?>

		<div class="paoc-modal-sub-title"><strong># <?php esc_html_e('General Tags', 'popup-anything-on-click'); ?></strong></div>

		<table class="wp-list-table widefat fixed striped paoc-tags-tbl">
			<tbody>
				<tr>
					<td><strong><?php esc_html_e('Site Name', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="site_name"]' readonly></td>
					<td><?php esc_html_e('Display website name.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Site URL', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="site_url"]' readonly></td>
					<td><?php esc_html_e('Display website URL.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Site Logo', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="site_logo"]' readonly></td>
					<td><?php esc_html_e('Display website logo.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Admin Email', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="admin_email"]' readonly></td>
					<td><?php esc_html_e('Display admin email addresss.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('User Name', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="user_name"]' readonly></td>
					<td><?php esc_html_e('Display user name.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('User Email', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="user_email"]' readonly></td>
					<td><?php esc_html_e('Display user email addresss.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Page/Post Title', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="page_title"]' readonly></td>
					<td><?php esc_html_e('Display current page or post title.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Post Excerpt', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="post_excerpt"]' readonly></td>
					<td><?php esc_html_e('Display current post excerpt.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Date Time', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="date_time"]' readonly></td>
					<td><?php esc_html_e('Display current date & time.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Date', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="date"]' readonly></td>
					<td><?php esc_html_e('Display current date.', 'popup-anything-on-click'); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Year', 'popup-anything-on-click'); ?></strong></td>
					<td><input type="text" class="large-text paoc-copy-clipboard" value='[paoc_details display="year"]' readonly></td>
					<td><?php esc_html_e('Display current year.', 'popup-anything-on-click'); ?></td>
				</tr>
			</tbody>
		</table>
	<?php }
}

$popupaoc_popup_tags = new Popupaoc_Popup_Tags();
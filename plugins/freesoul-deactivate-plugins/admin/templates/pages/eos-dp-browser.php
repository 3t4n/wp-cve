<?php
/**
 * Template User Agent.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for keeping active a certain plugin only when specific User Agents are matched.
function eos_dp_browser_callback() {
	add_filter( 'fdp_one_place_popup_title', function( $title ) {
		return esc_html__( 'Write the User Agents for which %s must be deactivated. Separate them by a return line.', 'freesoul-deactivate-plugins' )
			. '<p>' . esc_html__( 'Use the star * to replace any groups of characters.', 'freesoul-deactivate-plugins' ) . '</p>';
	} );
	add_action(
		'fdp_one_column_after_title',
		function() {
			?>
		<p><?php esc_html_e( 'Click on the pencil to set the User Agents.', 'freesoul-deactivate-plugins' ); ?></p>
		<p><?php esc_html_e( 'If you use these settings for a specific plugin, then that plugin will be deactivated when the specified User Agents are matched.', 'freesoul-deactivate-plugins' ); ?></p>
		<p><span class="dashicons dashicons-warning"></span><strong> <?php esc_html_e( 'If the pages are served by cache, the caching system must serve a different cache for the User Agents that you set here.', 'freesoul-deactivate-plugins' ); ?></strong></p>
			<?php
		}
	);
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-one-place.php';
	$page = new Fdp_One_Place( 'eos_dp_browser', esc_attr__( 'Disable plugins by User Agent.', 'freesoul-deactivate-plugins' ) );
	return;
}

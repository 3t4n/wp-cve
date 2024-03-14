<?php
/**
 * Template Plugin By URL.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for keeping active a ceratin plugin only when specific URLs are matched.
function eos_dp_one_place_callback() {
	add_action(
		'fdp_one_column_after_title',
		function() {
			?>
		<p><?php esc_html_e( 'Keep active specific plugins only where you need them.', 'freesoul-deactivate-plugins' ); ?></p>
		<p><?php esc_html_e( 'Click on the pencil to set the URLs where you need a specific plugin.', 'freesoul-deactivate-plugins' ); ?></p>
		<p><?php esc_html_e( 'If you use these settings for a specific plugin, then that plugin will be deactivated everywhere but active when the specified URLs are matched.', 'freesoul-deactivate-plugins' ); ?></p>
			<?php
		}
	);
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-one-place.php';
	$page = new Fdp_One_Place( 'eos_dp_one_place', esc_attr__( 'Keep active only where the URLs are matched.', 'freesoul-deactivate-plugins' ) );
	return;
}

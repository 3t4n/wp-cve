<?php
/**
 * Template Mobile.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivatin plugins for the mobile version.
function eos_dp_mobile_callback() {
	add_action(
		'fdp_one_column_after_title',
		function() {
			?>
		<p><?php esc_html_e( 'The plugins you uncheck here will always be disabled on mobile devices, no matter which pages and what you set on other options', 'freesoul-deactivate-plugins' ); ?></p>
		<p><strong><?php esc_html_e( 'Be sure you have a server cache plugin that distinguishes between mobile and desktop.', 'freesoul-deactivate-plugins' ); ?></strong></p>
			<?php
		}
	);
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-one-column-page.php';
	$page = new Eos_Fdp_One_Column_Page( 'eos_dp_mobile', esc_attr__( 'Mobile version.', 'freesoul-deactivate-plugins' ), 'smartphone' );
	return;
}

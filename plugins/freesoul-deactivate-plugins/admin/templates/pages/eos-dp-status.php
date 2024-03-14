<?php
/**
 * Template Status.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// It adds the status section.
function eos_dp_pro_status_callback() {
	wp_nonce_field( 'eos_dp_pro_setts', 'eos_dp_pro_setts' );
	eos_dp_navigation();
	?>
	<section id="eos-dp-status-section" class="eos-dp-section">
	<?php
	require_once EOS_DP_PLUGIN_DIR . '/admin/class.admin.system.report.php';
	$status = new EOS_DP_PRO_STATUS();
	$status->output();
	?>
	</section>
	<?php
}

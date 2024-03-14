<?php
/**
 * This navbar view component file.
 *
 * @package broken-link-cheker/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
echo '<div>
				<div id ="moblc_message"></div>
			</div>';
echo ' <div style="margin-top:3em;">
				<h1>';
if ( current_user_can( 'administrator' ) ) {
	echo 'Broken Link Checker&nbsp;
						<a class="moblc_troubleshoot_button" id="moblc_troubleshooting" style="font-size:17px;" href="' . esc_url( $debug_url ) . '">Troubleshoot</a>
						<input type="hidden" name="moblc_trobleshoot_index" value="' . esc_attr( delete_site_option( 'moblc_troubleshoot_index' ) ) . '"/>';
}
echo '</h1>			
	</div>
		</div>';
?>

<div class="moblc_flex-container" id="moblc_flex-container">
	<div class="nav-tab-wrapper moblc_nav-tab-wrapper">
		<?php
		echo '<a id="moblc_manual" class="moblc_nav-tab nav-tab ' . esc_attr(
			( 'moblc_manual' === $active_tab
			? 'moblc_nav-tab-active nav-tab-active' : '' )
		) . '" href="' . esc_url( $manual_url ) . '">Dashboard</a>';
		echo '<a id="moblc_report" class="moblc_nav-tab nav-tab ' . esc_attr(
			( 'moblc_report' === $active_tab
			? 'moblc_nav-tab-active nav-tab-active' : '' )
		) . '" href="' . esc_url( $report_url ) . '">Report</a>';
		echo '<a id="moblc_settings" class="moblc_nav-tab nav-tab ' . esc_attr(
			( 'moblc_settings' === $active_tab
			? 'moblc_nav-tab-active nav-tab-active' : '' )
		) . '" href="' . esc_url( $settings_url ) . '">Settings</a>';


		?>
	</div>
</div>

<?php
/**
 * Export widgets template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox export-widgets-box">
	<form method="post" action="<?php menu_page_url( 'better-admin-bar', true ); ?>">
		<input type="hidden" name="swift_control_action" value="export">
		<?php wp_nonce_field( 'swift_control_export_widgets', 'swift_control_export_nonce' ); ?>
		<h2><?php _e( 'Export', 'better-admin-bar' ); ?></h2>
		<div class="heatbox-content">
			<p>
				<?php _e( 'Use the export button to generate a .json file which you can then import to another WordPress installation.', 'better-admin-bar' ); ?>
			</p>
			<?php submit_button( __( 'Export File', 'better-admin-bar' ), 'primary', 'submit_export' ); ?>
		</div>
	</form>
</div>

<?php
/**
 * Import widgets template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox import-widgets-box">
	<form method="post" action="<?php menu_page_url( 'better-admin-bar', true ); ?>" enctype="multipart/form-data">
		<input type="hidden" name="swift_control_action" value="import">
		<?php wp_nonce_field( 'swift_control_import_widgets', 'swift_control_import_nonce' ); ?>
		<h2><?php _e( 'Import', 'better-admin-bar' ); ?></h2>
		<div class="heatbox-content">
			<p>
				<?php _e( 'Select the JSON file you would like to import.', 'better-admin-bar' ); ?>
			</p>
			<div class="setting-fields is-gapless">
				<div class="fields-wrapper">
					<label class="block-label" for="swift_control_import_file">Select File</label>
					<input type="file" name="swift_control_import_file" id="swift_control_import_file">
				</div>
			</div>
			<?php submit_button( __( 'Import File', 'better-admin-bar' ), 'primary', 'submit_import' ); ?>
		</div>
	</form>
</div>

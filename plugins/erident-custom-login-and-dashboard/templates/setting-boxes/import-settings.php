<?php
/**
 * Import settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox import-widgets-box">
	<form method="post" action="<?php menu_page_url( 'erident-custom-login-and-dashboard', true ); ?>#tools" enctype="multipart/form-data">
		<input type="hidden" name="er_action" value="import_settings">
		<?php wp_nonce_field( 'er_import_nonce', 'er_import_nonce' ); ?>

		<h2><?php _e( 'Import', 'erident-custom-login-and-dashboard' ); ?></h2>
		<div class="heatbox-content">
			<p>
				<?php _e( 'Select the .json file you would like to import.', 'erident-custom-login-and-dashboard' ); ?>
			</p>
			<div class="setting-fields is-gapless">
				<div class="fields-wrapper">
					<label class="block-label" for="import_file">Select File</label>
					<input type="file" name="import_file" id="import_file">
				</div>
			</div>
			<?php submit_button( __( 'Import Settings', 'erident-custom-login-and-dashboard' ), 'primary', 'submit_import' ); ?>
		</div>
	</form>
</div>

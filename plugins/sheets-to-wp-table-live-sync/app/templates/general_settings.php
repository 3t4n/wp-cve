<?php
/**
 * Displays general settings template.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

settings_errors();
?>
<style>
.notice {
	padding: 5px 15px;
}

table {
	margin: 0;
}
</style>
<div class="gswpts_general_settings_container">

	<div class="ui segment gswpts_loader">
		<div class="ui active inverted dimmer">
			<div class="ui massive text loader"></div>
		</div>
		<p></p>
		<p></p>
		<p></p>
	</div>

	<div class="child_container mt-4 settings_content transition hidden">
		<form action="options.php" method="POST">

			<div class="row heading_row">
				<div class="col-12 d-flex justify-content-start p-0 align-items-center">
					<img src="<?php echo esc_url( SWPTLS_BASE_URL . 'assets/public/images/logo_30_30.svg' ); ?>"
						alt="sheets-logo">
					<span class="ml-2">
						<strong><?php echo esc_html( SWPTLS_PLUGIN_NAME ); ?></strong>
					</span>
					<span class="gswpts_changelogs"></span>
				</div>
				<div class="col-12 p-0 mt-2 d-flex justify-content-between align-items-center">
					<h4 class="m-0">
						<?php esc_html_e( 'General Settings', 'sheetstowptable' ); ?>
					</h4>
					<span>
						<button type="submit" name="submit" id="submit" class="button ui violet m-0"
							value="Save Changes">
							<?php esc_html_e( 'Save Changes', 'sheetstowptable' ); ?>
							&nbsp; <i class='fas fa-save'></i>
						</button>
					</span>
				</div>
			</div>

			<div class="row mt-3 dash_boxes pt-3 pb-3 position-relative">
				<div class="col-md-12 pt-2 pb-2 pl-4 pr-4">
					<div class="gswpts_settings_container">
						<?php settings_fields( 'gswpts_general_setting' ); ?>
						<?php do_settings_sections( 'gswpts-general-settings' ); ?>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
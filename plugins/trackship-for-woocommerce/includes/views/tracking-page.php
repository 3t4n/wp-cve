<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="post" id="trackship_tracking_page_form" action="" enctype="multipart/form-data">
	<div class="heading_panel section_tracking_page_heading <?php echo 'tracking' == $section ? 'checked' : ''; ?>">
		<strong><?php esc_html_e( 'Tracking Page', 'trackship-for-woocommerce' ); ?></strong>
		<div class="heading_panel_save">
			<span class="dashicons dashicons-arrow-right-alt2"></span>
			<div class="spinner"></div>
			<button name="save" class="button-primary button-trackship btn_large woocommerce-save-button" type="submit" value="Save & close">
				<?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?>
			</button>
			<?php wp_nonce_field( 'trackship_tracking_page_form', 'trackship_tracking_page_form_nonce' ); ?>
			<input type="hidden" name="action" value="trackship_tracking_page_form_update">
		</div>
	</div>
	<div class="panel_content section_tracking_page_content">
		<div class="outer_form_table">
			<?php $this->get_settings_html( $this->get_tracking_page_data() ); ?>
		</div>
	</div>
</form>

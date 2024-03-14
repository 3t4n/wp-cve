<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="post" class="zorem_plugin_setting_tab_form trackship_sms_settings">
	<div class="heading_panel section_sms_heading">
		<strong><?php esc_html_e( 'SMS Settings', 'trackship-for-woocommerce' ); ?></strong>
		<div class="heading_panel_save">
			<span class="dashicons dashicons-arrow-right-alt2"></span>
			<div class="spinner workflow_spinner"></div>
			<button name="save" class="button-primary button-trackship btn_large woocommerce-save-button button-smswoo" type="submit" ><?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?></button>
			<?php $nonce = wp_create_nonce( 'smswoo_settings_tab' ); ?>
			<input type="hidden" name="smswoo_settings_tab_nonce" value="<?php echo esc_attr($nonce); ?>">
			<input type="hidden" name="action" value="smswoo_settings_tab_save">
		</div>
	</div>
	<div class="panel_content section_sms_content">
		<?php if ( function_exists( 'SMSWOO' ) || is_plugin_active( 'zorem-sms-for-woocommerce/zorem-sms-for-woocommerce.php' ) ) { ?>
			<span class="plugin_setting_note">
				<strong><?php esc_html_e( 'Please note: ', 'trackship-for-woocommerce' ); ?></strong>
				<?php /* translators: %s: search for a tag */ ?>
				<?php printf( esc_html__( 'You can edit the SMS gateways from the SMS for WooCommerce %1$ssettings%2$s', 'trackship-for-woocommerce' ), '<a href="' . esc_url( admin_url( 'admin.php?page=sms-for-woocommerce&tab=settings' ) ) . '">', '</a>' ); ?>
			</span>
		<?php } ?>
		<div class="outer_form_table">
			<?php $this->get_html( $this->get_sms_provider_data() ); ?>
		</div>
	</div>
</form>

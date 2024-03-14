<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cbr_tab_inner_container zorem_plugin_tab_inner_container">
	<form method="post" class="zorem_plugin_setting_tab_form">
	
		<?php $this->get_shipment_template_html( $this->get_customer_tracking_status_settings() ); ?>
		<?php $nonce = wp_create_nonce( 'smswoo_settings_tab' ); ?>
		<input type="hidden" name="smswoo_settings_tab_nonce" value="<?php echo esc_attr($nonce); ?>">
		<input type="hidden" name="action" value="smswoo_settings_tab_save">
				
	</form>
</div>

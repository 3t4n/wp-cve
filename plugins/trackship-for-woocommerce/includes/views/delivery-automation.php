<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="post" id="delivery_automation_form" action="" enctype="multipart/form-data">
	<div class="heading_panel section_delivery_automation_heading <?php echo 'delivery_automation' == $section ? 'checked' : ''; ?>">
		<strong><?php esc_html_e( 'Delivery Automation', 'trackship-for-woocommerce' ); ?></strong>
		<div class="heading_panel_save">
			<span class="dashicons dashicons-arrow-right-alt2"></span>
			<div class="spinner"></div>
			<button name="save" class="button-primary button-trackship btn_large woocommerce-save-button" type="submit" value="Save & close">
				<?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?>
			</button>
			<?php wp_nonce_field( 'trackship_delivery_automation_form', 'trackship_delivery_automation_form_nonce' ); ?>
			<input type="hidden" name="action" value="trackship_delivery_automation_form_update">
		</div>
	</div>
	<div class="panel_content section_delivery_automation_content">
		<div class="outer_form_table">
			<div class="settings_toogle">
				<input type="hidden" name="wc_ast_status_delivered" value="0"/>
				<input class="ast-tgl ast-tgl-flat ts_order_status_toggle" id="wc_ast_status_delivered" name="wc_ast_status_delivered" type="checkbox" <?php echo get_option( 'wc_ast_status_delivered', 1 ) ? 'checked' : ''; ?> value="1"/>
				<label class="ast-tgl-btn ast-tgl-btn-green" for="wc_ast_status_delivered"></label>
				<label class="setting_ul_tgl_checkbox_label">
					<span><?php esc_html_e( 'Enable Order Delivery Automation', 'trackship-for-woocommerce' ); ?></span>
					<span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'Enable a Custom Order Status Delivered that will be set automatically when all the order shipments are delivered', 'trackship-for-woocommerce' ); ?>"></span>
				</label>
			</div>
			<div class="ts4wc_delivered_color">
				<div class="order-label wc-delivered">
					<?php 
					if ( get_option( 'wc_ast_status_delivered', 1 ) ) {
						esc_html_e( wc_get_order_status_name( 'delivered' ), 'trackship-for-woocommerce' );
					} else {
						esc_html_e( 'Delivered', 'trackship-for-woocommerce' );
					}
					?>
				</div>
				<input class="input-text regular-input color_input" type="text" name="wc_ast_status_label_color" id="wc_ast_status_label_color" value="<?php echo esc_html( get_option( 'wc_ast_status_label_color', '#09d3ac' ) ); ?>" placeholder="">
				<select class="select ts_custom_order_color_select" id="wc_ast_status_label_font_color" name="wc_ast_status_label_font_color">	
				<option value="#fff" <?php echo '#fff' == get_option('wc_ast_status_label_font_color', '#fff') ? 'selected' : ''; ?>> <?php esc_html_e( 'Light Font', 'trackship-for-woocommerce' ); ?>
				</option>
					<option value="#000" <?php echo '#000' == get_option('wc_ast_status_label_font_color', '#fff') ? 'selected' : ''; ?>><?php esc_html_e( 'Dark Font', 'trackship-for-woocommerce' ); ?>
					</option>
				</select>
			</div>
		</div>
	</div>
</form>

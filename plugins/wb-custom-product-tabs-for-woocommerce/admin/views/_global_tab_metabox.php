<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wb_tab_metabox_container">
	<input type="hidden" name="wb_tab_meta_box" value="1">
	
	<label class="wb_tab_form_label"><?php _e('Tab nickname', 'wb-custom-product-tabs-for-woocommerce'); ?></label>
	<input type="text" name="wb_tab_tab_nickname" value="<?php echo esc_attr($tab_nickname);?>">
	<div class="wb_tabpanel_hlp" style="float:none;"><?php _e('Use this nickname to identify tabs in the backend', 'wb-custom-product-tabs-for-woocommerce'); ?></div>

	<label class="wb_tab_form_label"><?php _e('Tab position', 'wb-custom-product-tabs-for-woocommerce'); ?></label>
	<input type="text" name="wb_tab_tab_position" value="<?php echo esc_attr($tab_position);?>">
	<div style="margin-top:10px; display:inline-block;">
		<a href="https://webbuilder143.com/how-to-arrange-woocommerce-custom-product-tabs/" target="_blank"><?php _e('Know more', 'wb-custom-product-tabs-for-woocommerce'); ?> <span class="dashicons dashicons-external" style="text-decoration:none;"></span></a>
	</div>
</div>
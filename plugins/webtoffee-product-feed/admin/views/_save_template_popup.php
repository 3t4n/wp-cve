<?php
/**
 * Template saving popup HTML for Export
 *
 * @link            
 *
 * @package  Webtoffee_Product_Feed_Sync
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wt_pf_template_name wt_pf_popup" data-save-label="<?php _e('Save');?>" data-saveas-label="<?php _e('Save as');?>">
	<div class="wt_pf_popup_hd">
		<span style="line-height:40px;" class="dashicons dashicons-edit"></span>
		<span class="wt_pf_popup_hd_label"></span>
		<div class="wt_pf_popup_close">X</div>
	</div>
	<div class="wt_pf_warn_box">
		<div class="wt_pf_warn wt_pf_template_name_wrn">
			<?php _e('Please enter name');?> 
		</div>
	</div>
	<div class="wt_pf_template_name_box">
		<label class="wt_pf_template_name_label"><?php _e('Template name');?></label>
		<input type="text" name="wt_pf_template_name_field" class="wt_pf_text_field wt_pf_template_name_field">
		<div class="wt_pf_popup_footer">
			<button type="button" name="" class="button-secondary wt_pf_popup_cancel">
				<?php _e('Cancel');?> 
			</button>
			<button type="button" name="" class="button-primary wt_pf_template_create_btn"></button>	
		</div>
	</div>
</div>
<?php 
/**
 * @since      3.0.0
 * @package    DirectoryPress
 * @subpackage DirectoryPress/includes/core/fields/email/_html
 * @author     Designinvento <developers@designinvento.net>
 */
 
$classes = ($field->use_link_text)? 'col-md-6 col-sm-6 col-xs-12': 'col-md-12 col-sm-12 col-xs-12';
?>
<div class="field-wrap field-input-item submit_field_id_<?php echo esc_attr($field->id); ?> field-type-<?php echo esc_attr($field->type); ?>">
	<p class="directorypress-submit-field-title">
		<?php echo esc_html($field->name); ?>
		<?php do_action('directorypress_listing_submit_required_lable', $field); ?>
		<?php do_action('directorypress_listing_submit_user_info', $field->description); ?>
		<?php do_action('directorypress_listing_submit_admin_info', 'listing_field_link'); ?>
	</p>
	<div class="row clearfix">
		<div class="<?php echo esc_attr($classes); ?>">
			<div class="input-group">
				<span class="input-group-addon"><i class="fas fa-external-link-alt"></i></span>
				<input type="text" name="directorypress-field-input-url_<?php echo esc_attr($field->id); ?>" class="form-control" placeholder="<?php _e('URL:', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_url($field->value['url']); ?>" />
			</div>
		</div>
		<?php if ($field->use_link_text): ?>
			<div class="<?php echo esc_attr($classes); ?>">
				<div class="input-group">
					<span class="input-group-addon"><i class="fas fa-globe-europe"></i></span>
					<input type="text" name="directorypress-field-input-text_<?php echo esc_attr($field->id); ?>" class="form-control" placeholder="<?php _e('Link Text:', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_attr($field->value['text']); ?>" />
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
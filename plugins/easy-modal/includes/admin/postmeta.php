<?php
add_action('emodal_post_meta_box', 'emodal_post_meta_box_setting_table_open', 0);
function emodal_post_meta_box_setting_table_open($object)
{?>
<table class="form-table">
	<tbody><?php
}

add_action('emodal_post_meta_box', 'emodal_post_meta_box_setting_load', 1);
function emodal_post_meta_box_setting_load($object)
{
	$current_modals = get_post_meta($object->ID, EMCORE_SLUG.'_post_modals', true);
	$modals = get_all_modals("is_trash != 1 AND is_sitewide = 0");
	wp_nonce_field( EMCORE_NONCE, EMCORE_NONCE);?>
	<?php if(count($modals)):?>
	<tr>
		<th scope="row">
			<label><?php _e('Select which modals to load', 'easy-modal' );?></label>
		</th>
		<td>
			<div class="scrollable-checkboxes" style="padding:5px 10px;border:1px solid #eee;max-height:200px;overflow-y:scroll;">
				<?php foreach($modals as $modal):?>
				<label class="selectit"><input type="checkbox" name="emodal_post_modals[]" value="<?php esc_attr_e($modal->id);?>"<?php esc_attr_e(is_array($current_modals) && in_array($modal->id,$current_modals) ? ' checked="checked"' : '');?>> <?php esc_html_e($modal->name);?></label><br/>
				<?php endforeach;?>
			</div>
			<p class="description"><?php _e('Choose witch modal will be loaded on this page.', 'easy-modal' )?></p>
		</td>
	</tr>
	<?php endif;
}

add_action('emodal_post_meta_box', 'emodal_post_meta_box_setting_table_close', 1000);
function emodal_post_meta_box_setting_table_close($object)
{?>
	</tbody>
</table><?php
}


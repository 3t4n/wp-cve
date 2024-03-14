<?php
add_filter('emodal_admin_theme_form_tabs', 'emodal_admin_theme_form_content_tab', 60);
function emodal_admin_theme_form_content_tab($tabs)
{
	$tabs[] = array( 'id' => 'content', 'label' => __('Content', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_theme_form_tab_content', 'emodal_admin_theme_form_content_tab_settings', 10);
function emodal_admin_theme_form_content_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_theme_form_tab_content_settings');?>
		</tbody>
	</table><?php
}


add_action('emodal_admin_theme_form_tab_content_settings', 'emodal_admin_theme_form_content_tab_settings_font', 10);
function emodal_admin_theme_form_content_tab_settings_font()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Font', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="content_font_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][content][font][color]" id="content_font_color" value="<?php esc_attr_e(get_current_modal_theme('meta.content.font.color'))?>" class="color-picker" />
		</td>
	</tr>
<?php /*
	<tr>
		<th scope="row">
			<label for="content_font_size"><?php _e('Size', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][content][font][size]" id="content_font_size" min="8" max="32" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.content.font.size'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.content.font.size'));?></span>px</span>
			<p class="description"><?php _e('Font size.', 'easy-modal' )?></p>
		</td>
	</tr>
*/?>
	<tr>
		<th scope="row">
			<label for="content_font_family"><?php _e('Family', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][content][font][family]" id="content_font_family">
			<?php foreach(apply_filters('emodal_font_family_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.content.font.family') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
		</td>
	</tr><?php
}
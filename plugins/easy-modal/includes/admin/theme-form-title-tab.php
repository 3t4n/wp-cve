<?php
add_filter('emodal_admin_theme_form_tabs', 'emodal_admin_theme_form_title_tab', 50);
function emodal_admin_theme_form_title_tab($tabs)
{
	$tabs[] = array( 'id' => 'title', 'label' => __('Title', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_theme_form_tab_title', 'emodal_admin_theme_form_title_tab_settings', 10);
function emodal_admin_theme_form_title_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_theme_form_tab_title_settings');?>
		</tbody>
	</table><?php
}


add_action('emodal_admin_theme_form_tab_title_settings', 'emodal_admin_theme_form_title_tab_settings_font', 10);
function emodal_admin_theme_form_title_tab_settings_font()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Font', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_font_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][title][font][color]" id="title_font_color" value="<?php esc_attr_e(get_current_modal_theme('meta.title.font.color'))?>" class="color-picker" />
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="title_font_size"><?php _e('Size', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][title][font][size]" id="title_font_size" min="8" max="32" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.title.font.size'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.title.font.size'));?></span>px
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_font_family"><?php _e('Family', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][title][font][family]" id="title_font_family">
			<?php foreach(apply_filters('emodal_font_family_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.title.font.family') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_text_align"><?php _e('Align', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][title][text][align]" id="title_text_align">
			<?php foreach(apply_filters('emodal_text_align_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.title.text.align') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
		</td>
	</tr><?php
}

add_action('emodal_admin_theme_form_tab_title_settings', 'emodal_admin_theme_form_title_tab_settings_textshadow', 20);
function emodal_admin_theme_form_title_tab_settings_textshadow()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Text Shadow', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_textshadow_horizontal"><?php _e('Horizontal Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][title][textshadow][horizontal]" id="title_textshadow_horizontal" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.title.textshadow.horizontal'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.title.textshadow.horizontal'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_textshadow_vertical"><?php _e('Vertical Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][title][textshadow][vertical]" id="title_textshadow_vertical" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.title.textshadow.vertical'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.title.textshadow.vertical'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_textshadow_blur"><?php _e('Blur Radius', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][title][textshadow][blur]" id="title_textshadow_blur" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.title.textshadow.blur'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.title.textshadow.blur'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_textshadow_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][title][textshadow][color]" id="title_textshadow_color" value="<?php esc_attr_e(get_current_modal_theme('meta.title.textshadow.color'))?>" class="color-picker textshadow-color" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="title_textshadow_opacity"><?php _e('Opacity', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][title][textshadow][opacity]" id="title_textshadow_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.title.textshadow.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.title.textshadow.opacity'));?></span>%</span>
		</td>
	</tr><?php
}
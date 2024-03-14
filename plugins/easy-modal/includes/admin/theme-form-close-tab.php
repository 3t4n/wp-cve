<?php
add_filter('emodal_admin_theme_form_tabs', 'emodal_admin_theme_form_close_tab', 70);
function emodal_admin_theme_form_close_tab($tabs)
{
	$tabs[] = array( 'id' => 'close', 'label' => __('Close', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_theme_form_tab_close', 'emodal_admin_theme_form_close_tab_settings', 10);
function emodal_admin_theme_form_close_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_theme_form_tab_close_settings');?>
		</tbody>
	</table><?php
}

add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_text', 10);
function emodal_admin_theme_form_close_tab_settings_text()
{
	?><tr>
		<th scope="row">
			<label for="close_text"><?php _e('Text', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][close][text]" id="close_text" value="<?php esc_attr_e(get_current_modal_theme('meta.close.text'))?>" />
			<p class="description"><?php _e('Enter the close button text.', 'easy-modal' )?></p>
		</td>
	</tr><?php
}


add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_padding', 20);
function emodal_admin_theme_form_close_tab_settings_padding()
{
	?><tr>
		<th scope="row">
			<label for="close_padding"><?php _e('Padding', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][padding]" id="close_padding" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.padding'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.padding'));?></span>px</span>
		</td>
	</tr><?php
}


add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_location', 30);
function emodal_admin_theme_form_close_tab_settings_location()
{
	?><tr>
		<th scope="row">
			<label for="close_location"><?php _e('Location', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][close][location]" id="close_location">
			<?php foreach(apply_filters('emodal_theme_close_location_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.close.location') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Choose which corner the close button will be positioned.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Position', 'easy-modal' );?></h3></th>
	</tr>
	<tr class="topright topleft">
		<th scope="row">
			<label for="close_position_top"><?php _e('Top', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][position][top]" id="close_position_top" min="-100" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.position.top'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.position.top'));?></span>px</span>
		</td>
	</tr>
	<tr class="topleft bottomleft">
		<th scope="row">
			<label for="close_position_left"><?php _e('Left', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][position][left]" id="close_position_left" min="-100" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.position.left'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.position.left'));?></span>px</span>
		</td>
	</tr>
	<tr class="bottomleft bottomright">
		<th scope="row">
			<label for="close_position_bottom"><?php _e('Bottom', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][position][bottom]" id="close_position_bottom" min="-100" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.position.bottom'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.position.bottom'));?></span>px</span>
		</td>
	</tr>
	<tr class="topright bottomright">
		<th scope="row">
			<label for="close_position_right"><?php _e('Right', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][position][right]" id="close_position_right" min="-100" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.position.right'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.position.right'));?></span>px</span>
		</td>
	</tr><?php
}



add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_font', 40);
function emodal_admin_theme_form_close_tab_settings_font()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Font', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_font_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][close][font][color]" id="close_font_color" value="<?php esc_attr_e(get_current_modal_theme('meta.close.font.color'))?>" class="color-picker" />
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="close_font_size"><?php _e('Size', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][font][size]" id="close_font_size" min="8" max="32" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.font.size'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.font.size'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_font_family"><?php _e('Family', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][close][font][family]" id="close_font_family">
			<?php foreach(apply_filters('emodal_font_family_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.close.font.family') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
		</td>
	</tr><?php
}


add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_background', 50);
function emodal_admin_theme_form_close_tab_settings_background()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Background', 'easy-modal' );?></ h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_background_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][close][background][color]" id="close_background_color" value="<?php esc_attr_e(get_current_modal_theme('meta.close.background.color'))?>" class="color-picker background-color" />
		</td>
	</tr>
	<tr class="background-opacity">
		<th scope="row">
			<label for="close_background_opacity"><?php _e('Opacity', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][background][opacity]" id="close_background_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.background.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.background.opacity'));?></span>px</span>
		</td>
	</tr><?php
	if(!class_exists('EMAdvancedThemeEditor')){?>
	<tr>
		<th colspan="2" class="pro-upgrade-tip">
			<img style="" src="<?php echo EMCORE_URL;?>/assets/images/admin/icon-info-21x21.png"/> Wanna use background images? <a href="http://easy-modal.com/addons/unlimited-themes" target="_blank">Check out Advanced Theme Editor!</a>.
		</th>
	</tr><?php
	}
}



add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_border', 60);
function emodal_admin_theme_form_close_tab_settings_border()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Border', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_border_radius"><?php _e('Radius', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][border][radius]" id="close_border_radius" min="0" max="28" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.border.radius'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.border.radius'));?></span>px</span>
			<p class="description"><?php _e('Choose a corner radius for your close button.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_border_style"><?php _e('Style', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][close][border][style]" id="close_border_style" class="border-style">
			<?php foreach(apply_filters('emodal_border_style_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.close.border.style') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Choose a border style for your close button.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="border-options">
		<th scope="row">
			<label for="close_border_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][close][border][color]" id="close_border_color" value="<?php esc_attr_e(get_current_modal_theme('meta.close.border.color'))?>" class="color-picker" />
		</td>
	</tr>
	<tr class="border-options">
		<th scope="row">
			<label for="close_border_width"><?php _e('Thickness', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][border][width]" id="close_border_width" min="0" max="5" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.border.width'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.border.width'));?></span>px</span>
		</td>
	</tr><?php
}


add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_boxshadow', 70);
function emodal_admin_theme_form_close_tab_settings_boxshadow()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Drop Shadow', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_inset"><?php _e('Inset', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][close][boxshadow][inset]" id="close_boxshadow_inset">
			<?php foreach(array(
				__('No', 'easy-modal' ) => 'no',
				__('Yes', 'easy-modal' ) => 'yes'
			) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.close.boxshadow.inset') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Set the box shadow to inset (inner shadow).', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_horizontal"><?php _e('Horizontal Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][boxshadow][horizontal]" id="close_boxshadow_horizontal" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.boxshadow.horizontal'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.boxshadow.horizontal'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_vertical"><?php _e('Vertical Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][boxshadow][vertical]" id="close_boxshadow_vertical" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.boxshadow.vertical'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.boxshadow.vertical'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_blur"><?php _e('Blur Radius', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][boxshadow][blur]" id="close_boxshadow_blur" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.boxshadow.blur'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.boxshadow.blur'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_spread"><?php _e('Spread', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][boxshadow][spread]" id="close_boxshadow_spread" min="-100" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.boxshadow.spread'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.boxshadow.spread'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][close][boxshadow][color]" id="close_boxshadow_color" value="<?php esc_attr_e(get_current_modal_theme('meta.close.boxshadow.color'))?>" class="color-picker boxshadow-color" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_boxshadow_opacity"><?php _e('Opacity', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][boxshadow][opacity]" id="close_boxshadow_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.boxshadow.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.boxshadow.opacity'));?></span>%</span>
		</td>
	</tr><?php
}

add_action('emodal_admin_theme_form_tab_close_settings', 'emodal_admin_theme_form_close_tab_settings_textshadow', 80);
function emodal_admin_theme_form_close_tab_settings_textshadow()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Text Shadow', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_textshadow_horizontal"><?php _e('Horizontal Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][textshadow][horizontal]" id="close_textshadow_horizontal" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.textshadow.horizontal'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.textshadow.horizontal'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_textshadow_vertical"><?php _e('Vertical Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][textshadow][vertical]" id="close_textshadow_vertical" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.textshadow.vertical'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.textshadow.vertical'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_textshadow_blur"><?php _e('Blur Radius', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][textshadow][blur]" id="close_textshadow_blur" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.textshadow.blur'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.textshadow.blur'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_textshadow_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][close][textshadow][color]" id="close_textshadow_color" value="<?php esc_attr_e(get_current_modal_theme('meta.close.textshadow.color'))?>" class="color-picker textshadow-color" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="close_textshadow_opacity"><?php _e('Opacity', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][close][textshadow][opacity]" id="close_textshadow_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.close.textshadow.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.close.textshadow.opacity'));?></span>%</span>
		</td>
	</tr><?php
}
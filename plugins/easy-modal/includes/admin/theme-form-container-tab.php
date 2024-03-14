<?php
add_filter('emodal_admin_theme_form_tabs', 'emodal_admin_theme_form_container_tab', 40);
function emodal_admin_theme_form_container_tab($tabs)
{
	$tabs[] = array( 'id' => 'container', 'label' => __('Container', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_theme_form_tab_container', 'emodal_admin_theme_form_container_tab_settings', 10);
function emodal_admin_theme_form_container_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_theme_form_tab_container_settings');?>
		</tbody>
	</table><?php
}


add_action('emodal_admin_theme_form_tab_container_settings', 'emodal_admin_theme_form_container_tab_settings_padding', 10);
function emodal_admin_theme_form_container_tab_settings_padding()
{
	?><tr>
		<th scope="row">
			<label for="container_padding"><?php _e('Padding', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][padding]" id="container_padding" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.padding'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.padding'));?></span>px</span>
		</td>
	</tr><?php
}


add_action('emodal_admin_theme_form_tab_container_settings', 'emodal_admin_theme_form_container_tab_settings_background', 20);
function emodal_admin_theme_form_container_tab_settings_background()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Background', 'easy-modal' );?></ h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_background_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][container][background][color]" id="container_background_color" value="<?php esc_attr_e(get_current_modal_theme('meta.container.background.color'))?>" class="color-picker background-color" />
		</td>
	</tr>
	<tr class="background-opacity">
		<th scope="row">
			<label for="container_background_opacity"><?php _e('Opacity', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][background][opacity]" id="container_background_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.background.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.background.opacity'));?></span>%</span>
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

add_action('emodal_admin_theme_form_tab_container_settings', 'emodal_admin_theme_form_container_tab_settings_border', 30);
function emodal_admin_theme_form_container_tab_settings_border()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Border', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_border_radius"><?php _e('Radius', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][border][radius]" id="container_border_radius" min="0" max="80" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.border.radius'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.border.radius'));?></span>px</span>
			<p class="description"><?php _e('Choose a corner radius for your container button.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_border_style"><?php _e('Style', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][container][border][style]" id="container_border_style" class="border-style">
			<?php foreach(apply_filters('emodal_border_style_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.container.border.style') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Choose a border style for your container button.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="border-options">
		<th scope="row">
			<label for="container_border_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][container][border][color]" id="container_border_color" value="<?php esc_attr_e(get_current_modal_theme('meta.container.border.color'))?>" class="color-picker" />
		</td>
	</tr>
	<tr class="border-options">
		<th scope="row">
			<label for="container_border_width"><?php _e('Thickness', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][border][width]" id="container_border_width" min="0" max="5" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.border.width'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.border.width'));?></span>px</span>
		</td>
	</tr><?php
}

add_action('emodal_admin_theme_form_tab_container_settings', 'emodal_admin_theme_form_container_tab_settings_boxshadow', 40);
function emodal_admin_theme_form_container_tab_settings_boxshadow()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Drop Shadow', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_inset"><?php _e('Inset', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="theme[meta][container][boxshadow][inset]" id="container_boxshadow_inset">
			<?php foreach(array(
				__('No', 'easy-modal' ) => 'no',
				__('Yes', 'easy-modal' ) => 'yes'
			) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal_theme('meta.container.boxshadow.inset') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Set the box shadow to inset (inner shadow).', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_horizontal"><?php _e('Horizontal Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][boxshadow][horizontal]" id="container_boxshadow_horizontal" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.boxshadow.horizontal'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.boxshadow.horizontal'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_vertical"><?php _e('Vertical Position', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][boxshadow][vertical]" id="container_boxshadow_vertical" min="-50" max="50" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.boxshadow.vertical'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.boxshadow.vertical'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_blur"><?php _e('Blur Radius', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][boxshadow][blur]" id="container_boxshadow_blur" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.boxshadow.blur'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.boxshadow.blur'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_spread"><?php _e('Spread', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][boxshadow][spread]" id="container_boxshadow_spread" min="-100" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.boxshadow.spread'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.boxshadow.spread'));?></span>px</span>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][container][boxshadow][color]" id="container_boxshadow_color" value="<?php esc_attr_e(get_current_modal_theme('meta.container.boxshadow.color'))?>" class="color-picker boxshadow-color" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label for="container_boxshadow_opacity"><?php _e('Opacity', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="theme[meta][container][boxshadow][opacity]" id="container_boxshadow_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.container.boxshadow.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.container.boxshadow.opacity'));?></span>%</span>
		</td>
	</tr><?php
}
<?php
add_filter('emodal_admin_theme_form_tabs', 'emodal_admin_theme_form_overlay_tab', 20);
function emodal_admin_theme_form_overlay_tab($tabs)
{
	$tabs[] = array( 'id' => 'overlay', 'label' => __('Overlay', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_theme_form_tab_overlay', 'emodal_admin_theme_form_overlay_tab_settings', 10);
function emodal_admin_theme_form_overlay_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_theme_form_tab_overlay_settings');?>
		</tbody>
	</table><?php
}

add_action('emodal_admin_theme_form_tab_overlay_settings', 'emodal_admin_theme_form_overlay_tab_settings_background', 10);
function emodal_admin_theme_form_overlay_tab_settings_background()
{
	?><tr>
		<th scope="row">
			<label for="overlay_background_color"><?php _e('Color', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" name="theme[meta][overlay][background][color]" id="overlay_background_color" value="<?php esc_attr_e(get_current_modal_theme('meta.overlay.background.color'))?>" class="color-picker background-color" />
			<p class="description"><?php _e('Choose the overlay color.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="background-opacity">
		<th scope="row">
			<label for="overlay_background_opacity"><?php _e('Opacity', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="range" name="theme[meta][overlay][background][opacity]" id="overlay_background_opacity" min="0" max="100" step="1" value="<?php esc_attr_e(get_current_modal_theme('meta.overlay.background.opacity'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal_theme('meta.overlay.background.opacity'));?></span>%</span>
			<p class="description"><?php _e('The opacity value for the overlay.', 'easy-modal' )?></p>
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
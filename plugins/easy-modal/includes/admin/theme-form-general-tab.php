<?php
add_filter('emodal_admin_theme_form_tabs', 'emodal_admin_theme_form_general_tab', 10);
function emodal_admin_theme_form_general_tab($tabs)
{
	$tabs[] = array( 'id' => 'general', 'label' => __('General', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_theme_form_tab_general', 'emodal_admin_theme_form_general_tab_settings', 10);
function emodal_admin_theme_form_general_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_theme_form_tab_general_settings');?>
		</tbody>
	</table><?php
}

add_action('emodal_admin_theme_form_tab_general_settings', 'emodal_admin_theme_form_general_tab_settings_name', 10);
function emodal_admin_theme_form_general_tab_settings_name()
{
	?><tr>
		<th scope="row">
			<label for="name">
				<?php _e('Name', 'easy-modal' );?>
				<span class="description">(required)</span>
			</label>
		</th>
		<td>
			<input type="text" class="regular-text" name="theme[name]" id="name" value="<?php esc_attr_e(get_current_modal_theme('name'))?>" required/>
		</td>
	</tr><?php
	if(!class_exists('EMUnlimitedThemes')){?>
	<tr>
		<th colspan="2" class="pro-upgrade-tip">
			<hr/>
			<img style="" src="<?php echo EMCORE_URL;?>/assets/images/admin/icon-info-21x21.png"/> Free Users can only create one (1) theme. <a href="http://easy-modal.com/addons/unlimited-themes" target="_blank">Check out Unlimited Modals!</a>.
		</th>
	</tr><?php
	}
}
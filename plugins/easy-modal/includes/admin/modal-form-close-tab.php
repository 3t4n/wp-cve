<?php
add_filter('emodal_admin_modal_form_tabs', 'emodal_admin_modal_form_close_tab', 30);
function emodal_admin_modal_form_close_tab($tabs)
{
	$tabs[] = array( 'id' => 'close', 'label' => __('Close Options', 'easy-modal' ) );
	return $tabs;
}


add_action('emodal_admin_modal_form_tab_close', 'emodal_admin_modal_form_close_tab_settings', 20);
function emodal_admin_modal_form_close_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_modal_form_tab_close_settings');?>
		</tbody>
	</table><?php
}


add_action('emodal_admin_modal_form_tab_close_settings', 'emodal_admin_modal_form_tab_close_settings_overlay_click', 10);
function emodal_admin_modal_form_tab_close_settings_overlay_click()
{
	?><tr>
		<th scope="row"><?php _e('Click Overlay to Close', 'easy-modal' );?></th>
		<td>
			<input type="checkbox" value="true" name="modal[meta][close][overlay_click]" id="close_overlay_click" <?php echo get_current_modal('meta.close.overlay_click') ? 'checked="checked" ' : '';?>/>
			<label for="close_overlay_click" class="description"><?php _e('Checking this will cause modal to close when user clicks on overlay.', 'easy-modal' );?></label>
		</td>
	</tr><?php
}


add_action('emodal_admin_modal_form_tab_close_settings', 'emodal_admin_modal_form_tab_close_settings_esc_press', 20);
function emodal_admin_modal_form_tab_close_settings_esc_press()
{
	?><tr>
		<th scope="row"><?php _e('Press ESC to Close', 'easy-modal' );?></th>
		<td>
			<input type="checkbox" value="true" name="modal[meta][close][esc_press]" id="close_esc_press" <?php echo get_current_modal('meta.close.esc_press') ? 'checked="checked" ' : '';?>/>
			<label for="close_esc_press" class="description"><?php _e('Checking this will cause modal to close when user presses ESC key.', 'easy-modal' );?></label>
		</td>
	</tr><?php
}
<?php
add_filter('emodal_admin_modal_form_tabs', 'emodal_admin_modal_form_general_tab', 10);
function emodal_admin_modal_form_general_tab($tabs)
{
	$tabs[] = array( 'id' => 'general', 'label' => __('General', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_modal_form_tab_general', 'emodal_admin_modal_form_general_tab_settings', 10);
function emodal_admin_modal_form_general_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_modal_form_tab_general_settings');?>
		</tbody>
	</table><?php
}

add_action('emodal_admin_modal_form_tab_general_settings', 'emodal_admin_modal_form_general_tab_settings_name', 10);
function emodal_admin_modal_form_general_tab_settings_name()
{
	?><tr>
		<th scope="row">
			<label for="name">
				<?php _e('Name', 'easy-modal' );?>
				<span class="description">(required)</span>
			</label>
		</th>
		<td>
			<input type="text" class="regular-text" name="modal[name]" id="name" value="<?php esc_attr_e(get_current_modal('name'))?>" required/>
		</td>
	</tr><?php
}


add_action('emodal_admin_modal_form_tab_general_settings', 'emodal_admin_modal_form_general_tab_settings_load_type', 20);
function emodal_admin_modal_form_general_tab_settings_load_type()
{
	?><tr>
		<th scope="row">
			<label for="load_type">
				<?php _e('Load Type', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<select name="modal[load_type]" id="load_type">
				<option value="per-page-post"<?php echo !get_current_modal('is_sitewide') ? ' selected="selected"' : '';?>><?php _e('Per Page/Post');?></option>
				<option value="sitewide"<?php echo get_current_modal('is_sitewide') ? ' selected="selected"' : '';?>><?php _e('Load Sitewide');?></option>
			</select>
			<p class="description"><?php _e('Load this modal per page or sitewide. If per page or post, select the modal on the edit page.', 'easy-modal' )?></p>
		</td>
	</tr><?php
}


add_action('emodal_admin_modal_form_tab_general_settings', 'emodal_admin_modal_form_general_tab_settings_title', 30);
function emodal_admin_modal_form_general_tab_settings_title()
{
	?><tr>
		<th scope="row">
			<label for="title">
				<?php _e('Title', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<input type="text" class="regular-text" name="modal[title]" id="title" value="<?php esc_attr_e(get_current_modal('title'))?>"/>
			<p class="description"><?php _e(' The title that appears in the modal window. If you leave this blank, the title will be disabled.', 'easy-modal' )?></p>
		</td>
	</tr><?php
}

add_action('emodal_admin_modal_form_tab_general_settings', 'emodal_admin_modal_form_general_tab_settings_content', 40);
function emodal_admin_modal_form_general_tab_settings_content()
{
	?><tr>
		<th scope="row">
			<label for="content">
				<?php _e('Content', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<?php 
			$settings = array(
				'textarea_name' => 'modal[content]',
				'wpautop' => false,
			);
			if(!function_exists('wp_editor')){
				the_editor(get_current_modal('content'), "content", $settings);
			} else {
				wp_editor(get_current_modal('content'), "content", $settings);
			}?>
			<p class="description"><?php _e('Modal content. Can contain shortcodes.', 'easy-modal' )?></p>
		</td>
	</tr><?php
}
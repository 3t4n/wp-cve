<?php
add_filter('emodal_admin_modal_form_tabs', 'emodal_admin_modal_form_display_tab', 20);
function emodal_admin_modal_form_display_tab($tabs)
{
	$tabs[] = array( 'id' => 'display', 'label' => __('Display Options', 'easy-modal' ) );
	return $tabs;
}


add_action('emodal_admin_modal_form_tab_display', 'emodal_admin_modal_form_display_tab_settings', 20);
function emodal_admin_modal_form_display_tab_settings()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_modal_form_tab_display_settings');?>
		</tbody>
	</table><?php
}


add_action('emodal_admin_modal_form_tab_display_settings', 'emodal_admin_modal_form_tab_display_settings_size', 10);
function emodal_admin_modal_form_tab_display_settings_size()
{
	?><tr>
		<th scope="row">
			<label for="size">
				<?php _e('Size', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<select name="modal[meta][display][size]" id="size">
			<?php foreach(apply_filters('emodal_modal_display_size_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal('meta.display.size') ? ' selected="selected"' : '';?>
					<?php echo $value == '' ? ' class="bold"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Select the size of the modal.', 'easy-modal' )?></p>
		</td>
	</tr><?php
}


add_action('emodal_admin_modal_form_tab_display_settings', 'emodal_admin_modal_form_tab_display_settings_custom_sizes', 20);
function emodal_admin_modal_form_tab_display_settings_custom_sizes()
{
	?><tr class="custom-size-only">
		<th scope="row">
			<label for="custom_width"><?php _e('Width', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="text" value="<?php esc_html_e(get_current_modal('meta.display.custom_width'));?>" size="5" name="modal[meta][display][custom_width]" id="custom_width"/>
			<select name="modal[meta][display][custom_width_unit]" id="custom_width_unit">
			<?php foreach(apply_filters('emodal_size_unit_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal('meta.display.custom_width_unit') ? ' selected="selected"' : '';?>
					<?php echo $value == '' ? ' class="bold"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Set a custom width for the modal.', 'easy-modal' );?></p>
		</td>
	</tr>


	<tr class="custom-size-only">
		<th scope="row"><?php _e('Auto Adjusted Height', 'easy-modal' );?></th>
		<td>
			<input type="checkbox" value="true" name="modal[meta][display][custom_height_auto]" id="custom_height_auto" <?php echo get_current_modal('meta.display.custom_height_auto') ? 'checked="checked" ' : '';?>/>
			<label for="custom_height_auto" class="description"><?php _e('Checking this option will set height to fit the content.', 'easy-modal' );?></label>
		</td>
	</tr>


	<tr class="custom-size-only custom-size-height-only"<?php echo get_current_modal('meta.display.custom_height_auto') ? ' style="display:none"' : '';?>>
		<th scope="row">
			<?php _e('Height', 'easy-modal' );?>
		</th>
		<td>
			<input type="text" value="<?php esc_html_e(get_current_modal('meta.display.custom_height'));?>" size="5" name="modal[meta][display][custom_height]" id="custom_height"/>
			<select name="modal[meta][display][custom_height_unit]" id="custom_height_unit">
			<?php foreach(apply_filters('emodal_size_unit_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal('meta.display.custom_height_unit') ? ' selected="selected"' : '';?>
					<?php echo $value == '' ? ' class="bold"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Set a custom height for the modal.', 'easy-modal' );?></p>
		</td>
	</tr>
	<?php
}

add_action('emodal_admin_modal_form_tab_display_settings', 'emodal_admin_modal_form_tab_display_settings_overlay_disabled', 30);
function emodal_admin_modal_form_tab_display_settings_overlay_disabled()
{
	?><tr>
		<th scope="row"><?php _e('Disable Overlay', 'easy-modal' );?></th>
		<td>
			<input type="checkbox" value="true" name="modal[meta][display][overlay_disabled]" id="overlay_disabled" <?php echo get_current_modal('meta.display.overlay_disabled') ? 'checked="checked" ' : '';?>/>
			<label for="overlay_disabled" class="description"><?php _e('Checking this will disable and hide the overlay for this modal.', 'easy-modal' );?></label>
		</td>
	</tr><?php
}

add_action('emodal_admin_modal_form_tab_display_settings', 'emodal_admin_modal_form_tab_display_settings_animation', 40);
function emodal_admin_modal_form_tab_display_settings_animation()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Animation', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="animation_type">
				<?php _e('Animation Type', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<select name="modal[meta][display][animation][type]" id="animation_type">
			<?php foreach(apply_filters('emodal_modal_display_animation_type_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal('meta.display.animation.type') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Select an animation type for your modal.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="animation-speed">
		<th scope="row">
			<label for="animation_speed">
				<?php _e('Animation Speed', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<input type="range" name="modal[meta][display][animation][speed]" id="animation_speed" step="10" value="<?php esc_attr_e(get_current_modal('meta.display.animation.speed'))?>" min="<?php esc_html_e(apply_filters('emodal_admin_modal_min_animation_speed', 50));?>" max="<?php esc_html_e(apply_filters('emodal_admin_modal_max_animation_speed', 1000));?>"/>
			<span class="range-value regular-text"><?php esc_html_e(get_current_modal('meta.display.animation.speed'));?>ms</span>
			<p class="description"><?php _e('Set the animation speed for the modal.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="animation-origin">
		<th scope="row">
			<label for="animation_origin">
				<?php _e('Animation Origin', 'easy-modal' );?>
			</label>
		</th>
		<td>
			<select name="modal[meta][display][animation][origin]" id="animation_origin">
			<?php foreach(apply_filters('emodal_modal_display_animation_origin_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal('meta.display.animation.origin') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Choose where the animation will begin.', 'easy-modal' )?></p>
		</td>
	</tr>
<?php
}


add_action('emodal_admin_modal_form_tab_display_settings', 'emodal_admin_modal_form_display_tab_settings_position', 50);
function emodal_admin_modal_form_display_tab_settings_position()
{
	?><tr class="title-divider">
		<th colspan="2"><h3 class="title"><?php _e('Position', 'easy-modal' );?></h3></th>
	</tr>
	<tr>
		<th scope="row">
			<label for="display_location"><?php _e('Location', 'easy-modal' );?></label>
		</th>
		<td>
			<select name="modal[meta][display][location]" id="display_location">
			<?php foreach(apply_filters('emodal_modal_display_location_options', array()) as $option => $value) : ?>
				<option
					value="<?php echo $value;?>"
					<?php echo $value == get_current_modal('meta.display.location') ? ' selected="selected"' : '';?>
				><?php echo $option;?></option>
			<?php endforeach ?>
			</select>
			<p class="description"><?php _e('Choose which corner the close button will be positioned.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e('Fixed Postioning', 'easy-modal' );?></th>
		<td>
			<input type="checkbox" value="true" name="modal[meta][display][position][fixed]" id="display_position_fixed" <?php echo get_current_modal('meta.display.position.fixed') ? 'checked="checked" ' : '';?>/>
			<label for="display_position_fixed" class="description"><?php _e('Checking this sets the positioning of the modal to fixed.', 'easy-modal' );?></label>
		</td>
	</tr>
	<tr class="top">
		<th scope="row">
			<label for="display_position_top"><?php _e('Top', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="modal[meta][display][position][top]" id="display_position_top" min="0" max="500" step="1" value="<?php esc_attr_e(get_current_modal('meta.display.position.top'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal('meta.display.position.top'));?></span>px</span>
			<p class="description"><?php _e('Distance from the top edge of the screen.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="bottom">
		<th scope="row">
			<label for="display_position_bottom"><?php _e('Bottom', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="modal[meta][display][position][bottom]" id="display_position_bottom" min="0" max="500" step="1" value="<?php esc_attr_e(get_current_modal('meta.display.position.bottom'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal('meta.display.position.bottom'));?></span>px</span>
			<p class="description"><?php _e('Distance from the bottom edge of the screen.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="left">
		<th scope="row">
			<label for="display_position_left"><?php _e('Left', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="modal[meta][display][position][left]" id="display_position_left" min="0" max="500" step="1" value="<?php esc_attr_e(get_current_modal('meta.display.position.left'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal('meta.display.position.left'));?></span>px</span>
			<p class="description"><?php _e('Distance from the left edge of the screen.', 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="right">
		<th scope="row">
			<label for="display_position_right"><?php _e('Right', 'easy-modal' );?></label> 
		</th>
		<td>
			<input type="range" name="modal[meta][display][position][right]" id="display_position_right" min="0" max="500" step="1" value="<?php esc_attr_e(get_current_modal('meta.display.position.right'))?>" />
			<span class="range-value regular-text"><span class="value"><?php esc_html_e(get_current_modal('meta.display.position.right'));?></span>px</span>
			<p class="description"><?php _e('Distance from the right edge of the screen.', 'easy-modal' )?></p>
		</td>
	</tr><?php
}
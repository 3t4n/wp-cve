<?php
global $post;
$options = new MaxGalleryOptions($post->ID);

$description_positions = array(
	esc_html__('Above Gallery', 'maxgalleria') => 'above',
	esc_html__('Below Gallery', 'maxgalleria') => 'below'
);
?>

<div class="meta-options">
	<table>
		<tr>
			<td width="80">
				<label for="<?php echo esc_attr($options->description_enabled_key) ?>"><?php esc_html_e('Enabled:', 'maxgalleria') ?></label>
			</td>
			<td>
				<input type="checkbox" id="<?php echo esc_attr($options->description_enabled_key) ?>" name="<?php echo esc_attr($options->description_enabled_key) ?>" <?php echo esc_attr(($options->get_description_enabled() == 'on') ? 'checked' : '') ?> />
			</td>
		</tr>
		<tr>
			<td width="80">
				<?php esc_html_e('Location:', 'maxgalleria') ?>
			</td>
			<td>
				<select id="<?php echo esc_attr($options->description_position_key) ?>" name="<?php echo esc_attr($options->description_position_key) ?>">
				<?php foreach ($description_positions as $name => $value) { ?>
					<?php $selected = ($options->get_description_position() == $value) ? 'selected=selected' : ''; ?>
					<option value="<?php echo esc_attr($value) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($name) ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top: 10px;">
				<label for="<?php echo esc_attr($options->description_text_key) ?>"><?php esc_html_e('Text:', 'maxgalleria') ?></label>
				<div style="vertical-align: middle; display: inline-block; color: #808080; font-style: italic; margin-left: 20px;"><?php esc_html_e('HTML is allowed', 'maxgalleria') ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="<?php echo esc_attr($options->description_text_key) ?>" name="<?php echo esc_attr($options->description_text_key) ?>"><?php echo esc_textarea($options->get_description_text()) ?></textarea>
			</td>
		</tr>
	</table>
</div>

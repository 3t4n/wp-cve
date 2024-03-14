<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html($data['title']); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">

		<ul class="wc-szamlazz-settings-radio-group">
		<?php foreach ($data['options'] as $option_id => $option): ?>
			<li>
				<label>
					<input <?php disabled( $data['disabled'] ); ?> type="radio" name="<?php echo esc_attr($this->get_field_key( $key )); ?>" value="<?php echo esc_attr($option_id); ?>" <?php checked($option_id, $this->get_option( $key )); ?>  />
					<?php echo esc_html($option); ?>
				</label>
			</li>
		<?php endforeach; ?>
		</ul>

		<p class="description"><?php echo esc_html($data['description']); ?></p>
	</td>
</tr>

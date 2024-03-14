<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$saved_value = $this->get_option($key);

if($key == 'ipn_close_order' && !$saved_value && $this->get_option('ipn_close_order')) {
	$saved_value = 'wc-completed';
}

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html($data['title']); ?></th>
	<td class="forminp">

		<ul class="wc-szamlazz-settings-checkbox-group">
			<li>
				<label>
					<input <?php disabled( $data['disabled'] ); ?> type="radio" name="woocommerce_wc_szamlazz_<?php echo esc_attr($key); ?>" value="no" <?php checked('no', $saved_value); ?>  />
					<?php _e('Do not change status', 'wc-szamlazz'); ?>
				</label>
			</li>
			<?php foreach ($data['options'] as $option_id => $option): ?>
				<li>
					<label>
						<input <?php disabled( $data['disabled'] ); ?> type="radio" name="woocommerce_wc_szamlazz_<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($option_id); ?>" <?php checked($option_id, $saved_value); ?>  />
						<?php echo esc_html($option); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>

		<p class="description"><?php echo esc_html($data['description']); ?></p>
	</td>
</tr>

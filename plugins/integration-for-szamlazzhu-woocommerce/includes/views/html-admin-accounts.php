<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$saved_values = get_option('wc_szamlazz_extra_accounts');
$product_categories = array();
foreach (get_terms(array('taxonomy' => 'product_cat')) as $category) {
	$product_categories['product_cat_'.$category->term_id] = $category->name;
}
$condition_select_values = apply_filters('wc_szamlazz_account_conditions', array(
	array(
		"label" => __('Payment method', 'wc-szamlazz'),
		"options" => $this->get_payment_methods()
	),
	array(
		"label" => __('Shipping method', 'wc-szamlazz'),
		"options" => $this->get_shipping_methods()
	),
	array(
		"label" => __('Currency', 'wc-szamlazz'),
		'options' => array(
			'HUF' => __( 'Forint', 'wc-szamlazz' ),
			'EUR' => __( 'Euro', 'wc-szamlazz' ),
			'USD' => __( 'US dollars', 'wc-szamlazz' ),
		)
	),
	array(
		"label" => __('Order type', 'wc-szamlazz'),
		"options" => array(
			'order-individual' => __('Individual', 'wc-szamlazz'),
			'order-company' => __('Company', 'wc-szamlazz')
		)
	),
	array(
		"label" => __('Product category', 'wc-szamlazz'),
		"options" => $product_categories
  )
));
?>

<tr valign="top">
	<th scope="row" class="titledesc"></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings–inline-table-scroll border">
			<table class="wc-szamlazz-settings–inline-table wc-szamlazz-settings–inline-table-accounts">
				<thead>
					<tr>
						<th><?php _e('Account name', 'wc-szamlazz'); ?></th>
						<th><?php _e('Agent key', 'wc-szamlazz'); ?></th>
						<th><?php _e('Condition', 'wc-szamlazz'); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if($saved_values): ?>
						<?php foreach ( $saved_values as $account_id => $account ): ?>
							<tr>
								<td>
									<input type="text" placeholder="<?php _e('Can be anything', 'wc-szamlazz'); ?>" data-name="wc_szamlazz_additional_accounts[X][name]" name="wc_szamlazz_additional_accounts[<?php echo esc_attr( $account_id ); ?>][name]" value="<?php echo esc_attr($account['name']); ?>" />
								</td>
								<td>
									<input type="text" placeholder="<?php _e('42 character key', 'wc-szamlazz'); ?>" data-name="wc_szamlazz_additional_accounts[X][key]" name="wc_szamlazz_additional_accounts[<?php echo esc_attr( $account_id ); ?>][key]" value="<?php echo esc_attr($account['key']); ?>" />
								</td>
								<td>
									<select data-name="wc_szamlazz_additional_accounts[X][condition]" name="wc_szamlazz_additional_accounts[<?php echo esc_attr( $account_id ); ?>][condition]" <?php if(empty($account['condition'])): ?>class="placeholder"<?php endif; ?>>
										<option value=""><?php _e('Not conditional', 'wc-szamlazz'); ?></option>
										<?php foreach ($condition_select_values as $option_group): ?>
											<optgroup label="<?php echo esc_attr($option_group['label']); ?>">
												<?php foreach ($option_group['options'] as $option_id => $option_label): ?>
													<option value="<?php echo esc_attr($option_id); ?>" <?php selected( $account['condition'], $option_id ); ?>><?php echo esc_html($option_label); ?></option>
												<?php endforeach; ?>
											</optgroup>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<a href="#" class="delete-row"><span class="dashicons dashicons-dismiss"></span></a>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">
							<a href="#"><span class="dashicons dashicons-plus-alt"></span> <span><?php _e('Add a new account', 'wc-szamlazz'); ?></span></a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

<script type="text/html" id="wc_szamlazz_additional_accounts_sample_row">
	<tr>
		<td>
			<input type="text" placeholder="<?php _e('Can be anything', 'wc-szamlazz'); ?>" data-name="wc_szamlazz_additional_accounts[X][name]" />
		</td>
		<td>
			<input type="text" placeholder="<?php _e('42 character key', 'wc-szamlazz'); ?>" data-name="wc_szamlazz_additional_accounts[X][key]" />
		</td>
		<td>
			<select data-name="wc_szamlazz_additional_accounts[X][condition]" class="placeholder">
				<option value="" selected><?php _e('Not conditional', 'wc-szamlazz'); ?></option>
				<?php foreach ($condition_select_values as $option_group): ?>
					<optgroup label="<?php echo esc_attr($option_group['label']); ?>">
						<?php foreach ($option_group['options'] as $option_id => $option_label): ?>
							<option value="<?php echo esc_attr($option_id); ?>"><?php echo esc_html($option_label); ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
		</td>
		<td>
			<a href="#" class="delete-row"><span class="dashicons dashicons-dismiss"></span></a>
		</td>
	</tr>
</script>

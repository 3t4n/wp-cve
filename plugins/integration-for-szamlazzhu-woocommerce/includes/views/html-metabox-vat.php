<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vat_number = $order->get_meta('wc_szamlazz_adoszam');
if($order->get_meta('_billing_wc_szamlazz_adoszam')) $vat_number = $order->get_meta('_billing_wc_szamlazz_adoszam');

?>
	<table>
		<?php if($vat_number_data['valid'] === 'unknown'): ?>
			<tr>
				<th><?php esc_html_e('VAT number', 'wc-szamlazz'); ?></th>
				<td><?php echo esc_html($vat_number); ?> <span class="dashicons dashicons-warning"></span></td>
			</tr>
			<tr><td colspan="2"><small><?php esc_html_e('When creating the order, it was not possible to retrieve the tax number data from Számlázz.hu.', 'wc-szamlazz'); ?></small></td></tr>
		<?php else: ?>
			<tr>
				<th><?php esc_html_e('VAT number', 'wc-szamlazz'); ?></th>
				<td><?php echo esc_html($vat_number); ?> <span class="dashicons dashicons-yes"></span></td>
			</tr>
		<?php endif; ?>

		<?php if(array_key_exists('address',$vat_number_data)): ?>
			<tr>
				<th><?php esc_html_e('Name', 'wc-szamlazz'); ?></th>
				<td><?php echo esc_html($vat_number_data['name']); ?></td>
			</tr>
			<?php $wc_szamlazz_labels = array(
				"countryCode" => esc_html__('Country code', 'wc-szamlazz'),
				"postalCode" => esc_html__('Postcode', 'wc-szamlazz'),
				"city" => esc_html__('City', 'wc-szamlazz'),
				"streetName" => esc_html__('Street', 'wc-szamlazz'),
				"publicPlaceCategory" => esc_html__('Street type', 'wc-szamlazz'),
				"number" => esc_html__('Number', 'wc-szamlazz'),
				"building" => esc_html__('Building', 'wc-szamlazz'),
				"staircase" => esc_html__('Staircase', 'wc-szamlazz'),
				"floor" => esc_html__('Floor', 'wc-szamlazz'),
				"door" => esc_html__('Door', 'wc-szamlazz')
			);
			?>
			<?php foreach ($wc_szamlazz_labels as $field => $label): ?>
				<?php if($vat_number_data['address'][$field]): ?>
					<tr>
						<th><?php echo $label; ?></th>
						<td><?php echo esc_html($vat_number_data['address'][$field]); ?></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			<tr><td colspan="2"><small><?php esc_html_e('The data was retrieved from the NAV database.', 'wc-szamlazz'); ?></small></td></tr>
		<?php endif; ?>

		<?php if(array_key_exists('vies',$vat_number_data)): ?>
			<tr>
				<th><?php esc_html_e('Name', 'wc-szamlazz'); ?></th>
				<td><?php echo esc_html($vat_number_data['name']); ?></td>
			</tr>
			<?php $wc_szamlazz_labels = array(
				"requestDate" => esc_html__('Request date', 'wc-szamlazz'),
				"address" => esc_html__('Address', 'wc-szamlazz'),
			);
			?>
			<?php foreach ($wc_szamlazz_labels as $field => $label): ?>
				<?php if($vat_number_data['vies'][$field]): ?>
					<tr>
						<th><?php echo $label; ?></th>
						<td><?php echo esc_html($vat_number_data['vies'][$field]); ?></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			<tr><td colspan="2"><small><?php esc_html_e('The data was retrieved from the VIES database.', 'wc-szamlazz'); ?></small></td></tr>
		<?php endif; ?>
	</table>

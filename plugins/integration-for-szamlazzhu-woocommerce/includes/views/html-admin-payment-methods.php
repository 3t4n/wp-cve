<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$saved_values = get_option('wc_szamlazz_payment_method_options_v2');
?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html( $data['title'] ); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings–inline-table-scroll">
			<table class="wc-szamlazz-settings–inline-table wc-szamlazz-settings–inline-table-payment-methods">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Payment method', 'wc-szamlazz' ); ?></th>
						<th class="wc-szamlazz-toggle-group-automation-cell-hide"><?php esc_html_e( 'Payment deadline(days)', 'wc-szamlazz' ); ?></th>
						<th class="wc-szamlazz-toggle-group-automation-cell-hide"><?php esc_html_e( 'Mark as paid', 'wc-szamlazz' ); ?> <?php if($data['disabled']): ?><i class="wc_szamlazz_pro_label">PRO</i><?php endif; ?></th>
						<th class="wc-szamlazz-toggle-group-automation-cell-hide"><?php esc_html_e( 'Proforma invoice', 'wc-szamlazz' ); ?> <?php if($data['disabled']): ?><i class="wc_szamlazz_pro_label">PRO</i><?php endif; ?></th>
						<th class="wc-szamlazz-toggle-group-automation-cell-hide"><?php esc_html_e( 'Deposit invoice', 'wc-szamlazz' ); ?> <?php if($data['disabled']): ?><i class="wc_szamlazz_pro_label">PRO</i><?php endif; ?></th>
						<th><?php esc_html_e( 'Name on the invoice', 'wc-szamlazz' ); ?> <?php if($data['disabled']): ?><i class="wc_szamlazz_pro_label">PRO</i><?php endif; ?></th>
						<th><?php esc_html_e( 'DO NOT generate automatically', 'wc-szamlazz' ); ?> <?php if($data['disabled']): ?><i class="wc_szamlazz_pro_label">PRO</i><?php endif; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $this->get_payment_methods() as $payment_method_id => $payment_method ): ?>
						<?php
						if($saved_values && isset($saved_values[esc_attr( $payment_method_id )])) {
							$value_deadline = esc_attr( $saved_values[esc_attr( $payment_method_id )]['deadline']);
							$value_complete = $saved_values[esc_attr( $payment_method_id )]['complete'];
							$value_proform = $saved_values[esc_attr( $payment_method_id )]['proform'];
							$value_deposit = $saved_values[esc_attr( $payment_method_id )]['deposit'];
							$value_name = '';
							if(isset($saved_values[esc_attr( $payment_method_id )]['name'])) {
								$value_name = esc_attr( $saved_values[esc_attr( $payment_method_id )]['name']);
							}

							$value_auto_disabled = false;
							if(isset($saved_values[esc_attr( $payment_method_id )]['auto_disabled'])) {
								$value_auto_disabled = $saved_values[esc_attr( $payment_method_id )]['auto_disabled'];
							}

						} else {
							$value_deadline = '';
							$value_complete = false;
							$value_proform = false;
							$value_deposit = false;
							$value_name = '';
							$value_auto_disabled = false;
						}
						?>
						<tr>
							<td class="label"><strong><?php echo esc_html($payment_method); ?></strong></td>
							<td class="wc-szamlazz-toggle-group-automation-cell-hide"><input type="number" name="wc_szamlazz_payment_options[<?php echo esc_attr( $payment_method_id ); ?>][deadline]" value="<?php echo $value_deadline; ?>" placeholder="<?php echo esc_attr($this->get_option('payment_deadline')); ?>" /></td>
							<td class="wc-szamlazz-toggle-group-automation-cell-hide cb"><input <?php disabled( $data['disabled'] ); ?> type="checkbox" name="wc_szamlazz_payment_options[<?php echo esc_attr( $payment_method_id ); ?>][complete]" value="1" <?php checked( $value_complete ); ?> /></td>
							<td class="wc-szamlazz-toggle-group-automation-cell-hide cb"><input <?php disabled( $data['disabled'] ); ?> type="checkbox" name="wc_szamlazz_payment_options[<?php echo esc_attr( $payment_method_id ); ?>][proform]" value="1" <?php checked( $value_proform ); ?> /></td>
							<td class="wc-szamlazz-toggle-group-automation-cell-hide cb"><input <?php disabled( $data['disabled'] ); ?> type="checkbox" name="wc_szamlazz_payment_options[<?php echo esc_attr( $payment_method_id ); ?>][deposit]" value="1" <?php checked( $value_deposit ); ?> /></td>
							<td><input type="text" name="wc_szamlazz_payment_options[<?php echo esc_attr( $payment_method_id ); ?>][name]" value="<?php echo $value_name; ?>" placeholder="<?php echo esc_attr($payment_method); ?>" /></td>
							<td class="cb"><input <?php disabled( $data['disabled'] ); ?> type="checkbox" name="wc_szamlazz_payment_options[<?php echo esc_attr( $payment_method_id ); ?>][auto_disabled]" value="1" <?php checked( $value_auto_disabled ); ?> /></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

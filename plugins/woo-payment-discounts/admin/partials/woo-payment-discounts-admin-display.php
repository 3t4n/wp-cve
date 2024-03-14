<?php
/**
 * Administration page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( isset( $_POST['wpd_submit'] ) ) {
	$settings_arr = array();
	$flag         = false;
	if ( isset( $_POST['woo_payment_discounts'] ) && ! empty( $_POST['woo_payment_discounts'] ) ) {
		foreach ( $_POST['woo_payment_discounts'] as $v ) {
			if ( $v['amount'] < 0 ) {
				$flag = true;
				break;
			}
		}
		if ( $flag == true ) { ?>
			<div class="notice error my-acf-notice is-dismissible">
				<p><?php _e( 'Enter positive value only', 'woo-payment-discounts' ); ?></p>
			</div>
		<?php } else {
			$settings_arr = maybe_serialize( $_POST['woo_payment_discounts'] );
			update_option( 'woo_payment_discounts_setting', $settings_arr );
			$flag = false;
		}
	} ?>


<?php }
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>

	<form method="post" action="">
		<?php settings_fields( 'woo_payment_discounts_group' ); ?>
		<h3><?php _e( 'Payment Methods', 'woo-payment-discounts' ); ?></h3>
		<p><?php _e( 'Enter an amount for each payment gateway.', 'woo-payment-discounts' ); ?><br/></p>
		<table class="form-table" style="max-width:50%;">
			<tbody>
			<tr valign="top">
				<th scope="row" style="padding: 15px 10px;"> Payment Gateway Name</th>
				<th scope="row" style="padding: 15px 10px;"> Amount</th>
				<th scope="row" style="padding: 15px 10px;"> Discount Type</th>
			</tr>
			<?php
			$getsettings = array();
			$getsettings = get_option( 'woo_payment_discounts_setting' );
			$getsettings = maybe_unserialize( $getsettings );
			foreach ( $payment_gateways as $gateway ) :
				$current = isset( $getsettings[ $gateway->id ]['amount'] ) ? $getsettings[ $gateway->id ]['amount'] : '0';
				?>
				<tr valign="top">
					<th scope="row"><label for="woo_payment_discounts_<?php echo esc_attr( $gateway->id ); ?>"><?php echo esc_attr( $gateway->title ); ?></label></th>
					<td>
						<input type="text" class="input-text regular-input" value="<?php echo esc_attr( $current ); ?>" id="woo_payment_discounts_<?php echo esc_attr( $gateway->id ); ?>" name="woo_payment_discounts[<?php echo esc_attr( $gateway->id ); ?>][amount]"/>
					</td>
					<td>
						<select id="woo_payment_discounts_type" name="woo_payment_discounts[<?php echo esc_attr( $gateway->id ); ?>][type]">
							<option value="fixed" <?php if ( isset( $getsettings[ $gateway->id ]['type'] ) && $getsettings[ $gateway->id ]['type'] == "fixed" ) {
								echo 'selected="selected"';
							} ?> >Fixed
							</option>
							<option value="percentage" <?php if ( isset( $getsettings[ $gateway->id ]['type'] ) && $getsettings[ $gateway->id ]['type'] == "percentage" ) {
								echo 'selected="selected"';
							} ?> >Percentage(%)
							</option>
						</select>

					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="wpd_submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>
<?php
/**
 * Settings page rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>

<div class="wrap" id="<?php echo esc_html( Branding::$branding_short ); ?>-settings">
	<h1><?php echo esc_html( Branding::$company_name ); ?> Connect</h1>

	<form method="post" action="options.php">
		<h2>1. <?php esc_html_e( 'Order statuses settings', 'boxtal-connect' ); ?></h2>
		<?php settings_fields( Branding::$branding . '-connect-settings-group' ); ?>
		<?php do_settings_sections( Branding::$branding . '-connect-settings-group' ); ?>
		<table class="form-table states">
			<tr valign="top">
				<td scope="row" class="titledesc">
					<label for="order_shipped"><?php esc_html_e( 'When the shipment is picked up by the carrier, then change its status to', 'boxtal-connect' ); ?></label>
				</td>
				<td>
					<select name="<?php echo esc_html( strtoupper( Branding::$branding_short ) ); ?>_ORDER_SHIPPED">
						<option value="none"
						<?php
						if ( null === get_option( strtoupper( Branding::$branding_short ) . '_ORDER_SHIPPED' ) ) {
							echo 'selected';}
						?>
						>
							<?php esc_html_e( 'No status associated', 'boxtal-connect' ); ?>
						</option>
						<?php
						foreach ( $order_statuses as $order_status => $translation ) {
							echo '<option value="' . esc_html( $order_status ) . '" ';
							if ( get_option( strtoupper( Branding::$branding_short ) . '_ORDER_SHIPPED' ) === $order_status ) {
								echo 'selected="selected"';
							}
							//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
							echo '>' . esc_html( __( $translation, 'woocommerce' ) ) . '</option>';
						}
						?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<td scope="row" class="titledesc">
					<label for="order_delivered"><?php esc_html_e( 'When the shipment is delivered by the carrier, then change its status to', 'boxtal-connect' ); ?></label>
				</td>
				<td>
					<select name="<?php echo esc_html( strtoupper( Branding::$branding_short ) ); ?>_ORDER_DELIVERED" id="order-delivered">
						<option value="none"
							<?php
							if ( null === get_option( strtoupper( Branding::$branding_short ) . '_ORDER_DELIVERED' ) ) {
								echo 'selected="selected"';}
							?>
						>
							<?php esc_html_e( 'No status associated', 'boxtal-connect' ); ?>
						</option>
						<?php
						foreach ( $order_statuses as $order_status => $translation ) {
							echo '<option value="' . esc_html( $order_status ) . '" ';
							if ( get_option( strtoupper( Branding::$branding_short ) . '_ORDER_DELIVERED' ) === $order_status ) {
								echo 'selected';
							}
							//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
							echo '>' . esc_html( __( $translation, 'woocommerce' ) ) . '</option>';
						}
						?>
					</select>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>

	<?php if ( null !== $tuto_url ) { ?>
	<h2>2. <?php esc_html_e( 'Shipping methods settings', 'boxtal-connect' ); ?></h2>

	<table class="form-table">
		<tr valign="top">
			<td scope="row" class="titledesc large">
				<label for="order_shipped"><?php esc_html_e( 'Just one last step, it will only take a few minutes, let us guide you: ', 'boxtal-connect' ); ?></label>
			</td>
		</tr>
	</table>
	<p class="submit">
		<a href="<?php echo esc_url( $tuto_url ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Go to the tutorial', 'boxtal-connect' ); ?></a>
	</p>
	<?php } ?>
</div>

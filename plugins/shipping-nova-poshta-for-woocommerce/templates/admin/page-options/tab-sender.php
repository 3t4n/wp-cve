<?php
/**
 * Sender settings
 *
 * @package NovaPosta\Templates\Admin
 *
 * @var string   $tab_label            Current tab label.
 * @var Settings $settings             Settings.
 * @var string   $current_city_id      Current city ID.
 * @var string   $current_city         Current city name.
 * @var string   $current_warehouse_id Current warehouse ID.
 * @var array    $warehouses           List of warehouses for the current city.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use NovaPoshta\Main;
use NovaPoshta\Settings\Settings;

?>
<h2><?php echo esc_html( $tab_label ); ?></h2>
<?php if ( ! $settings->api_key() ) { ?>

	<p>
		<span class="shipping-nova-poshta-for-woocommerce-alert shipping-nova-poshta-for-woocommerce-alert--error">
			<?php esc_html_e( 'Firstly you need to fill in the API key for the Nova Poshta.', 'shipping-nova-poshta-for-woocommerce' ); ?>
		</span>
	</p>
	<p class="submit">
		<?php
		printf(
			'<a href="%s" class="button button-primary">%s</a>',
			esc_url( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) ),
			esc_html__( 'Go to API key field', 'shipping-nova-poshta-for-woocommerce' )
		);
		?>
	</p>

	<?php
	return;
}
?>
<form action="options.php" method="POST" class="shipping-nova-poshta-for-woocommerce-form">
	<?php settings_errors( Main::PLUGIN_SLUG ); ?>
	<?php settings_fields( Main::PLUGIN_SLUG ); ?>

	<div>
		<p>
			<label>
				<?php esc_attr_e( 'Sender Phone', 'shipping-nova-poshta-for-woocommerce' ); ?><br>
				<input
					type="tel"
					name="<?php echo esc_attr( Main::PLUGIN_SLUG ); ?>[phone]"
					placeholder="+380991234567"
					required="required"
					value="<?php echo esc_attr( $settings->phone() ); ?>" />
			</label>
		</p>
		<p>
			<label>
				<?php esc_attr_e( 'Description of your products', 'shipping-nova-poshta-for-woocommerce' ); ?><br>
				<span class="with-help-tip">
						<input
							type="tel"
							name="<?php echo esc_attr( Main::PLUGIN_SLUG ); ?>[description]"
							value="<?php echo esc_attr( $settings->description() ); ?>"
							required="required" />
						<span
							class="help-tip"
							data-tip="<?php esc_attr_e( 'A few words about what you send. For example: toys, shoes, household appliances, etc.', 'shipping-nova-poshta-for-woocommerce' ); ?>"
						></span>
					</span>
			</label>
		</p>
		<p>
			<label>
				<?php esc_attr_e( 'Sender City', 'shipping-nova-poshta-for-woocommerce' ); ?><br>
				<select
					id="shipping_nova_poshta_for_woocommerce_city"
					name="<?php echo esc_attr( Main::PLUGIN_SLUG ); ?>[city_id]"
					required="required"
					data-placeholder="<?php esc_attr_e( 'Sender City', 'shipping-nova-poshta-for-woocommerce' ); ?>"
				>
					<option
						value="<?php echo esc_attr( $current_city_id ); ?>"><?php echo esc_attr( $current_city ); ?></option>
				</select>
			</label>
		</p>
		<p>
			<label>
				<?php esc_attr_e( 'Sender Warehouse', 'shipping-nova-poshta-for-woocommerce' ); ?><br>
				<select
					id="shipping_nova_poshta_for_woocommerce_warehouse"
					name="<?php echo esc_attr( Main::PLUGIN_SLUG ); ?>[warehouse_id]"
					required="required"
					data-placeholder="<?php esc_attr_e( 'Sender Warehouse', 'shipping-nova-poshta-for-woocommerce' ); ?>"
				>
					<?php foreach ( $warehouses as $warehouse_id => $name ) { ?>
						<option
							<?php selected( $warehouse_id, $current_warehouse_id ); ?>
							value="<?php echo esc_attr( $warehouse_id ); ?>"
						><?php echo esc_attr( $name ); ?></option>
					<?php } ?>
				</select>
			</label>
		</p>
	</div>
	<?php submit_button(); ?>
</form>

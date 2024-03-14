<?php
/**
 * License form.
 *
 * @package NovaPosta\Templates\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( defined( 'NOVA_POSHTA_API_KEY' ) ) {
	return;
}

use NovaPoshta\Main;

$license_action   = add_query_arg(
	[
		'page' => Main::PLUGIN_SLUG,
	],
	admin_url( 'admin.php' )
);
$license_key      = get_option( Main::PLUGIN_SLUG . '-license', '' );
$method           = empty( $license_key ) ? 'activate' : 'deactivate';
$is_pro           = nova_poshta()->is_pro();
$button_classes   = [ 'button' ];
$button_classes[] = 'activate' === $method ? 'button-primary' : 'button-default';
$button_labels    = [
	'activate'   => esc_html__( 'Become Pro', 'shipping-nova-poshta-for-woocommerce' ),
	'deactivate' => esc_html__( 'Deactivate Key', 'shipping-nova-poshta-for-woocommerce' ),
	'upgrade'    => esc_html__( 'Upgrade Plugin', 'shipping-nova-poshta-for-woocommerce' ),
];
?>

<h2><?php esc_html_e( 'License', 'shipping-nova-poshta-for-woocommerce' ); ?></h2>
<form action="<?php echo esc_url( $license_action ); ?>" method="POST" class="shipping-nova-poshta-for-woocommerce-form shipping-nova-poshta-for-woocommerce-form--license">
	<?php wp_nonce_field( Main::PLUGIN_SLUG . '-license' ); ?>
	<div>
		<p><?php esc_html_e( 'You\'re using Shipping Nova Poshta for WooCommerce - no license needed. Enjoy!', 'shipping-nova-poshta-for-woocommerce' ); ?></p>
	</div>
	<div>
		<input type="password" name="license_key" value="<?php echo esc_attr( $license_key ); ?>" <?php echo 'deactivate' === $method ? 'readonly' : ''; ?>>
		<?php if ( 'activate' !== $method && ! $is_pro ) { ?>
			<button
				type="submit"
				name="method"
				value="upgrade"
				class="button button-primary">
				<?php echo esc_html( $button_labels['upgrade'] ); ?>
			</button>
		<?php } ?>
		<button
			type="submit"
			name="method"
			value="<?php echo esc_attr( $method ); ?>"
			class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>">
			<?php echo esc_html( $button_labels[ $method ] ); ?>
		</button>
	</div>
	<?php if ( ! $is_pro ) { ?>
		<div>
			<p>
				<?php
				echo wp_kses(
					sprintf( /* translators: %1$s - link to marketing site, %2$s - discount in percent */
						__( 'To unlock more features, consider <a href="%1$s" target="_blank">upgrading to Pro</a>. As a valued user you receive %2$s off, automatically applied at checkout!', 'shipping-nova-poshta-for-woocommerce' ),
						'https://wp-unit.com/product/nova-poshta-pro/',
						'50%'
					),
					[
						'a' => [
							'href'   => true,
							'target' => true,
						],
					]
				);
				?>
			</p>
		</div>
	<?php } ?>
</form>

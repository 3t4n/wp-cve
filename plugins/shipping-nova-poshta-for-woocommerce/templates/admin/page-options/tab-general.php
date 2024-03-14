<?php
/**
 * General settings
 *
 * @package NovaPosta\Templates\Admin
 *
 * @var string   $tab_label Current tab label.
 * @var Settings $settings  Settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use NovaPoshta\Main;
use NovaPoshta\Settings\Settings;

?>
<h1 style="display: none"></h1>
<div class="shipping-nova-poshta-for-woocommerce-row">
	<div class="shipping-nova-poshta-for-woocommerce-column">
		<?php require_once __DIR__ . '/license-form.php'; ?>
		<h2><?php echo esc_html( $tab_label ); ?></h2>
		<form action="options.php" method="POST" class="shipping-nova-poshta-for-woocommerce-form">
			<?php settings_errors( Main::PLUGIN_SLUG ); ?>
			<?php settings_fields( Main::PLUGIN_SLUG ); ?>
			<p>
				<label><?php esc_attr_e( 'API key', 'shipping-nova-poshta-for-woocommerce' ); ?><br>
					<input
						type="text"
						name="<?php echo esc_attr( Main::PLUGIN_SLUG ); ?>[api_key]"
						value="<?php echo esc_attr( $settings->api_key() ); ?>" />
				</label>
				<?php
				echo wp_kses_post(
					sprintf( /* translators: 1: Link on Nova Poshta personal account */
						__(
							'If you do not have an API key, then you can get it in the <a href="%1$s" target="_blank">personal account of Nova Poshta</a>. Unfortunately, without the API key, the plugin will not work :(. Also read: <a href="%2$s">How to get Nova Poshta API key and connect our plugin</a>.',
							'shipping-nova-poshta-for-woocommerce'
						),
						'https://new.novaposhta.ua/dashboard/settings/developers',
						'https://wp-unit.com/uk/yak-otrymaty-api-klyuch-dlya-novoyi-poshty-ta-pidklyuchyty-jogo-do-nashogo-plaginu/'
					)
				);
				?>
			</p>
			<p>
				<label><?php esc_html_e( 'The place for fields on the checkout page', 'shipping-nova-poshta-for-woocommerce' ); ?>
					<select name="<?php echo esc_attr( Main::PLUGIN_SLUG ); ?>[place_for_fields]">
						<option value="billing" <?php selected( 'billing', $settings->place_for_fields() ); ?>><?php esc_html_e( 'Inside billing fields', 'shipping-nova-poshta-for-woocommerce' ); ?></option>
						<option value="shipping_method" <?php selected( 'shipping_method', $settings->place_for_fields() ); ?>><?php esc_html_e( 'Under shipping method', 'shipping-nova-poshta-for-woocommerce' ); ?></option>
					</select>
				</label>
			</p>
			<?php submit_button(); ?>
		</form>
	</div>
	<div class="shipping-nova-poshta-for-woocommerce-column">
		<?php require NOVA_POSHTA_PATH . 'templates/admin/page-options/quick-references.php'; ?>
	</div>
</div>

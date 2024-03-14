<?php
/**
 * Advertisement messages for customers.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Notice;

use NovaPoshta\Main;

/**
 * Class Advertisement
 *
 * @package NovaPoshta\Admin
 */
class Advertisement extends Notice {

	/**
	 * Init hooks.
	 */
	public function hooks() {

		add_action( 'admin_notices', [ $this, 'notices' ] );
		add_action( 'wp_ajax_shipping_nova_poshta_for_woocommerce_notice', [ $this, 'close' ] );
	}

	/**
	 * Show advertisement
	 */
	public function notices() {

		global $current_screen;
		if ( 0 !== strpos( $current_screen->base, 'toplevel_page_' . Main::PLUGIN_SLUG ) ) {
			return;
		}
		if ( $this->transient_cache->get( 'advertisement' ) ) {
			return;
		}
		$advertisement = $this->get_advertisement();
		$this->show(
			'info',
			$advertisement['message'],
			$advertisement['btn_label'],
			$advertisement['btn_url']
		);
	}

	/**
	 * Get random advertisement
	 *
	 * @return array
	 */
	private function get_advertisement(): array {

		$advertisements = [
			[
				'message'   =>
					wp_kses(
						sprintf( /* translators: %s - stars icons */
							__( 'Hey, do you like our plugin? Please, could you rate it and set %s stars for us. We very care about your emotion and comfortability.', 'shipping-nova-poshta-for-woocommerce' ),
							'<span class="stars"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></span>'
						),
						[
							'span' => [
								'class' => true,
							],
						]
					),
				'btn_label' => esc_html__( 'Rate plugin', 'shipping-nova-poshta-for-woocommerce' ),
				'btn_url'   => 'https://wordpress.org/support/plugin/shipping-nova-poshta-for-woocommerce/reviews/#new-post',
			],
			[
				'message'   => esc_html__( 'If you found a bug or have an idea for a new feature tell us. Let\'s go make the plugin better.', 'shipping-nova-poshta-for-woocommerce' ),
				'btn_label' => esc_html__( 'Report a bug', 'shipping-nova-poshta-for-woocommerce' ),
				'btn_url'   => 'https://wordpress.org/support/plugin/shipping-nova-poshta-for-woocommerce/#new-topic-0',
			],
		];

		if ( ! nova_poshta()->is_pro() ) {
			$advertisements[] = [
				'message'   => sprintf( /* translators: %s - discount in percent */
					esc_html__( 'To unlock more features, consider upgrading to Pro. As a valued user you receive %s off, automatically applied at checkout!', 'shipping-nova-poshta-for-woocommerce' ),
					'50%'
				),
				'btn_label' => esc_html__( 'Upgrade', 'shipping-nova-poshta-for-woocommerce' ),
				'btn_url'   => 'https://wp-unit.com/product/nova-poshta-pro/',
			];
		}

		return $advertisements[ wp_rand( 0, count( $advertisements ) - 1 ) ];
	}

	/**
	 * Close on some time.
	 */
	public function close() {

		check_ajax_referer( Main::PLUGIN_SLUG, 'nonce' );
		$this->transient_cache->set( 'advertisement', 1, 7 * constant( 'DAY_IN_SECONDS' ) );
		wp_send_json( true );
	}
}

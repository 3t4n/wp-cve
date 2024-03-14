<?php

namespace WpifyWoo\Modules\HeurekaMereniKonverzi;

use WpifyWoo\Abstracts\AbstractModule;
use WpifyWoo\Models\WooOrderModel;
use WpifyWoo\Repositories\WooOrderRepository;
use WpifyWooDeps\Wpify\Core\Models\WooOrderItemProductModel;

/**
 * Class HeurekaOverenoZakaznikyModule
 * @package WpifyWoo\Modules\HeurekaOverenoZakazniky
 */
class HeurekaMereniKonverziModule extends AbstractModule {

	/**
	 * Setup
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'woocommerce_thankyou', array( $this, 'render_tracking_code' ) );
	}

	/**
	 * Get the module ID
	 * @return string
	 */
	public function id(): string {
		return 'heureka_mereni_konverzi';
	}

	/**
	 * Set module name
	 * @return string
	 */
	public function name(): string {
		return __( 'Heureka Měření konverzí', 'wpify-woo' );
	}

	/**
	 *  Get the settings
	 * @return array[]
	 */
	public function settings(): array {
		return array(
				array(
						'id'    => 'api_key',
						'type'  => 'text',
						'label' => __( 'Public key for conversions', 'wpify-woo' ),
						'desc'  => __( 'Enter the public key for the conversion measurement code.' ),
				),
				array(
						'id'      => 'country',
						'type'    => 'select',
						'options' => [
								[
										'label' => 'CZ',
										'value' => 'cs',
								],
								[
										'label' => 'SK',
										'value' => 'sk',
								],
						],
						'label'   => __( 'Country', 'wpify-woo' ),
						'desc'    => __( 'Select country for tracking' ),
				),
		);
	}

	/**
	 *
	 */
	public function render_tracking_code( $order_id ) {
		$api_key = $this->get_setting( 'api_key' );
		if ( ! $api_key ) {
			return;
		}

		/** @var WooOrderModel $order */
		$order    = $this->plugin->get_repository( WooOrderRepository::class )->get( $order_id );
		$products = [];
		foreach ( $order->get_line_items() as $item ) {
			/** @var WooOrderItemProductModel $item */
			$products[] = [
					'addProduct',
					$item->get_name(),
					(string) $item->get_unit_price(),
					(string) $item->get_quantity(),
					(string) $item->get_product_id(),
			];
		}
		$url = 'https://im9.cz/js/ext/1-roi-async.js';
		if ( 'sk' === $this->get_setting( 'country' ) ) {
			$url = 'https://im9.cz/sk/js/ext/2-roi-async.js';
		}
		$url = apply_filters( 'wpify_woo_heureka_mereni_konverzi_url', $url );
		?>
		<script type="text/javascript">
			var _hrq = _hrq || [];
			_hrq.push(['setKey', '<?php echo esc_attr( $api_key ); ?>']);
			_hrq.push(['setOrderId', '<?php echo esc_attr( $order->get_id() ); ?>']);

			<?php foreach ( $products as $item ) { ?>
			_hrq.push(<?php echo json_encode( $item );?>);
			<?php }?>
			_hrq.push(['trackOrder']);

			(function () {
				var ho = document.createElement('script');
				ho.type = 'text/javascript';
				ho.async = true;
				ho.src = '<?php echo $url;?>';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ho, s);
			})();
		</script>

		<?php
	}
}

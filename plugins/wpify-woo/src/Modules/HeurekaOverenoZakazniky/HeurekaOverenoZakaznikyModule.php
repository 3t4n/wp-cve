<?php

namespace WpifyWoo\Modules\HeurekaOverenoZakazniky;

use ReflectionException;
use WpifyWoo\Abstracts\AbstractModule;
use WpifyWoo\Models\WooOrderModel;
use WpifyWoo\Repositories\WooOrderRepository;
use WpifyWooDeps\Heureka\ShopCertification;
use WpifyWooDeps\Heureka\ShopCertification\Exception;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;

/**
 * Class HeurekaOverenoZakaznikyModule
 *
 * @package WpifyWoo\Modules\HeurekaOverenoZakazniky
 */
class HeurekaOverenoZakaznikyModule extends AbstractModule {

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		//add_action( 'woocommerce_checkout_order_processed', array( $this, 'schedule_event' ) );
		add_action( 'woocommerce_checkout_order_created', array( $this, 'send_order_to_heureka_now' ) );
		add_action( 'wpify_woo_heureka_overeno_zakazniky', array( $this, 'send_order_to_heureka' ) );
		add_action( 'woocommerce_checkout_after_terms_and_conditions', array( $this, 'add_optout' ) );
		add_action( 'wp_head', array( $this, 'render_widget' ) );
	}

	/**
	 * Get the module ID
	 *
	 * @return string
	 */
	public function id(): string {
		return 'heureka_overeno_zakazniky';
	}

	/**
	 *  Get the settings
	 *
	 * @return array[]
	 */
	public function settings(): array {
		return array(
				array(
						'id'      => 'country',
						'type'    => 'select',
						'label'   => __( 'Country', 'wpify-woo' ),
						'desc'    => __( 'Select country', 'wpify-woo' ),
						'options' => array(
								array(
										'label' => __( 'Heureka CZ', 'wpify-woo' ),
										'value' => 'CZ',
								),
								array(
										'label' => __( 'Heureka SK', 'wpify-woo' ),
										'value' => 'SK',
								),
						),
				),
				array(
						'id'    => 'api_key',
						'type'  => 'text',
						'label' => __( 'Api Key', 'wpify-woo' ),
						'desc'  => __( 'Enter the API Key', 'wpify-woo' ),
				),
				array(
						'id'    => 'enable_optout',
						'type'  => 'switch',
						'label' => __( 'Enable Opt-Out', 'wpify-woo' ),
						'desc'  => __( 'Check if you want to enable opt out on the checkout', 'wpify-woo' ),
				),
				array(
						'id'      => 'enable_optout_text',
						'type'    => 'text',
						'label'   => __( 'Enable Opt-Out Text', 'wpify-woo' ),
						'desc'    => __( 'Enter the Opt-out text', 'wpify-woo' ),
						'default' => __( "I don't want to receive survey from Heureka ověřeno zákazníky", 'wpify-woo' ),
				),
				array(
						'id'    => 'widget_enabled',
						'type'  => 'switch',
						'label' => __( 'Enable Certification Widget', 'wpify-woo' ),
						'desc'  => __( 'Enable certification widget.', 'wpify-woo' ),
				),
				array(
						'id'    => 'widget_code',
						'type'  => 'textarea',
						'label' => __( 'Certification widget code', 'wpify-woo' ),
						'desc'  => __( 'Copy the code from your Heureka account.', 'wpify-woo' ),
				),
				array(
						'id'    => 'send_async',
						'type'  => 'toggle',
						'label' => __( 'Send asynchronously', 'wpify-woo' ),
						'desc'  => __( 'By default the order is sent to Heureka synchronously, which is required by Heureka. Under some circumstances this can cause issues - toggle on if you want to schedule the event and send it asynchronously.', 'wpify-woo' ),
				),
		);
	}

	/**
	 * Schedule the event
	 *
	 * @param int|string $order_id Order ID.
	 *
	 * @return false|int
	 */
	public function schedule_event( $order_id ) {
		return as_schedule_single_action( time(), 'wpify_woo_heureka_overeno_zakazniky', array( 'order_id' => $order_id ) );
	}

	/**
	 * Send the order to Heureka on order processed hook
	 *
	 * @param int|string $order_id Order ID.
	 *
	 * @return false|int
	 */
	public function send_order_to_heureka_now( $order_id ) {
		if ( ! $this->get_setting( 'api_key' ) ) {
			return false;
		}

		// Get order
		if ( is_a( $order_id, '\WC_Order' ) ) {
			$order    = $order_id;
			$order_id = $order_id->get_id();
		} else {
			$order = wc_get_order( $order_id );
		}

		// Check if data is already send
		if ( $order->meta_exists( '_wpify_woo_heureka_optout_agreement' ) ) {
			return false;
		}

		// If customer don't agree with questionnaire
		if ( isset( $_POST['wpify_woo_heureka_optout'] ) && ! empty( $_POST['wpify_woo_heureka_optout'] ) ) {
			$order->add_order_note( sprintf( __( 'Heureka: Agree with the satisfaction questionnaire: %s', 'wpify-woo' ), __( 'No', 'wpify-woo' ) ) );
			$order->update_meta_data( '_wpify_woo_heureka_optout_agreement', 'no' );
			$order->save();

			return false;
		}

		// send data
		if ( $this->get_setting( 'send_async' ) ) {
			$this->schedule_event( $order_id );
		} else {
			$this->send_order_to_heureka( $order_id );
		}
	}

	/**
	 * Send order to Heureka
	 *
	 * @param int|string $order_id Order ID.
	 *
	 * @throws ReflectionException Exception.
	 * @throws PluginException Exception.
	 */
	public function send_order_to_heureka( $order_id ) {
		/** Order Model. @var WooOrderModel $order */
		$order = $this->plugin->get_repository( WooOrderRepository::class )->get( $order_id );

		try {
			$options = array();
			if ( 'CZ' === $this->get_setting( 'country' ) ) {
				$options['service'] = ShopCertification::HEUREKA_CZ;
			} elseif ( 'SK' === $this->get_setting( 'country' ) ) {
				$options['service'] = ShopCertification::HEUREKA_SK;
			}


			$shop_certification = new ShopCertification( $this->get_setting( 'api_key' ), $options, ( new WpRequester() ) );
			$shop_certification->setEmail( $order->get_wc_order()->get_billing_email() );
			$shop_certification->setOrderId( $order->get_id() );

			foreach ( $order->get_line_items() as $item ) {
				$shop_certification->addProductItemId( $item->get_product_id() );
			}

			$shop_certification->logOrder();
			$order->get_wc_order()->add_order_note( sprintf( __( 'Heureka: Agree with the satisfaction questionnaire: %s', 'wpify-woo' ), __( 'Yes. The order has been sent.', 'wpify-woo' ) ) );
			$order->get_wc_order()->update_meta_data( '_wpify_woo_heureka_optout_agreement', 'yes' );
			$order->get_wc_order()->save();
			$this->plugin->get_logger()->info(
					sprintf( 'Heureka Overeno: sent order to Heureka.' ),
					array(
							'data' => array(
									'order_id' => $order->get_id(),
							),
					)
			);
		} catch ( Exception $e ) {
			$this->plugin->get_logger()->error(
					sprintf( 'Heureka Overeno: error sending to Heureka.' ),
					array(
							'data' => array(
									'message'  => $e->getMessage(),
									'settings' => $this->get_settings(),
									'options'  => $options,
									'order_id' => $order->get_id(),
							),
					)
			);
		}
	}

	/**
	 * Add optout to checkout
	 */
	public function add_optout() {
		if ( ! $this->get_setting( 'enable_optout' ) || apply_filters( 'wpify_woo_heureka_add_optout', true ) === false ) {
			return;
		}
		?>
		<p class="form-row wpify-woo-heureka-optout">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
					   name="wpify_woo_heureka_optout" style="width: auto;"
						<?php
						checked( isset( $_POST['wpify_woo_heureka_optout'] ), true ); // WPCS: input var ok, csrf ok.
						?>
				/>
				<span class="wpify-woo-heureka-optout-checkbox-text"><?php echo sanitize_text_field( $this->get_setting( 'enable_optout_text' ) ); ?></span>&nbsp;
			</label>
		</p>        <?php
	}

	/**
	 * Render certification widget
	 */
	public function render_widget() {
		if ( empty( $this->get_setting( 'widget_enabled' ) ) || empty( $this->get_setting( 'widget_code' ) ) || apply_filters( 'wpify_woo_heureka_render_widget', true ) === false ) {
			return;
		}

		echo $this->get_setting( 'widget_code' );
	}

	public function name() {
		return __( 'Heureka Ověřeno Zákázníky', 'wpify-woo' );
	}
}

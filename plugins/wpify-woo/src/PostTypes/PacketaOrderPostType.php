<?php

namespace WpifyWoo\PostTypes;

use WpifyWoo\Factories\WooOrderFieldsFactory;
use WpifyWoo\Models\PacketaOrderModel;
use WpifyWoo\Modules\PacketaShipping\PacketaShippingModule;
use WpifyWoo\Plugin;
use WpifyWoo\Repositories\PacketaOrderRepository;

/**
 * Class BookPostType
 * @package WpifyPlugin\Cpt
 * @property Plugin $plugin
 */
class PacketaOrderPostType extends WooOrderPostType {
	public function setup() {
		$woo_integration = $this->plugin->get_woocommerce_integration();
		if ( ! $woo_integration->is_module_enabled( 'packeta_shipping' )  ) {
			return;
		}

		add_action('init',[$this,'register_metaboxes'], 5);
	}

	public function register_metaboxes() {
		if ( empty( $_GET['post']) && (empty($_POST['action']) || $_POST['action'] !== 'editpost') ) {
			return;
		}

		$post_id = intval($_GET['post'] ?? $_POST['post_ID']);

		if ( ! $post_id || 'shop_order' !== get_post_type( $post_id ) ) {
			return;
		}
		/** @var PacketaOrderModel $order */
		$order = $this->plugin->get_repository( PacketaOrderRepository::class )->get( $post_id );
		if ( ! $order->is_packeta_shipping() ) {
			return;
		}
		$orderStatus = $order->get_wc_order()->get_status();

		if ($orderStatus !== 'completed') {
			$items = [
				[
					'id' => '_packeta_order_details',
					'type' => 'group',
					'items' => [
						[
							'type' => 'html',
							'content' => __('You can override the calculated values that are sent to Packeta here. If you do changes, <strong>make sure to click update button</strong> before sending the order to Packeta.', 'wpify-woo'),
							'id' => 'title'
						],
						[
							'title' => __('Weight (kg)', 'wpify-woo'),
							'id' => 'weight',
							'type' => 'number',
							'custom_attributes' => array( 'step' => ".01")
						]
					]
				]
			];
		} else {
			$items[] = [
				'type'    => 'html',
				'id'      => 'weight',
				'content' => sprintf( '<strong>Weight: </strong> %s kg', $order->get_package_weight() ),
			];
		}

		if ( $order->get_package_id() ) {
			$items[] = [
				'type'    => 'html',
				'id'      => 'packeta_order',
				'content' => sprintf( '<div><strong>Packeta package ID: </strong> %s</div>', $order->get_package_id() ),
			];
			$items[] = [
				'type'  => 'button',
				'id'    => 'packeta_label',
				'label' => __( 'Download label', 'wpify-woo' ),
				'url'   => add_query_arg( array( 'packeta-generate-label' => $order->get_package_id() ), get_edit_post_link( sanitize_text_field( $_GET['post'] ) ) ),
			];
		}

		$items[] = [
			'type'  => 'button',
			'id'    => 'packeta_send',
			'label' => $order->get_package_id() ? __( 'Resend to Packeta', 'wpify-woo' ) : __( 'Send to Packeta', 'wpify-woo' ),
			'url'   => add_query_arg(array( 'action' => 'send_to_packeta', 'order_id' => $order->get_id() ), admin_url()) ,

		];

		$this->plugin->get_wcf()->create_metabox(
			[
				'id'         => 'wpify-woo-packeta',
				'title'      => __( 'Packeta shipping', 'wpify-woo' ),
				'post_types' => array( 'shop_order' ),
				'items'      => $items,
				'context'    => 'side'
			]
		);
	}

	public function model(): string {
		return PacketaOrderModel::class;
	}

	public function custom_fields_factory(): ?string {
		return WooOrderFieldsFactory::class;
	}
}

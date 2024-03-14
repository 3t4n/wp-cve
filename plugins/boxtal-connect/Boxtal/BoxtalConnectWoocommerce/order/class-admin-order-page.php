<?php
/**
 * Contains code for the admin order page class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Order
 */

namespace Boxtal\BoxtalConnectWoocommerce\Order;

use Boxtal\BoxtalConnectWoocommerce\Util\Order_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Admin_Order_Page class.
 *
 * Adds additional info to order page.
 */
class Admin_Order_Page {

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Order tracking.
	 *
	 * @var mixed
	 */
	private $tracking;

	/**
	 * Order parcel point.
	 *
	 * @var mixed
	 */
	private $parcelpoint;

	/**
	 * Construct function.
	 *
	 * @param array $plugin plugin array.
	 * @void
	 */
	public function __construct( $plugin ) {
		$this->plugin_url     = $plugin['url'];
		$this->plugin_version = $plugin['version'];
		$this->tracking       = null;
		$this->parcelpoint    = null;
	}

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		$controller = new Controller(
			array(
				'url'     => $this->plugin_url,
				'version' => $this->plugin_version,
			)
		);
		add_action( 'admin_enqueue_scripts', array( $controller, 'tracking_styles' ) );
		add_filter( 'add_meta_boxes_shop_order', array( $this, 'add_tracking_to_admin_order_page' ), 10, 2 );
		add_filter( 'add_meta_boxes_woocommerce_page_wc-orders', array( $this, 'add_tracking_to_admin_order_page_hpos' ), 10, 2 );
		add_filter( 'add_meta_boxes_shop_order', array( $this, 'add_parcelpoint_to_admin_order_page' ), 10, 2 );
		add_filter( 'add_meta_boxes_woocommerce_page_wc-orders', array( $this, 'add_parcelpoint_to_admin_order_page_hpos' ), 10, 2 );
		add_filter( 'woocommerce_admin_order_preview_get_order_details', array( $this, 'order_view_modal_details' ) );
		add_filter( 'woocommerce_admin_order_preview_end', array( $this, 'order_view_modal' ) );
	}

	/**
	 * Add parcelpoint info to admin order page
	 *
	 * @param mixed $order current order.
	 *
	 * @void
	 */
	public function add_parcelpoint_to_admin_order_page_hpos( $order ) {
		$this->parcelpoint = Order_Util::get_parcelpoint( $order );

		if ( null !== $this->parcelpoint ) {
			/* translators: 1) company name */
			$box_name = sprintf( __( '%s - Shipment pickup point', 'boxtal-connect' ), Branding::$company_name );
			$box_id   = Branding::$branding . '-order-parcelpoint';
			add_meta_box( $box_id, $box_name, array( $this, 'order_edit_page_parcelpoint' ), null, 'side' );
		}
	}

	/**
	 * Add parcelpoint info to admin order page
	 *
	 * @void
	 */
	public function add_parcelpoint_to_admin_order_page() {
		$order             = Order_Util::admin_get_order();
		$this->parcelpoint = Order_Util::get_parcelpoint( $order );

		if ( null === $this->parcelpoint ) {
			return;
		}

		if ( function_exists( 'wc_get_order_types' ) ) {
			foreach ( wc_get_order_types( 'order-meta-boxes' ) as $type ) {
				/* translators: 1) company name */
				add_meta_box( Branding::$branding . '-order-parcelpoint', sprintf( __( '%s - Shipment pickup point', 'boxtal-connect' ), Branding::$company_name ), array( $this, 'order_edit_page_parcelpoint' ), $type, 'side', 'default' );
			}
		} else {
			/* translators: 1) company name */
			add_meta_box( Branding::$branding . '-order-parcelpoint', sprintf( __( '%s - Shipment pickup point', 'boxtal-connect' ), Branding::$company_name ), array( $this, 'order_edit_page_parcelpoint' ), 'shop_order', 'side', 'default' );
		}
	}

	/**
	 * Add tracking info to admin order page.
	 *
	 * @param mixed $order current order.
	 *
	 * @void
	 */
	public function add_tracking_to_admin_order_page_hpos( $order ) {
		$controller     = new Controller(
			array(
				'url'     => $this->plugin_url,
				'version' => $this->plugin_version,
			)
		);
		$this->tracking = $controller->get_order_tracking( Order_Util::get_id( $order ) );

		if ( null !== $this->tracking && property_exists( $this->tracking, 'shipmentsTracking' ) && ! empty( $this->tracking->shipmentsTracking ) ) {
			/* translators: 1) company name */
			$box_name = sprintf( __( '%s - Shipment tracking', 'boxtal-connect' ), Branding::$company_name );
			$box_id   = Branding::$branding . '-order-tracking';

			add_meta_box( $box_id, $box_name, array( $this, 'order_edit_page_tracking' ), null, 'side' );
		}
	}

	/**
	 * Add tracking info to admin order page.
	 *
	 * @void
	 */
	public function add_tracking_to_admin_order_page() {
		$controller     = new Controller(
			array(
				'url'     => $this->plugin_url,
				'version' => $this->plugin_version,
			)
		);
		$this->tracking = $controller->get_order_tracking( Order_Util::get_id( Order_Util::admin_get_order() ) );

		if ( null === $this->tracking || ! property_exists( $this->tracking, 'shipmentsTracking' ) || empty( $this->tracking->shipmentsTracking ) ) {
			return;
		}

		if ( function_exists( 'wc_get_order_types' ) ) {
			foreach ( wc_get_order_types( 'order-meta-boxes' ) as $type ) {
				/* translators: 1) company name */
				add_meta_box( Branding::$branding . '-order-tracking', sprintf( __( '%s - Shipment tracking', 'boxtal-connect' ), Branding::$company_name ), array( $this, 'order_edit_page_tracking' ), $type, 'normal', 'high' );
			}
		} else {
			/* translators: 1) company name */
			add_meta_box( Branding::$branding . '-order-tracking', sprintf( __( '%s - Shipment tracking', 'boxtal-connect' ), Branding::$company_name ), array( $this, 'order_edit_page_tracking' ), 'shop_order', 'normal', 'high' );
		}
	}

	/**
	 *
	 * Display the parcel point metabox content
	 *
	 * @Void
	 */
	public function order_edit_page_parcelpoint() {
		$parcelpoint          = $this->parcelpoint;
		$parcelpoint_networks = \Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point\Controller::get_network_list();
		require_once realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-admin-order-edit-page-parcelpoint.php';
	}

	/**
	 * Order edit page output.
	 *
	 * @void
	 */
	public function order_edit_page_tracking() {
		$tracking = $this->tracking;
		include realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-admin-order-edit-page-tracking.php';
	}

	/**
	 * Order view modal details.
	 *
	 * @param array $order_details order details sent to template.
	 *
	 * @return array
	 */
	public function order_view_modal_details( $order_details ) {

		if ( ! isset( $order_details['order_number'] ) ) {
			return $order_details;
		}

		$controller = new Controller(
			array(
				'url'     => $this->plugin_url,
				'version' => $this->plugin_version,
			)
		);
		$tracking   = $controller->get_order_tracking( $order_details['order_number'] );

		if ( null === $tracking || ! property_exists( $tracking, 'shipmentsTracking' ) || empty( $tracking->shipmentsTracking ) ) {
			return $order_details;
		}
		ob_start();
		include realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-admin-order-view-modal-tracking.php';
		$html                           = ob_get_clean();
		$order_details['tracking_html'] = $html;
		return $order_details;
	}

	/**
	 * Order view modal.
	 *
	 * @void
	 */
	public function order_view_modal() {
		include realpath( plugin_dir_path( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'html-admin-order-view-modal-print-tracking.php';
	}
}

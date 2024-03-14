<?php
/**
 * CoD function for interakt add on.
 *
 * @package interakt-add-on-woocommerce
 */

/**
 * API class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Setting class.
 */
class Intrkt_Cod {
	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;
	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_thankyou_cod', array( $this, 'intrkt_add_order_conformation_status' ) );
		add_action( 'woocommerce_order_details_after_order_table', array( $this, 'intrkt_add_order_conformation_action' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'intrkt_register_cod_scripts' ) );
		add_action( 'wp_ajax_intrkt_cod_action', array( $this, 'intrkt_cod_action_callback' ) );
		add_action( 'wp_ajax_nopriv_intrkt_cod_action', array( $this, 'intrkt_cod_action_callback' ) );
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'intrkt_order_list_custom_column' ) );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'intrkt_add_order_confirmation_column_content' ) );
	}
	/**
	 * Change Order thank you page template.
	 *
	 * @param string $order_id order id.
	 */
	public function intrkt_add_order_conformation_status( $order_id ) {
		$is_confirmed = ! empty( get_post_meta( $order_id, 'intrkt_is_confirmed', true ) ) ? get_post_meta( $order_id, 'intrkt_is_confirmed', true ) : false;
		$status_label = 'confirm' === get_post_meta( $order_id, 'intrkt_confirm_status', true ) ? __( 'Confirmed', 'abandoned-checkout-recovery-order-notifications-woocommerce' ) : __( 'Canceled', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
		?>
		<?php if ( 'true' === $is_confirmed ) : ?>
			<ul class = 'woocommerce-order-overview woocommerce-thankyou-order-details order_details intrkt-cod-confirm-wrap'>
				<li class="woocommerce-order-overview__confirm order">
					<?php esc_html_e( 'Confirm Order:', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
					<strong><?php echo esc_html( $status_label ); ?></strong>
				</li>
			</ul>
		<?php endif; ?>
		<?php
	}
	/**
	 * Add CTA for confirmation.
	 *
	 * @param object $order WooCommerce order.
	 */
	public function intrkt_add_order_conformation_action( $order ) {
		if ( empty( $order ) ) {
			return;
		}
		$order_id       = $order->get_id();
		$payment_method = $order->get_payment_method();
		if ( 'cod' !== $payment_method ) {
			return;
		}
		$is_confirmed = ! empty( get_post_meta( $order_id, 'intrkt_is_confirmed', true ) ) ? get_post_meta( $order_id, 'intrkt_is_confirmed', true ) : 'false';
		?>
		<?php if ( 'false' === $is_confirmed ) : ?>
			<div class="intrkt_confirm-action">
				<input type="button" class = "intrkt_cod_action" id = "intrkt_action_cancel" value="<?php esc_html_e( 'Cancel Order', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>">
				<input type="button" class = "intrkt_cod_action" id = "intrkt_action_confirm" value="<?php esc_html_e( 'Confirm Order', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>">
			</div>
		<?php endif; ?>
		<?php
	}
	/**
	 * Enqueue required scripts for CoD.
	 */
	public function intrkt_register_cod_scripts() {
		global $wp;
		if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' ) ) {
			wp_enqueue_script(
				'intrkt-cod',
				INTRKT_URL . 'public/js/intrkt-cod.js',
				array( 'jquery' ),
				INTRKT_VER,
				true
			);
			wp_enqueue_style(
				'intrkt_admin_css',
				INTRKT_URL . 'public/css/intrkt-cod.css',
				array(),
				INTRKT_VER,
				'all'
			);
			$order_id = is_wc_endpoint_url( 'order-received' ) ? $wp->query_vars['order-received'] : $wp->query_vars['view-order'];
			$vars     = array(
				'orderId' => $order_id,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'_nonce'  => wp_create_nonce( 'intrkt_cod_data' ),
			);
			wp_localize_script( 'intrkt-cod', 'intrktCodVars', $vars );
		}
	}
	/**
	 * Ajax call to CoD CTA
	 */
	public function intrkt_cod_action_callback() {
		check_ajax_referer( 'intrkt_cod_data', 'security' );
		$cod_action = ! empty( $_POST['cod_action'] ) ? sanitize_text_field( wp_unslash( $_POST['cod_action'] ) ) : '';
		$order_id   = ! empty( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : '';
		if ( ! empty( $cod_action ) && ! empty( $order_id ) ) {
			$order = new WC_Order( $order_id );
			update_post_meta( $order_id, 'intrkt_is_confirmed', 'true' );
			update_post_meta( $order_id, 'intrkt_confirm_status', $cod_action );
			$cod_action_label = ( 'confirm' === $cod_action ) ? __( 'Confirmed', 'abandoned-checkout-recovery-order-notifications-woocommerce' ) : __( 'Canceled', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
			/* translators: %s WC download URL link. */
			$note = sprintf( __( 'Order is %s', 'abandoned-checkout-recovery-order-notifications-woocommerce' ), $cod_action_label );
			$order->add_order_note( $note, 1 );
			if ( 'cancel' === $cod_action ) {
				$order->update_status( 'cancelled', 'User declined the order' );
			}
		}
		wp_send_json( $cod_action );
	}
	/**
	 * Register custom column.
	 *
	 * @param array $columns Order list columns.
	 *
	 * @return array.
	 */
	public function intrkt_order_list_custom_column( $columns ) {
		$columns['intrkt_confirmation_status'] = __( 'CoD Confirmation Status', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
		return $columns;
	}
	/**
	 * Add data to confirmation column.
	 *
	 * @param array $column Order list column.
	 *
	 * @return void.
	 */
	public function intrkt_add_order_confirmation_column_content( $column ) {
		global $post;
		if ( 'intrkt_confirmation_status' !== $column ) {
			return;
		}
		$status_label   = __( 'NA', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
		$order          = wc_get_order( $post->ID );
		$order_id       = $post->ID;
		$payment_method = $order->get_payment_method();
		if ( 'cod' === $payment_method ) {
			$is_confirmed   = ! empty( get_post_meta( $order_id, 'intrkt_is_confirmed', true ) ) ? get_post_meta( $order_id, 'intrkt_is_confirmed', true ) : false;
			$confirm_status = get_post_meta( $order_id, 'intrkt_confirm_status', true );
			if ( 'true' === $is_confirmed && 'confirm' === $confirm_status ) {
				$status_label = __( 'Confirmed', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
			} elseif ( 'true' === $is_confirmed && 'cancel' === $confirm_status ) {
				$status_label = __( 'Cancel', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
			} else {
				$status_label = __( 'Not Confirmed', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
			}
		}
		$status_label = apply_filters( 'intrkt_confirmation_status_label_admin_order', $status_label, $order_id );
		echo esc_html( $status_label );
	}
}
Intrkt_Cod::get_instance();

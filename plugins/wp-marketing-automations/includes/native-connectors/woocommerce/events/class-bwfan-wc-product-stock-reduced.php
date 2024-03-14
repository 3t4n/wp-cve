<?php

final class BWFAN_WC_Product_Stock_Reduced extends BWFAN_Event {
	private static $instance = null;
	public $reduced_products = [];
	public $order_id = null;
	public $single_item_id = null;
	public $qty = null;
	public $single_item = null;
	/** @var WC_Order $order */
	public $order = null;

	private function __construct() {
		$this->optgroup_label         = esc_html__( 'Orders', 'wp-marketing-automations' );
		$this->event_name             = esc_html__( 'Order Item Stock Reduced', 'wp-marketing-automations' );
		$this->event_desc             = esc_html__( 'This event runs after an order payment is complete and its relative product items stocks reduced (runs per product item).', 'wp-marketing-automations' );
		$this->event_merge_tag_groups = array( 'bwf_contact', 'wc_items' );
		$this->event_rule_groups      = array(
			'wc_items',
			'bwf_contact_segments',
			'bwf_contact',
			'bwf_contact_fields',
			'bwf_contact_user',
			'bwf_contact_wc',
			'bwf_contact_geo',
			'bwf_engagement',
			'bwf_broadcast'
		);
		$this->priority               = 15.4;
		$this->v2                     = true;
	}

	public function load_hooks() {
		add_filter( 'woocommerce_payment_complete_reduce_order_stock', [ $this, 'trigger_reduce_stock_event' ], 999999, 2 );
	}

	/**
	 * @return BWFAN_WC_Product_Stock_Reduced|null
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set up rules data
	 *
	 * @param $value
	 */
	public function pre_executable_actions( $value ) {
		BWFAN_Core()->rules->setRulesData( $this->order, 'wc_order' );
		BWFAN_Core()->rules->setRulesData( $this->single_item, 'wc_items' );
		BWFAN_Core()->rules->setRulesData( $this->qty, 'qty' );
	}

	public function trigger_reduce_stock_event( $trigger_reduce, $order_id ) {
		// Only continue if we're reducing stock.
		if ( ! $trigger_reduce ) {
			return $trigger_reduce;
		}

		$this->order_id = $order_id;
		add_filter( 'woocommerce_order_item_quantity', [ $this, 'get_item_data' ], 999999, 3 );

		add_action( 'woocommerce_reduce_order_stock', [ $this, 'process' ] );

		return $trigger_reduce;
	}

	public function get_item_data( $item_qty, $order, $item ) {
		$this->reduced_products[] = [
			'item'  => $item,
			'order' => $order,
			'qty'   => $item_qty,
		];

		return $item_qty;
	}

	public function get_task_view( $global_data ) {
		ob_start();
		if ( ! is_array( $global_data ) ) {
			return '';
		}
		if ( ! isset( $global_data['single_item_id'] ) || 0 === absint( $global_data['single_item_id'] ) ) {
			return;
		}

		/** @var WC_Order_Item $item_ins */
		$item_ins = new WC_Order_Item_Product( absint( $global_data['single_item_id'] ) );
		if ( ! $item_ins instanceof WC_Order_Item_Product || ! is_array( $item_ins->get_data() ) ) {
			return;
		}

		$data = $item_ins->get_data();
		?>
        <li>
            <strong><?php esc_html_e( 'Product :', 'wp-marketing-automations' ); ?> </strong>
            <a target="_blank" href="<?php echo get_edit_post_link( $data['product_id'] ); //phpcs:ignore WordPress.Security.EscapeOutput ?>"><?php echo esc_attr( $data['name'] ); ?></a>
        </li>
        <li>
            <strong><?php esc_html_e( 'Reduced Quantity :', 'wp-marketing-automations' ); ?> </strong>
			<?php echo esc_attr( $global_data['qty'] ); ?>
        </li>
		<?php

		return ob_get_clean();
	}

	/**
	 * Make the required data for the current event and send it asynchronously.
	 *
	 * @param $order WC_Product
	 */
	public function process( $order ) {
		if ( ! is_array( $this->reduced_products ) || 0 === count( $this->reduced_products ) ) {
			return;
		}
		foreach ( $this->reduced_products as $single ) {
			$data            = $this->get_default_data();
			$data['details'] = array(
				'order_id'       => $single['order']->get_id(),
				'single_item_id' => $single['item']->get_id(),
				'qty'            => $single['qty'],
			);
			$this->send_async_call( $data );
		}
	}

	/**
	 * Registers the tasks for current event.
	 *
	 * @param $automation_id
	 * @param $integration_data
	 * @param $event_data
	 */
	public function register_tasks( $automation_id, $integration_data, $event_data ) {
		if ( ! is_array( $integration_data ) ) {
			return;
		}

		$data_to_send = $this->get_event_data();
		$this->create_tasks( $automation_id, $integration_data, $event_data, $data_to_send );
	}

	public function get_event_data() {
		$data_to_send                             = [];
		$data_to_send['global']['order_id']       = $this->order_id;
		$data_to_send['global']['single_item_id'] = $this->single_item_id;
		$data_to_send['global']['qty']            = $this->qty;
		$data_to_send['global']['single_item']    = $this->single_item;

		$this->order                     = $this->order instanceof WC_Order ? $this->order : wc_get_order( $this->order_id );
		$data_to_send['global']['email'] = BWFAN_Common::get_email_from_order( $this->order_id, $this->order );
		$data_to_send['global']['phone'] = BWFAN_Common::get_phone_from_order( $this->order_id, $this->order );
		$user_id                         = BWFAN_Common::get_wp_user_id_from_order( $this->order_id, $this->order );
		if ( intval( $user_id ) > 0 ) {
			$data_to_send['global']['user_id'] = $user_id;
		}
		$order_lang = '';
		if ( class_exists( 'woocommerce_wpml' ) && $this->order instanceof WC_Order ) {
			$order_lang = $this->order->get_meta( 'wpml_language' );
		}

		if ( ! empty( $order_lang ) ) {
			$data_to_send['global']['language'] = $order_lang;
		}

		return $data_to_send;
	}

	/**
	 * Set global data for all the merge tags which are supported by this event.
	 *
	 * @param $task_meta
	 */
	public function set_merge_tags_data( $task_meta ) {
		$get_data = BWFAN_Merge_Tag_Loader::get_data();
		$set_data = array(
			'wc_single_item_id' => $task_meta['global']['single_item_id'],
		);

		if ( ! isset( $get_data['wc_order_id'] ) || $get_data['wc_order_id'] !== $task_meta['global']['order_id'] ) {
			$set_data['wc_order_id'] = $task_meta['global']['order_id'];
			$set_data['wc_order']    = wc_get_order( $task_meta['global']['order_id'] );
		}

		$set_data['wc_single_item'] = $task_meta['global']['single_item'];

		BWFAN_Merge_Tag_Loader::set_data( $set_data );
	}

	/**
	 * Capture the async data for the current event.
	 * @return array|bool
	 */
	public function capture_async_data() {
		$details = BWFAN_Common::$events_async_data['details'];

		$this->single_item_id = $details['single_item_id'];
		$this->single_item    = new WC_Order_Item_Product( $this->single_item_id );
		$this->qty            = $details['qty'];
		$this->run_automations();
	}

	/**
	 * Capture the async data for the current event.
	 * @return array|bool
	 */
	public function capture_v2_data( $automation_data ) {
		$details = BWFAN_Common::$events_async_data['details'];

		$this->single_item_id = $details['single_item_id'];
		$this->order_id       = $details['order_id'];
		$this->single_item    = new WC_Order_Item_Product( $this->single_item_id );
		$this->qty            = $details['qty'];
		$this->order_id       = $details['order_id'];

		$automation_data['single_item_id'] = $this->single_item_id;
		$automation_data['order_id']       = $this->order_id;
		$automation_data['qty']            = $this->qty;

		return $automation_data;
	}
}

/**
 * Register this event to a source.
 * This will show the current event in dropdown in single automation screen.
 */
if ( bwfan_is_woocommerce_active() ) {
	return 'BWFAN_WC_Product_Stock_Reduced';
}

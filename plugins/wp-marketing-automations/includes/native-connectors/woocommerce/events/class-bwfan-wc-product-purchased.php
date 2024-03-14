<?php

final class BWFAN_WC_Product_Purchased extends BWFAN_Event {
	private static $instance = null;

	public $order_id = null;
	/** @var WC_Order $order */
	public $order = null;
	public $to_status = null;
	public $single_item = null;
	public $single_item_id = null;
	public $has_run = 0;

	private function __construct() {
		$this->optgroup_label         = esc_html__( 'Orders', 'wp-marketing-automations' );
		$this->event_name             = esc_html__( 'Order Created - Per Product', 'wp-marketing-automations' );
		$this->event_desc             = esc_html__( 'This event runs after a new WooCommerce order is created and runs per line item. Can only run once on selected WC order statuses.', 'wp-marketing-automations' );
		$this->event_merge_tag_groups = array( 'bwf_contact', 'wc_order', 'wc_items' );
		$this->event_rule_groups      = array(
			'wc_order',
			'wc_items',
			'wc_order_items_data',
			'aerocheckout',
			'bwf_contact_segments',
			'bwf_contact',
			'bwf_contact_fields',
			'bwf_contact_user',
			'bwf_contact_wc',
			'bwf_contact_geo',
			'bwf_engagement',
			'bwf_broadcast'
		);
		$this->support_lang           = true;
		$this->priority               = 15.1;
		$this->v2                     = true;
	}

	public function load_hooks() {
		// event trigger hooks
		add_action( 'woocommerce_new_order', [ $this, 'new_order' ], 11 );
		add_action( 'bwfan_wc_order_status_changed', array( $this, 'order_status_changed' ), 11, 3 );

		// this action localizes the data which will be used in script template for making the UI of the event
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 98 );
		add_filter( 'bwfan_wc_event_order_status_' . $this->get_slug(), array( $this, 'modify_order_statuses' ), 10, 1 );
		add_filter( 'bwfan_before_making_logs', array( $this, 'check_if_bulk_process_executing' ), 10, 1 );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Localize data for html fields for the current event.
	 */
	public function admin_enqueue_assets() {
		if ( BWFAN_Common::is_load_admin_assets( 'automation' ) ) {
			$integration_data = $this->get_view_data();
			BWFAN_Core()->admin->set_events_js_data( $this->get_slug(), 'order_status_options', $integration_data );
		}
	}

	public function get_view_data() {
		$all_status = wc_get_order_statuses();
		if ( isset( $all_status['wc-cancelled'] ) ) {
			unset( $all_status['wc-cancelled'] );
		}
		if ( isset( $all_status['wc-failed'] ) ) {
			unset( $all_status['wc-failed'] );
		}
		if ( isset( $all_status['wc-refunded'] ) ) {
			unset( $all_status['wc-refunded'] );
		}
		if ( isset( $all_status['wc-wfocu-pri-order'] ) ) {
			unset( $all_status['wc-wfocu-pri-order'] );
		}
		asort( $all_status, SORT_REGULAR );

		$all_status = apply_filters( 'bwfan_wc_event_order_status_' . $this->get_slug(), $all_status );

		return apply_filters( 'bwfan_wc_event_order_status', $all_status );
	}

	/**
	 * Show the html fields for the current event.
	 */
	public function get_view( $db_eventmeta_saved_value ) {
		?>
        <script type="text/html" id="tmpl-event-<?php esc_html_e( $this->get_slug() ); ?>">
            <div class="bwfan-col-sm-12 bwfan-p-0 bwfan-mt-15">
                <#
                selected_statuses = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'order_status')) ? data.eventSavedData.order_status : '';
                is_validated = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'validate_event')) ? 'checked' : '';
                #>
                <label for="" class="bwfan-label-title"><?php esc_html_e( 'Select Order Statuses', 'wp-marketing-automations' ); ?></label>
                <#
                if(_.has(data.eventFieldsOptions, 'order_status_options') && _.isObject(data.eventFieldsOptions.order_status_options) ) {
                _.each( data.eventFieldsOptions.order_status_options, function( value, key ){
                checked = '';
                if(selected_statuses!='' && _.contains(selected_statuses, key)){
                checked = 'checked';
                }
                #>
                <div class="bwfan-checkboxes">
                    <input type="checkbox" name="event_meta[order_status][]" id="bwfan-{{key}}" value="{{key}}" class="bwfan-checkbox" data-warning="<?php esc_html_e( 'Please select atleast 1 order status', 'wp-marketing-automations' ); ?>" {{checked}}/>
                    <label for="bwfan-{{key}}" class="bwfan-checkbox-label">{{value}}</label>
                </div>
                <# })
                }
                #>
                <div class="clearfix bwfan_field_desc bwfan-pt-0">
                    This automation would run on new order items with selected order statuses.
                </div>
            </div>
			<?php
			$this->get_validation_html( $this->get_slug(), 'Validate Order status before executing task', 'Validate' );
			?>
        </script>
		<?php
	}

	/**
	 * Set up rules data
	 *
	 * @param $value
	 */
	public function pre_executable_actions( $value ) {
		BWFAN_Core()->rules->setRulesData( $this->event_automation_id, 'automation_id' );
		BWFAN_Core()->rules->setRulesData( $this->order, 'wc_order' );
		BWFAN_Core()->rules->setRulesData( $this->single_item, 'wc_items' );
		BWFAN_Core()->rules->setRulesData( BWFAN_Common::get_bwf_customer( $this->order->get_billing_email(), $this->order->get_user_id() ), 'bwf_customer' );
	}

	/**
	 * Save active automation ids in order meta when a new order is created so that can be processed later on.
	 *
	 * @param $order_id
	 */
	public function new_order( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		/** Check if v1 automations exists */
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );

		if ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) {
			BWFAN_Core()->logger->log( 'No active automations for order ID - ' . $order_id . ', Event - ' . $this->get_slug() . ' and function name ' . __FUNCTION__, $this->log_type );

			return;
		}

		$order->update_meta_data( '_bwfan_' . $this->get_slug(), count( $this->automations_arr ) );
		$order->save();
	}

	/**
	 * Returns the current event settings set in the automation at the time of task creation.
	 *
	 * @param $value
	 *
	 * @return array
	 */
	public function get_automation_event_data( $value ) {
		return [
			'event_source'   => $value['source'],
			'event_slug'     => $value['event'],
			'validate_event' => ( isset( $value['event_meta']['validate_event'] ) ) ? 1 : 0,
			'order_status'   => $value['event_meta']['order_status'],
		];
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
		$data_to_send                                = [];
		$data_to_send['global']['order_id']          = $this->order_id;
		$data_to_send['global']['wc_single_item_id'] = $this->single_item_id;

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

	public function order_status_changed( $order, $from_status, $to_status ) {
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$this->order_id = $order->get_id();
		$this->order    = $order;

		if ( BWFAN_Common::bwf_check_to_skip_child_order( $this->order_id ) ) {
			BWFAN_Core()->logger->log( 'Skip child order for ID - ' . $this->order_id . ', Event - ' . $this->get_slug() . ' and function name ' . __FUNCTION__, $this->log_type );

			return;
		}

		/** Check if automations v1 or v2 exists */
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );
		BWFAN_Core()->public->load_active_v2_automations( $this->get_slug() );

		$automation_not_found = false;
		if ( ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) && ( ! is_array( $this->automations_v2_arr ) || count( $this->automations_v2_arr ) === 0 ) ) {
			$automation_not_found = true;
		}

		if ( true === $automation_not_found ) {
			BWFAN_Core()->logger->log( 'No active automations for order ID - ' . $this->order_id . ', Event - ' . $this->get_slug() . ' and function name ' . __FUNCTION__, $this->log_type );

			return;
		}

		$this->to_status = 'wc-' . $to_status;
		$this->process( $this->order_id );
		$this->to_status = null;
	}

	/**
	 * Make the required data for the current event and send it asynchronously.
	 *
	 * @param $order_id
	 */
	public function process( $order_id ) {
		$data             = $this->get_default_data();
		$data['order_id'] = $order_id;
		$data['email']    = $this->order->get_billing_email();
		$data['phone']    = $this->order->get_billing_phone();

		if ( ! is_null( $this->to_status ) ) {
			$data['to_status'] = $this->to_status;
		}


		/** Run v2 automation */
		BWFAN_Common::maybe_run_v2_automations( $this->get_slug(), $data );

		/** For v1 automation */
		$count = BWFAN_Woocommerce_Compatibility::get_order_data( $this->order, '_bwfan_' . $this->get_slug() );
		if ( empty( $count ) ) {
			return;
		}

		$this->order->delete_meta_data( '_bwfan_' . $this->get_slug() );
		$this->order->save();

		$items = $this->order->get_items();
		foreach ( $items as $item_id => $item ) {
			if ( ! $item instanceof WC_Order_Item ) {
				continue;
			}
			$this->single_item    = $item;
			$this->single_item_id = $item_id;
			$this->run_automations();
		}
	}

	/**
	 * Make the view data for the current event which will be shown in task listing screen.
	 *
	 * @param $global_data
	 *
	 * @return false|string
	 */
	public function get_task_view( $global_data ) {
		ob_start();
		$order = wc_get_order( $global_data['order_id'] );
		if ( $order instanceof WC_Order ) {
			?>
            <li>
                <strong><?php esc_html_e( 'Order', 'wp-marketing-automations' ); ?> </strong>
                <a target="_blank" href="<?php echo get_edit_post_link( $global_data['order_id'] ); //phpcs:ignore WordPress.Security.EscapeOutput
				?>"><?php echo '#' . esc_html( $global_data['order_id'] . ' ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></a>
            </li>
		<?php } ?>
        <li>
            <strong><?php esc_html_e( 'Email:', 'wp-marketing-automations' ); ?> </strong>
			<?php esc_html_e( $global_data['email'] ); ?>
        </li>
		<?php
		return ob_get_clean();
	}

	/**
	 * This function decides if the task has to be executed or not.
	 * The event has validate checkbox in its meta fields.
	 *
	 * @param $task_details
	 *
	 * @return array|mixed
	 */
	public function validate_event( $task_details ) {
		$result                                     = [];
		$task_event                                 = $task_details['event_data']['event_slug'];
		$automation_id                              = $task_details['processed_data']['automation_id'];
		$automation_details                         = BWFAN_Model_Automations::get( $automation_id );
		$current_automation_event                   = $automation_details['event'];
		$current_automation_event_meta              = BWFAN_Model_Automationmeta::get_meta( $automation_id, 'event_meta' );
		$current_automation_event_validation_status = ( isset( $current_automation_event_meta['validate_event'] ) ) ? $current_automation_event_meta['validate_event'] : 0;
		$current_automation_order_statuses          = $current_automation_event_meta['order_status'];

		// Current automation has no checking
		if ( 0 === $current_automation_event_validation_status ) {
			return $this->get_automation_event_validation();
		}

		// Current automation event does not match with the event of task when the task was made
		if ( $task_event !== $current_automation_event ) {
			return $this->get_automation_event_status();
		}

		$order_id = $task_details['processed_data']['order_id'];
		$order    = wc_get_order( $order_id );

		$task_order_status = BWFAN_Woocommerce_Compatibility::get_order_status( $order );
		if ( in_array( $task_order_status, $current_automation_order_statuses, true ) ) {
			return $this->get_automation_event_success();
		}

		$result['status']  = 4;
		$result['message'] = __( 'Order status in automation has been changed', 'wp-marketing-automations' );

		return $result;
	}

	public function validate_event_data_before_executing_task( $data ) {
		return $this->validate_order( $data );
	}

	/**
	 * Set global data for all the merge tags which are supported by this event.
	 *
	 * @param $task_meta
	 */
	public function set_merge_tags_data( $task_meta ) {
		$get_data = BWFAN_Merge_Tag_Loader::get_data();
		$set_data = array(
			'wc_single_item_id' => $task_meta['global']['wc_single_item_id'],
		);

		if ( ! isset( $get_data['wc_order_id'] ) || $get_data['wc_order_id'] !== $task_meta['global']['order_id'] ) {
			$set_data['wc_order_id'] = $task_meta['global']['order_id'];
			$set_data['email']       = $task_meta['global']['email'];
			$set_data['wc_order']    = wc_get_order( $task_meta['global']['order_id'] );
		}
		if ( isset( $set_data['wc_order'] ) ) {
			$items = $set_data['wc_order']->get_items();
		} else {
			$items = $get_data['wc_order']->get_items();
		}

		foreach ( $items as $item_id => $item ) {
			if ( $set_data['wc_single_item_id'] !== $item_id ) {
				continue;
			}
			$set_data['wc_single_item'] = $item;
		}

		BWFAN_Merge_Tag_Loader::set_data( $set_data );
	}

	public function handle_single_automation_run( $value1, $automation_id ) {
		/** If current status or order is same as the order status selected by user in automation */
		if ( isset( $value1['event_meta']['order_status'] ) && is_array( $value1['event_meta']['order_status'] ) && ( in_array( $this->to_status, $value1['event_meta']['order_status'], true ) ) ) {
			return parent::handle_single_automation_run( $value1, $automation_id );

		}
		if ( ! empty( $this->user_selected_actions ) ) {
			return parent::handle_single_automation_run( $value1, $automation_id );
		}

		$meta_automations   = $this->order->get_meta( '_bwfan_' . $this->get_slug() );
		$meta_automations   = ( ! is_array( $meta_automations ) ) ? [] : $meta_automations;
		$meta_automations[] = $automation_id;

		$meta_automations = array_filter( array_unique( $meta_automations ) );
		$this->order->update_meta_data( '_bwfan_' . $this->get_slug(), maybe_serialize( $meta_automations ) ); // Update order meta so that we can check if task for this order should be made or not on order status change hook
		$this->order->save();

		return false;
	}

	public function modify_order_statuses( $statuses ) {
		if ( isset( $statuses['wc-pending'] ) ) {
			unset( $statuses['wc-pending'] );
		}

		return $statuses;
	}

	/**
	 * @param $actions
	 *
	 * Recalculate action's execution time with respect to order date.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function recalculate_actions_time( $actions ) {
		$order_date = BWFAN_Woocommerce_Compatibility::get_order_date( $this->order );

		return $this->calculate_actions_time( $actions, $order_date );
	}

	/**
	 * validate v2 event settings
	 * @return bool
	 */
	public function validate_v2_event_settings( $automation_data ) {
		/** validate settings*/
		$current_automation_order_statuses = ( isset( $automation_data['event_meta'] ) && isset( $automation_data['event_meta']['order_status'] ) ) ? $automation_data['event_meta']['order_status'] : array();

		$task_order_status = $automation_data['to_status'];

		/** check order status with automation setting */
		if ( ! in_array( $task_order_status, $current_automation_order_statuses, true ) ) {
			return false;
		}

		/** validate order  */
		return $this->validate_order( $automation_data );
	}

	/**
	 * validate the order items for specific or any
	 *
	 * @param $automation_data
	 * @param $product_selected
	 * @param $item
	 *
	 * @return bool
	 */
	public function validate_event_order_items( $automation_data, $product_selected, $item ) {
		if ( ! $item instanceof WC_Order_Item ) {
			return false;
		}

		/** Any product case */
		if ( ! isset( $automation_data['event_meta'] ) || ! isset( $automation_data['event_meta']['order-contains'] ) || 'any' === $automation_data['event_meta']['order-contains'] ) {
			return true;
		}

		$ordered_products = [ $item->get_product_id() ];

		/** In case variation */
		$ordered_products = ( $item->get_variation_id() ) ? array_merge( $ordered_products, [ $item->get_variation_id() ] ) : $ordered_products;

		return count( array_intersect( $product_selected, $ordered_products ) ) > 0;
	}

	/**
	 * Before starting automation on a contact, validating if cart row exists
	 *
	 * @param $row
	 *
	 * @return bool
	 */
	public function validate_v2_before_start( $row ) {
		if ( empty( $row['data'] ) ) {
			return false;
		}

		$data = isset( $row['data'] ) ? json_decode( $row['data'], true ) : [];
		$data = isset( $data['global'] ) ? $data['global'] : [];

		return $this->validate_order( $data );
	}

	public function handle_automation_run_v2( $automation_id, $automation_data ) {
		/** If no start node found */
		if ( ! isset( $automation_data['start'] ) || 0 === intval( $automation_data['start'] ) ) {
			return false;
		}

		/** Global data */
		$global_data = $this->global_data;
		if ( empty( $global_data ) ) {
			return false;
		}
		$global_data = BWFAN_Common::get_global_data( $global_data );

		/** If no contact ID found, log and return */
		if ( ! isset( $global_data['global'] ) || ! isset( $global_data['global']['cid'] ) || 0 === intval( $global_data['global']['cid'] ) ) {
			BWFAN_Common::log_test_data( 'No cid found for Automation ID - ' . $automation_id . '. Event - ' . $this->get_slug(), 'no-contact-id', true );
			BWFAN_Common::log_test_data( $global_data, 'no-contact-id', true );

			return false;
		}

		$exclude_check = false;
		if ( isset( $automation_data['event_meta'] ) && isset( $automation_data['event_meta']['enter_automation_on_active_contact'] ) && 1 === absint( $automation_data['event_meta']['enter_automation_on_active_contact'] ) ) {
			$exclude_check = true;
		}

		/** set automation id in event global data */
		$global_data['global']['automation_id'] = $automation_id;

		$order_id = isset( $automation_data['order_id'] ) ? $automation_data['order_id'] : 0;
		$order    = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return false;
		}

		/** Event data */
		$event_data = $this->event_data;

		$global_data['event_data'] = ! is_array( $event_data ) ? [] : $event_data;

		/** Specific product case */
		$get_selected_product = isset( $automation_data['event_meta']['products'] ) ? $automation_data['event_meta']['products'] : [];
		$product_selected     = empty( $get_selected_product ) ? [] : array_column( $get_selected_product, 'id' );

		$insert_id = 0;
		$items     = $order->get_items();
		foreach ( $items as $item_id => $item ) {
			if ( ! $item instanceof WC_Order_Item ) {
				continue;
			}

			/** If contact is active in automation */
			if ( false === $exclude_check && BWFAN_Model_Automation_Contact::maybe_contact_in_automation( $global_data['global']['cid'], $automation_id ) ) {
				BWFAN_Common::log_test_data( 'Contact ' . $global_data['global']['cid'] . ' is active in the automation - ' . $automation_id . '. Event - ' . $this->get_slug(), 'contact-exist-automation', true );

				return false;
			}

			/** Validate automation if contact is already exists with same order */
			if ( false === $this->validate_automation( $automation_data, $item_id ) ) {
				return false;
			}

			/** Validate automation common settings like run count */
			if ( false === BWFAN_Model_Automations_V2::validation_automation_run_count( $automation_id, $global_data['global']['cid'], $automation_data, $exclude_check ) ) {
				BWFAN_Common::log_test_data( 'Automation ID ' . $automation_id . ' already run on a contact ' . $global_data['global']['cid'] . '. Event - ' . $this->get_slug(), 'contact-exist-automation', true );

				return false;
			}

			/** Validate automation event settings per item */
			if ( false === $this->validate_event_order_items( $automation_data, $product_selected, $item ) ) {
				continue;
			}

			$global_data['global']['wc_single_item_id'] = $item_id;

			$data = [
				'cid'       => intval( $global_data['global']['cid'] ),
				'aid'       => $automation_id,
				'event'     => $this->get_slug(),
				'c_date'    => current_time( 'mysql', 1 ),
				'e_time'    => current_time( 'timestamp', 1 ),
				'last_time' => current_time( 'timestamp', 1 ),
				'data'      => json_encode( $global_data )
			];

			/** In case of duplicate run, when hook runs twice. */
			$already_exists = BWFAN_Model_Automation_Contact::check_duplicate_automation_contact( $data );
			if ( $already_exists ) {
				BWFAN_Common::log_test_data( 'Automation ID ' . $data['aid'] . ' already exists with same data for contact ' . $data['cid'] . '. Event - ' . $data['event'], 'contact-duplicate-automation', true );
				BWFAN_Common::log_test_data( $global_data, 'contact-duplicate-automation', true );

				continue;
			}

			BWFAN_Model_Automation_Contact::insert( $data );
			$insert_id = BWFAN_Model_Automation_Contact::insert_id();
		}

		/** Update automation active and entered contact fields */
		$this->update_automation_contact_field( intval( $global_data['global']['cid'] ), $automation_id );
		$this->has_run = true;

		return $insert_id;
	}

	/**
	 * @param $automation_data
	 *
	 * @return bool
	 */
	public function validate_automation( $automation_data, $item_id ) {
		$this->order_id = $automation_data['order_id'];
		$global_data    = BWFAN_Common::get_global_data( $this->get_event_data() );
		$cid            = isset( $global_data['global']['contact_id'] ) ? $global_data['global']['contact_id'] : 0;
		$cid            = empty( $cid ) && isset( $global_data['global']['cid'] ) ? $global_data['global']['cid'] : $cid;
		if ( empty( $cid ) ) {
			return false;
		}

		/** Check if contact is already exists in automation with same order */
		if ( BWFAN_Common::is_contact_in_automation( $automation_data['id'], $cid, $this->order_id, $item_id, $this->get_slug() ) ) {
			return false;
		}

		return true;
	}

	/** v2 Methods: START */

	public function get_fields_schema() {
		$arr = [
			[
				'id'          => 'order_status',
				'label'       => __( 'Order Statuses', 'wp-marketing-automations' ),
				'type'        => 'checkbox_grid',
				'class'       => '',
				'placeholder' => '',
				'required'    => true,
				"errorMsg"    => 'Select at least one status.',
				'options'     => $this->get_view_data(),
				'hint'        => __( 'This automation would run on new orders product with selected statuses.', 'wp-marketing-automations' ),
			],
			[
				'id'          => 'order-contains',
				'label'       => __( 'Order Contains', 'wp-marketing-automations' ),
				'type'        => 'radio',
				'options'     => [
					[
						'label' => 'Any Product',
						'value' => 'any'
					],
					[
						'label' => 'Specific Products',
						'value' => 'selected_product'
					],
				],
				"class"       => 'bwfan-input-wrapper',
				"tip"         => "",
				"required"    => false,
				"description" => ""
			],
		];
		if ( bwfan_is_autonami_pro_active() ) {
			$arr[] = [
				'id'            => 'products',
				'label'         => __( 'Select Products', 'wp-marketing-automations' ),
				'type'          => 'search',
				'autocompleter' => 'products',
				'class'         => '',
				'placeholder'   => '',
				'required'      => true,
				'toggler'       => [
					'fields' => [
						[
							'id'    => 'order-contains',
							'value' => 'selected_product',
						]
					]
				],
			];
		} else {
			$arr[] = [
				'id'       => 'products',
				'type'     => 'notice',
				'class'    => '',
				'status'   => 'warning',
				'message'  => 'This is a Pro feature.',
				'dismiss'  => false,
				'required' => false,
				'toggler'  => [
					'fields' => [
						[
							'id'    => 'order-contains',
							'value' => 'selected_product',
						]
					]
				],
			];
		}

		return $arr;
	}

	public function get_default_values() {
		return [
			'order-contains' => 'any'
		];
	}
}

/**
 * Register this event to a source.
 * This will show the current event in dropdown in single automation screen.
 */
if ( bwfan_is_woocommerce_active() ) {
	return 'BWFAN_WC_Product_Purchased';
}

<?php

final class BWFAN_WC_New_Order extends BWFAN_Event {
	private static $instance = null;

	// Environment variables for current event
	public $order_id = null;
	/** @var WC_Order $order */
	public $order = null;
	public $to_status = null;
	public $has_run = 0;

	private function __construct() {
		$this->optgroup_label         = esc_html__( 'Orders', 'wp-marketing-automations' );
		$this->event_name             = esc_html__( 'Order Created', 'wp-marketing-automations' );
		$this->event_desc             = esc_html__( 'This event runs after a new WooCommerce order is created. Can only run once on selected WC order statuses.', 'wp-marketing-automations' );
		$this->event_merge_tag_groups = array( 'bwf_contact', 'wc_order' );
		$this->event_rule_groups      = array(
			'wc_order',
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
		$this->priority               = 15;
		$this->support_lang           = true;
		$this->v2                     = true;
		$this->is_goal                = true;
		$this->goal_name              = esc_html__( 'Product Purchased', 'wp-marketing-automations' );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function load_hooks() {
		// event trigger hooks
		add_action( 'woocommerce_new_order', [ $this, 'new_order' ], 11 );

		add_action( 'bwfan_wc_order_status_changed', array( $this, 'bwfan_order_status_changed' ), 11, 3 );

		// this action localizes the data which will be used in script template for making the UI of the event
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 98 );

		add_filter( 'bwfan_wc_event_order_status_' . $this->get_slug(), array( $this, 'modify_order_statuses' ), 10, 1 );

		// this filter tells if the logs should be made during sync process for the current event
		add_filter( 'bwfan_before_making_logs', array( $this, 'check_if_bulk_process_executing' ), 10, 1 );

		add_action( 'bwfan_sync_call_delete_tasks', array( $this, 'terminate_automations_tasks' ), 10, 2 );
	}

	/**
	 * Localize data for html fields for the current event.
	 */
	public function admin_enqueue_assets() {
		if ( false === BWFAN_Common::is_load_admin_assets( 'automation' ) ) {
			return;
		}
		$integration_data = $this->get_view_data();

		BWFAN_Core()->admin->set_events_js_data( $this->get_slug(), 'order_status_options', $integration_data );
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
        <script type="text/html" id="tmpl-event-<?php echo esc_attr( $this->get_slug() ); ?>">
            <div class="bwfan-col-sm-12 bwfan-p-0 bwfan-mt-15">
                <#
                selected_statuses = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'order_status')) ? data.eventSavedData.order_status : '';
                is_validated = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'validate_event')) ? 'checked' : '';
                terminate_on_order = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'terminate_on_order')) ? 'checked' : '';
                #>
                <label for="" class="bwfan-label-title"><?php echo esc_html__( 'Select Order Statuses', 'wp-marketing-automations' ); ?></label>
                <#
                if(_.has(data.eventFieldsOptions, 'order_status_options') && _.isObject(data.eventFieldsOptions.order_status_options) ) {
                _.each( data.eventFieldsOptions.order_status_options, function( value, key ){
                checked = '';
                if(selected_statuses!='' && _.contains(selected_statuses, key)){
                checked = 'checked';
                }
                #>
                <div class="bwfan-checkboxes">
                    <input type="checkbox" name="event_meta[order_status][]" id="bwfan-{{key}}" value="{{key}}" class="bwfan-checkbox" data-warning="<?php echo esc_html__( 'Please select atleast 1 order status', 'wp-marketing-automations' ); ?>" {{checked}}/>
                    <label for="bwfan-{{key}}" class="bwfan-checkbox-label">{{value}}</label>
                </div>
                <# })
                }
                #>
                <div class="clearfix bwfan_field_desc bwfan-pt-0">
                    This automation would run on new orders with selected statuses.
                </div>
            </div>
            <#
            if(1 == bwfanParams.pro_active){
            #>
            <div class="bwfan-col-sm-12 bwfan-p-0 bwfan-mt-15">
                <label class="bwfan-label-title">End Automation</label>
                <div>
                    <input type="checkbox" name="event_meta[terminate_on_order]" id="bwfan_end_automation" value="1" {{terminate_on_order}}/>
                    <label for="bwfan_end_automation" class="bwfan-checkbox-label"><?php esc_html_e( 'End automation if customer places the order during the automation' ) ?></label>
                </div>

            </div>
            <#
            }
            #>
			<?php
			$this->get_validation_html( $this->get_slug(), 'Validate Order status before executing task', 'Validate' );
			?>
        </script>
		<?php
	}

	/**
	 * Set up rules data
	 *
	 * @param $automation_data
	 */
	public function pre_executable_actions( $automation_data ) {
		BWFAN_Core()->rules->setRulesData( $this->event_automation_id, 'automation_id' );
		BWFAN_Core()->rules->setRulesData( $this->order, 'wc_order' );
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

		if ( BWFAN_Common::bwf_check_to_skip_child_order( $order_id ) ) {
			BWFAN_Core()->logger->log( 'Skip child order for ID - ' . $order_id . ', Event - ' . $this->get_slug() . ' and function name ' . __FUNCTION__, $this->log_type );

			return;
		}

		/** Check if v1 automations exists */
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );

		$automation_not_found = false;
		if ( ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) ) {
			$automation_not_found = true;
		}

		if ( $automation_not_found ) {
			BWFAN_Core()->logger->log( 'No active automations for order ID - ' . $order_id . ', Event - ' . $this->get_slug() . ' and function name ' . __FUNCTION__, $this->log_type );

			return;
		}

		/** For v1 */
		if ( count( $this->automations_arr ) > 0 ) {
			$order->update_meta_data( '_bwfan_' . $this->get_slug(), count( $this->automations_arr ) );
			$order->save();
		}
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
			'order_status'   => $value['event_meta']['order_status']
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
		$data_to_send                       = [];
		$data_to_send['global']['order_id'] = $this->order_id;
		$this->order                        = $this->order instanceof WC_Order ? $this->order : wc_get_order( $this->order_id );
		$data_to_send['global']['email']    = BWFAN_Common::get_email_from_order( $this->order_id, $this->order );
		$data_to_send['global']['phone']    = BWFAN_Common::get_phone_from_order( $this->order_id, $this->order );
		$user_id                            = BWFAN_Common::get_wp_user_id_from_order( $this->order_id, $this->order );
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

	public function terminate_automations_tasks( $email, $phone ) {
		if ( empty( $email ) && empty( $phone ) ) {
			return;
		}

		$event_slug  = $this->get_slug();
		$automations = BWFAN_Core()->automations->get_active_automations( 1, $event_slug );

		$selected_automations = array();
		foreach ( $automations as $automation_id => $automation ) {
			if ( isset( $automation['meta']['event_meta']['terminate_on_order'] ) && $automation['meta']['event_meta']['terminate_on_order'] ) {
				$selected_automations[] = $automation_id;
			}
		}

		$schedule_tasks = [];

		if ( ! empty( $email ) ) {
			$schedule_tasks_email = BWFAN_Common::get_schedule_task_by_email( $selected_automations, $email );
			$schedule_tasks       = array_merge( $schedule_tasks, $schedule_tasks_email );
		}

		if ( ! empty( $phone ) ) {
			$schedule_tasks_phone = BWFAN_Common::get_schedule_task_by_phone( $selected_automations, $phone );
			$schedule_tasks       = array_merge( $schedule_tasks, $schedule_tasks_phone );
		}

		$schedule_tasks = array_filter( $schedule_tasks );
		if ( 0 === count( $schedule_tasks ) ) {
			return;
		}

		$schedule_tasks = array_unique( $schedule_tasks );
		foreach ( $schedule_tasks as $tasks ) {
			if ( empty( $tasks ) ) {
				continue;
			}
			$delete_tasks = array();
			foreach ( $tasks as $task ) {
				$delete_tasks[] = $task['ID'];
			}

			BWFAN_Core()->tasks->delete_tasks( $delete_tasks );
		}
	}

	public function bwfan_order_status_changed( $order, $from_status, $to_status ) {
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
		/** No v1 or v2 automations */
		if ( ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) && ( ! is_array( $this->automations_v2_arr ) || count( $this->automations_v2_arr ) === 0 ) ) {
			$automation_not_found = true;
		}

		/** Check for goals */
		if ( true === $automation_not_found ) {
			$automation_not_found = empty( $this->get_current_goal_automations() );
		}

		if ( true === $automation_not_found ) {
			BWFAN_Core()->logger->log( 'No active automations for order ID - ' . $this->order_id . ', Event - ' . $this->get_slug() . ' and function name ' . __FUNCTION__, $this->log_type );

			return;
		}

		$to_status = 'wc-' . $to_status;

		$this->to_status = $to_status;
		$this->process( $this->order_id );
		$this->to_status = null;
	}

	/**
	 * Make the required data for the current event.
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

		/** Run v1 automation */
		if ( count( $this->automations_arr ) > 0 ) {
			$this->order->delete_meta_data( '_bwfan_' . $this->get_slug() );
			$this->order->save_meta_data();

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
                <strong><?php echo esc_html__( 'Order:', 'wp-marketing-automations' ); ?> </strong>
                <a target="_blank" href="<?php echo get_edit_post_link( $global_data['order_id'] ); //phpcs:ignore WordPress.Security.EscapeOutput
				?>"><?php echo '#' . esc_attr( $global_data['order_id'] . ' ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></a>
            </li>
		<?php } ?>
        <li>
            <strong><?php echo esc_html__( 'Email:', 'wp-marketing-automations' ); ?> </strong>
			<?php echo esc_html( $global_data['email'] ); ?>
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

		$order_id          = $task_details['processed_data']['order_id'];
		$order             = wc_get_order( $order_id );
		$task_order_status = BWFAN_Woocommerce_Compatibility::get_order_status( $order );

		if ( in_array( $task_order_status, $current_automation_order_statuses, true ) ) {
			return $this->get_automation_event_success();
		}

		$result['status']  = 4;
		$result['message'] = esc_html__( 'Order status in automation has been changed', 'wp-marketing-automations' );

		return $result;
	}

	public function validate_event_data_before_executing_task( $data ) {
		return $this->validate_order( $data );
	}

	/**
	 * validate v2 event settings
	 * @return bool
	 */
	public function validate_v2_event_settings( $automation_data ) {
		/** validate settings */
		$current_automation_order_statuses = ( isset( $automation_data['event_meta'] ) && isset( $automation_data['event_meta']['order_status'] ) ) ? $automation_data['event_meta']['order_status'] : array();

		$task_order_status = $automation_data['to_status'];

		/** check order status with automation setting */
		if ( ! in_array( $task_order_status, $current_automation_order_statuses, true ) ) {
			return false;
		}

		/** validate order  */
		if ( false === $this->validate_order( $automation_data ) ) {
			return false;
		}

		/** Validate automation if contact is already exists with same order */
		if ( false === $this->validate_automation( $automation_data ) ) {
			return false;
		}

		return BWFAN_Common::validate_create_order_event_setting( $automation_data );
	}

	/**
	 * @param $automation_data
	 *
	 * @return bool
	 */
	public function validate_automation( $automation_data ) {
		$this->order_id = $automation_data['order_id'];
		$global_data    = BWFAN_Common::get_global_data( $this->get_event_data() );
		$cid            = isset( $global_data['global']['contact_id'] ) ? $global_data['global']['contact_id'] : 0;
		$cid            = empty( $cid ) && isset( $global_data['global']['cid'] ) ? $global_data['global']['cid'] : $cid;
		if ( empty( $cid ) ) {
			return false;
		}

		/** Check if contact is already exists in automation with same order */
		if ( BWFAN_Common::is_contact_in_automation( $automation_data['id'], $cid, $this->order_id, '', $this->get_slug() ) ) {
			return false;
		}

		return true;
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

	public function validate_goal_settings( $automation_settings, $automation_data ) {
		if ( ! is_array( $automation_settings ) || ! is_array( $automation_data ) || ! isset( $automation_settings['order_status'] ) || ! isset( $automation_data['order_id'] ) ) {
			return false;
		}

		$order_id = absint( $automation_data['order_id'] );
		$order    = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return false;
		}

		$status_selected = $automation_settings['order_status'];
		if ( ! is_array( $status_selected ) || empty( $status_selected ) ) {
			return false;
		}

		$order_status = $automation_data['to_status'];
		$order_status = empty( $order_status ) ? $order->get_status() : $order_status;

		/** Order status not matched */
		if ( ! in_array( $order_status, $status_selected ) ) {
			return false;
		}

		/** Any product case */
		if ( 'any' === $automation_settings['order-contains'] ) {
			return true;
		}

		/** Specific product case */
		$get_selected_product = $automation_settings['products'];
		$order_items          = $order->get_items();
		$ordered_products     = array();
		foreach ( $order_items as $item ) {
			$product_id         = $item->get_product_id();
			$ordered_products[] = $product_id;

			/** In case variation */
			if ( $item->get_variation_id() ) {
				$ordered_products[] = $item->get_variation_id();
			}
		}

		$product_selected = empty( $get_selected_product ) ? [] : array_column( $get_selected_product, 'id' );
		$ordered_products = array_unique( $ordered_products );
		sort( $ordered_products );

		return count( array_intersect( $product_selected, $ordered_products ) ) > 0;
	}

	/**
	 * Capture the async data for the current event.
	 *
	 * @return array|bool|void
	 */
	public function capture_async_data() {
		$order_id = BWFAN_Common::$events_async_data['order_id'];
		if ( isset( BWFAN_Common::$events_async_data['to_status'] ) ) {
			$this->to_status = BWFAN_Common::$events_async_data['to_status'];
		}
		$this->order_id = $order_id;

		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$order->delete_meta_data( '_bwfan_' . $this->get_slug() );
		$order->save();

		$this->order = $order;

		return $this->run_automations();
	}

	/**
	 * Get Contact ID for goal
	 */
	public function get_contact_id_for_goal( $capture_args ) {
		if ( ! is_array( $capture_args ) || ! isset( $capture_args['order_id'] ) || empty( $capture_args['order_id'] ) || ! bwfan_is_woocommerce_active() ) {
			return 0;
		}

		$order = wc_get_order( absint( $capture_args['order_id'] ) );
		if ( ! $order instanceof WC_Order ) {
			return 0;
		}

		$email   = $order->get_billing_email();
		$user_id = $order->get_user_id();
		$contact = new WooFunnels_Contact( $user_id, $email );
		if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
			return 0;
		}

		return absint( $contact->get_id() );
	}

	public function handle_single_automation_run( $automation_data, $automation_id ) {
		/** If current status or order is same as the order status selected by user in automation */
		if ( isset( $automation_data['event_meta']['order_status'] ) && is_array( $automation_data['event_meta']['order_status'] ) && ( in_array( $this->to_status, $automation_data['event_meta']['order_status'], true ) ) ) {
			return parent::handle_single_automation_run( $automation_data, $automation_id );
		}
		/** History sync handling */
		if ( ! empty( $this->user_selected_actions ) ) {
			return parent::handle_single_automation_run( $automation_data, $automation_id );
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
		unset( $statuses['wc-pending'] );

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
				'hint'        => __( 'This automation would run on new orders with selected statuses.', 'wp-marketing-automations' ),
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

	/**
	 * Goal schema
	 *
	 * @return array[]
	 */
	public function get_goal_fields_schema() {
		return [
			[
				'id'          => 'order_status',
				'label'       => __( 'Order Statuses', 'wp-marketing-automations' ),
				'type'        => 'checkbox_grid',
				'class'       => '',
				'placeholder' => '',
				'required'    => true,
				"errorMsg"    => 'Select at least one status.',
				'options'     => $this->get_view_data(),
				'hint'        => __( 'The goal will be met if the new order has the selected order status.', 'wp-marketing-automations' ),
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
				"required"    => true,
				"description" => ""
			],
			[
				'id'            => 'products',
				'label'         => __( 'Select Products', 'wp-marketing-automations' ),
				'type'          => 'search',
				'autocompleter' => 'products',
				'class'         => '',
				'placeholder'   => '',
				'required'      => false,
				'toggler'       => [
					'fields' => [
						[
							'id'    => 'order-contains',
							'value' => 'selected_product',
						]
					]
				],
			],
		];
	}

	/**
	 * Default values for goal values
	 *
	 * @return array
	 */
	public function get_default_goal_values() {
		return [
			'order-contains' => 'any'
		];
	}

	public function get_desc_text( $data ) {
		$data = json_decode( wp_json_encode( $data ), true );
		if ( ! isset( $data['order_status'] ) || empty( $data['order_status'] ) ) {
			return '';
		}
		$statues = [];
		$options = $this->get_view_data();
		foreach ( $data['order_status'] as $status ) {
			if ( ! isset( $options[ $status ] ) || empty( $options[ $status ] ) ) {
				continue;
			}
			$statues[] = $options[ $status ];
		}

		return $statues;
	}

	/** v2 Methods: END */

	/** set default values */
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
	return 'BWFAN_WC_New_Order';
}

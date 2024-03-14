<?php

final class BWFAN_WC_Order_Status_Change extends BWFAN_Event {
	private static $instance = null;
	public $order_id = null;
	public $from_status = null;
	public $to_status = null;
	public $order = null;

	private function __construct() {
		$this->optgroup_label         = esc_html__( 'Orders', 'wp-marketing-automations' );
		$this->event_name             = esc_html__( 'Order Status Changed', 'wp-marketing-automations' );
		$this->event_desc             = esc_html__( 'This event runs after an order status is changed.', 'wp-marketing-automations' );
		$this->event_merge_tag_groups = array( 'bwf_contact', 'wc_order' );
		$this->event_rule_groups      = array(
			'wc_order',
			'aerocheckout',
			'wc_order_state',
			'bwf_contact_segments',
			'bwf_contact',
			'bwf_contact_fields',
			'bwf_contact_user',
			'bwf_contact_wc',
			'bwf_contact_geo',
			'bwf_engagement',
			'bwf_broadcast'
		);
		$this->priority               = 15.2;
		$this->support_lang           = true;
		$this->v2                     = true;
	}

	public function load_hooks() {
		add_action( 'bwfan_wc_order_status_changed', array( $this, 'order_status_changed' ), 11, 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 98 );
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
		if ( false === BWFAN_Common::is_load_admin_assets( 'automation' ) ) {
			return;
		}
		$wc_order_statuses    = $this->get_view_data();
		$to_wc_order_statuses = $this->get_view_data();
		if ( isset( $to_wc_order_statuses['wc-pending'] ) ) {
			unset( $to_wc_order_statuses['wc-pending'] );
		}
		BWFAN_Core()->admin->set_events_js_data( $this->get_slug(), 'from_options', $wc_order_statuses );
		BWFAN_Core()->admin->set_events_js_data( $this->get_slug(), 'to_options', $to_wc_order_statuses );
	}

	public function get_view_data() {
		return wc_get_order_statuses();
	}

	/**
	 * Show the html fields for the current event.
	 */
	public function get_view( $db_eventmeta_saved_value ) {
		?>
        <script type="text/html" id="tmpl-event-<?php esc_attr_e( $this->get_slug() ); ?>">
            <#
            is_validated = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'validate_event')) ? 'checked' : '';
            selected_from_status = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'from')) ? data.eventSavedData.from : '';
            selected_to_status = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'to')) ? data.eventSavedData.to : '';
            #>
            <div class="bwfan_mt15"></div>
            <div class="bwfan-col-sm-6 bwfan-pl-0">
                <label for="" class="bwfan-label-title"><?php esc_html_e( 'From Status', 'wp-marketing-automations' ); ?></label>
                <select required id="" class="bwfan-input-wrapper" name="event_meta[from]">
                    <option value="wc-any"><?php esc_html_e( 'Any', 'wp-marketing-automations' ); ?></option>
                    <#
                    if(_.has(data.eventFieldsOptions, 'from_options') && _.isObject(data.eventFieldsOptions.from_options) ) {
                    _.each( data.eventFieldsOptions.from_options, function( value, key ){
                    selected = (key == selected_from_status) ? 'selected' : '';
                    #>
                    <option value="{{key}}" {{selected}}>{{value}}</option>
                    <# })
                    }
                    #>
                </select>
            </div>
            <div class="bwfan-col-sm-6 bwfan-pr-0">
                <label for="" class="bwfan-label-title"><?php esc_html_e( 'To Status', 'wp-marketing-automations' ); ?></label>
                <select required id="" class="bwfan-input-wrapper" name="event_meta[to]">
                    <option value="wc-any"><?php esc_html_e( 'Any', 'wp-marketing-automations' ); ?></option>
                    <#
                    if(_.has(data.eventFieldsOptions, 'to_options') && _.isObject(data.eventFieldsOptions.to_options) ) {
                    _.each( data.eventFieldsOptions.to_options, function( value, key ){
                    selected = (key == selected_to_status) ? 'selected' : '';
                    #>
                    <option value="{{key}}" {{selected}}>{{value}}</option>
                    <# })
                    }
                    #>
                </select>
            </div>
			<?php
			$this->get_validation_html( $this->get_slug(), 'Validate order status before executing task', 'Validate' );
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
		$order       = wc_get_order( $this->order_id );
		$this->order = $order;
		BWFAN_Core()->rules->setRulesData( $this->order, 'wc_order' );
		BWFAN_Core()->rules->setRulesData( $this->event_automation_id, 'automation_id' );
		BWFAN_Core()->rules->setRulesData( BWFAN_Common::get_bwf_customer( $this->order->get_billing_email(), $this->order->get_user_id() ), 'bwf_customer' );
	}

	public function handle_single_automation_run( $value1, $automation_id ) {
		$is_register_task = false;
		$to_status        = $this->to_status;
		$from_status      = $this->from_status;
		$event_meta       = $value1['event_meta'];
		$from             = str_replace( 'wc-', '', $event_meta['from'] );
		$to               = str_replace( 'wc-', '', $event_meta['to'] );

		if ( 'any' === $from && 'any' === $to ) {
			$is_register_task = true;
		} elseif ( 'any' === $from && $to_status === $to ) {
			$is_register_task = true;
		} elseif ( $from_status === $from && 'any' === $to ) {
			$is_register_task = true;
		} elseif ( $from_status === $from && $to_status === $to ) {
			$is_register_task = true;
		}

		if ( $is_register_task ) {
			$all_statuses        = wc_get_order_statuses();
			$value1['from']      = $all_statuses[ 'wc-' . $from_status ];
			$value1['from_slug'] = 'wc-' . $from_status;
			$value1['to']        = $all_statuses[ 'wc-' . $to_status ];
			$value1['to_slug']   = 'wc-' . $to_status;

			return parent::handle_single_automation_run( $value1, $automation_id );
		}

		return '';
	}

	public function order_status_changed( $order, $from_status, $to_status ) {
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$this->order_id    = $order->get_id();
		$this->order       = $order;
		$this->from_status = $from_status;
		$this->to_status   = $to_status;
		if ( BWFAN_Common::bwf_check_to_skip_child_order( $this->order_id ) ) {
			return;
		}
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );

		$this->process( $this->order_id, $from_status, $to_status );
	}

	/**
	 * Make the required data for the current event and send it asynchronously.
	 *
	 * @param $order_id
	 * @param $from_status
	 * @param $to_status
	 */
	public function process( $order_id, $from_status, $to_status ) {
		$data                = $this->get_default_data();
		$data['order_id']    = $order_id;
		$data['from_status'] = $from_status;
		$data['to_status']   = $to_status;
		$data['email']       = $this->order->get_billing_email();
		$data['phone']       = $this->order->get_billing_phone();

		$all_statuses      = wc_get_order_statuses();
		$data['from']      = $all_statuses[ 'wc-' . $this->from_status ];
		$data['from_slug'] = 'wc-' . $this->from_status;
		$data['to']        = $all_statuses[ 'wc-' . $this->to_status ];
		$data['to_slug']   = 'wc-' . $this->to_status;

		/** Run v2 automation */
		BWFAN_Common::maybe_run_v2_automations( $this->get_slug(), $data );

		/** Run v1 automation */
		if ( count( $this->automations_arr ) > 0 ) {
			$this->run_automations();
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
		$event_meta = $value['event_meta'];

		return [
			'event_source'   => $value['source'],
			'event_slug'     => $value['event'],
			'validate_event' => ( isset( $value['event_meta']['validate_event'] ) ) ? 1 : 0,
			'from_status'    => $event_meta['from'],
			'to_status'      => $event_meta['to'],
			'from'           => $value['from'],
			'from_slug'      => $value['from_slug'],
			'to'             => $value['to'],
			'to_slug'        => $value['to_slug'],
		];
	}

	/**
	 * Registers the tasks for current event.
	 *
	 * @param $automation_id
	 * @param $actions : after processing events data
	 * @param $event_data
	 */
	public function register_tasks( $automation_id, $actions, $event_data ) {
		if ( ! is_array( $actions ) ) {
			return;
		}

		$data_to_send = $this->get_event_data( $event_data );
		$this->create_tasks( $automation_id, $actions, $event_data, $data_to_send );
	}

	public function get_event_data( $event_data = array() ) {
		$data_to_send                       = [];
		$data_to_send['global']['order_id'] = $this->order_id;
		$data_to_send['global']['from']     = isset( $event_data['from'] ) ? $event_data['from'] : '';
		$data_to_send['global']['to']       = isset( $event_data['to'] ) ? $event_data['to'] : '';

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
                <strong><?php esc_html_e( 'Order:', 'wp-marketing-automations' ); ?> </strong>
                <a target="_blank" href="<?php echo get_edit_post_link( $global_data['order_id'] ); //phpcs:ignore WordPress.Security.EscapeOutput
				?>"><?php echo '#' . esc_html( $global_data['order_id'] . ' ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></a>
            </li>
		<?php } ?>
        <li>
            <strong><?php esc_html_e( 'Email:', 'wp-marketing-automations' ); ?> </strong>
			<?php esc_html_e( $global_data['email'] ); ?>
        </li>
        <li>
            <strong><?php esc_html_e( 'From Status:', 'wp-marketing-automations' ); ?> </strong>
			<?php esc_html_e( $global_data['from'] ); ?>
        </li>
        <li>
            <strong><?php esc_html_e( 'To Status:', 'wp-marketing-automations' ); ?> </strong>
			<?php esc_html_e( $global_data['to'] ); ?>
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
		$result        = [];
		$task_event    = $task_details['event_data']['event_slug'];
		$automation_id = $task_details['processed_data']['automation_id'];

		$automation_details                         = BWFAN_Model_Automations::get_automation_with_data( $automation_id );
		$current_automation_event                   = $automation_details['event'];
		$current_automation_event_meta              = $automation_details['meta']['event_meta'];
		$current_automation_event_validation_status = ( isset( $current_automation_event_meta['validate_event'] ) ) ? $current_automation_event_meta['validate_event'] : 0;
		$current_automation_status_to               = $current_automation_event_meta['to'];

		if ( 'wc-any' === $current_automation_event_meta['from'] && 'wc-any' === $current_automation_event_meta['to'] ) {
			return $this->get_automation_event_success();
		}

		/** Using current automation 'order to' state rather than saved one in the task */

		/** Current automation has no checking */
		if ( 0 === $current_automation_event_validation_status ) {
			return $this->get_automation_event_validation();
		}

		/** Current automation event does not match with the event of task when the task was made */
		if ( $task_event !== $current_automation_event ) {
			return $this->get_automation_event_status();
		}

		$order_id          = $task_details['processed_data']['order_id'];
		$order             = wc_get_order( $order_id );
		$task_order_status = BWFAN_Woocommerce_Compatibility::get_order_status( $order );

		if ( $task_order_status === $current_automation_status_to ) {
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
		$wc_order_id = BWFAN_Merge_Tag_Loader::get_data( 'wc_order_id' );
		if ( empty( $wc_order_id ) || $wc_order_id !== $task_meta['global']['order_id'] ) {
			$set_data = array(
				'wc_order_id' => $task_meta['global']['order_id'],
				'email'       => $task_meta['global']['email'],
				'wc_order'    => wc_get_order( $task_meta['global']['order_id'] ),
			);
			BWFAN_Merge_Tag_Loader::set_data( $set_data );
		}
	}

	/**
	 * validate v2 event settings
	 * @return bool
	 */
	public function validate_v2_event_settings( $automation_data ) {
		if ( ! isset( $automation_data['event_meta'] ) || empty( $automation_data['event_meta'] ) || ! is_array( $automation_data['event_meta'] ) ) {
			return false;
		}
		$current_automation_event_meta  = $automation_data['event_meta'];
		$current_automation_status_to   = isset( $current_automation_event_meta['to'] ) ? $current_automation_event_meta['to'] : '';
		$current_automation_status_from = isset( $current_automation_event_meta['from'] ) ? $current_automation_event_meta['from'] : '';
		$current_order_contains         = isset( $current_automation_event_meta['order-contains'] ) ? $current_automation_event_meta['order-contains'] : '';
		if ( ! $this->validate_order( $automation_data ) ) {
			return false;
		}

		/** from any to any status checking */
		if ( ( 'wc-any' === $current_automation_status_from && 'wc-any' === $current_automation_status_to ) && ( empty( $current_order_contains ) || 'any' === $current_order_contains ) ) {
			return true;
		}

		/** Specific product case */
		if ( 'selected_product' === $current_order_contains ) {
			$order_id    = absint( $automation_data['order_id'] );
			$order       = wc_get_order( $order_id );
			$order_items = $order->get_items();

			$ordered_products = array();
			foreach ( $order_items as $item ) {
				$product_id         = $item->get_product_id();
				$ordered_products[] = $product_id;

				/** In case variation */
				if ( $item->get_variation_id() ) {
					$ordered_products[] = $item->get_variation_id();
				}
			}
			$ordered_products = array_unique( $ordered_products );

			$get_selected_product = $automation_data['event_meta']['products'];
			$product_selected     = array_column( $get_selected_product, 'id' );

			$check_products = count( array_intersect( $product_selected, $ordered_products ) );

			/** No selected products found */
			if ( $check_products <= 0 ) {
				return false;
			}
		}

		$order_status_from = 'wc-' . $automation_data['from_status'];
		$order_status_to   = 'wc-' . $automation_data['to_status'];

		/** checking from any to any status */
		if ( 'wc-any' === $current_automation_status_from && 'wc-any' === $current_automation_status_to ) {
			return true;
		}

		/** checking any status to selected status */
		if ( 'wc-any' === $current_automation_status_from ) {
			return ( $order_status_to === $current_automation_status_to );
		}

		/** checking selected status to any status */
		if ( 'wc-any' === $current_automation_status_to ) {
			return ( $order_status_from === $current_automation_status_from );
		}

		/** checking selected status to selected status */
		return ( ( $order_status_from === $current_automation_status_from ) && ( $order_status_to === $current_automation_status_to ) );
	}

	/**
	 * v2 Method: Get fields schema
	 * @return array[][]
	 */
	public function get_fields_schema() {
		$default = [
			'wc-any' => 'Any'
		];
		$status  = array_replace( $default, $this->get_view_data() );
		$status  = BWFAN_Common::prepared_field_options( $status );

		$arr = [
			[
				'id'          => 'from',
				'type'        => 'wp_select',
				'options'     => $status,
				'placeholder' => __( 'Select Status', 'wp-marketing-automations' ),
				'label'       => __( 'From Status', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper bwf-3-col-item',
				"required"    => true,
				"description" => ""
			],
			[
				'id'          => 'to',
				'type'        => 'wp_select',
				'options'     => $status,
				'label'       => __( 'To Status', 'wp-marketing-automations' ),
				'placeholder' => __( 'Select Status', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper bwf-3-col-item',
				"required"    => true,
				"description" => ""
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

	/** set default values */
	public function get_default_values() {
		return [
			'from'           => 'wc-any',
			'to'             => 'wc-any',
			'order-contains' => 'any',
		];
	}

}

/**
 * Register this event to a source.
 * This will show the current event in dropdown in single automation screen.
 */
if ( bwfan_is_woocommerce_active() ) {
	return 'BWFAN_WC_Order_Status_Change';
}

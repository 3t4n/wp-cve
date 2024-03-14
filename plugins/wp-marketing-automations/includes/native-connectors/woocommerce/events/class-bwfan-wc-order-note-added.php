<?php

/**
 *
 * This class work only for admin created note `not for checkout order not field`
 * Class BWFAN_WC_Order_Note_Added\
 */
final class BWFAN_WC_Order_Note_Added extends BWFAN_Event {
	private static $instance = null;
	public $order_id = null;
	public $order = null;
	private $is_customer_note = false;
	private $comment_content = '';
	private $order_note_types = [];

	private function __construct() {
		$this->optgroup_label         = esc_html__( 'Orders', 'wp-marketing-automations' );
		$this->event_name             = esc_html__( 'Order Note Added', 'wp-marketing-automations' );
		$this->event_desc             = esc_html__( 'This event runs after a new order note is added.', 'wp-marketing-automations' );
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
		$this->priority               = 15.5;
		$this->support_lang           = true;
		$this->order_note_types       = [
			'both'    => __( 'Both', 'wp-marketing-automations' ),
			'private' => __( 'Private', 'wp-marketing-automations' ),
			'public'  => __( 'Customer', 'wp-marketing-automations' ),
		];
		$this->v2                     = true;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function load_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 98 );
		add_filter( 'woocommerce_new_order_note_data', [ $this, 'process_note' ], 10, 2 );
	}

	/**
	 * Localize data for html fields for the current event.
	 */
	public function admin_enqueue_assets() {
		BWFAN_Core()->admin->set_events_js_data( $this->get_slug(), 'order_note_type', $this->order_note_types );
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
		];
	}

	public function validate_event_data_before_executing_task( $data ) {
		return $this->validate_order( $data );
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
		<?php if ( isset( $global_data['current_order_note'] ) ) { ?>
            <li>
                <strong><?php esc_html_e( 'Content:', 'wp-marketing-automations' ); ?> </strong>
				<?php echo $global_data['current_order_note']; //phpcs:ignore WordPress.Security.EscapeOutput ?>
            </li>
		<?php } ?>
        <li>
            <strong><?php esc_html_e( 'Type:', 'wp-marketing-automations' ); ?> </strong>
			<?php

			if ( wc_string_to_bool( $global_data['order_customer_note_type'] ) ) {
				esc_html_e( 'Note to customer', 'woocommerce' );
			} else {
				esc_html_e( 'Private note', 'woocommerce' );
			}
			?>
        </li>
		<?php
		return ob_get_clean();
	}

	/**
	 * Show the html fields for the current event.
	 */
	public function get_view( $db_eventmeta_saved_value ) {
		?>
        <script type="text/html" id="tmpl-event-<?php esc_attr_e( $this->get_slug() ); ?>">
            <div class="bwfan_mt15"></div>
            <label for="bwfan-select-box-order-note" class="bwfan-label-title"><?php esc_html_e( 'Select Order Note Mode', 'wp-marketing-automations' ); ?></label>
            <div class="bwfan-select-box">
                <#
                selected_statuses = (_.has(data, 'eventSavedData') &&_.has(data.eventSavedData, 'bwfan_order_note_type')) ? data.eventSavedData.bwfan_order_note_type : 'both';
                #>
                <select name="event_meta[bwfan_order_note_type]" id="bwfan-select-box-order-note" class="bwfan-input-wrapper">
                    <#
                    if(_.has(bwfan_events_js_data, 'wc_order_note_added') && _.isObject(bwfan_events_js_data.wc_order_note_added.order_note_type) ) {
                    _.each( bwfan_events_js_data.wc_order_note_added.order_note_type, function( title, key ){
                    selected = (key == selected_statuses) ? 'selected' : '';
                    #>
                    <option value="{{key}}" {{selected}}>{{title}}</option>
                    <# })
                    }
                    #>
                </select>
            </div>
        </script>
		<?php
	}

	/**
	 * Admin add customer note
	 *
	 * @param $comment_data
	 * @param $data
	 *
	 * @return mixed
	 */
	public function process_note( $comment_data, $data ) {
		$order = wc_get_order( $comment_data['comment_post_ID'] );
		if ( ! $order instanceof WC_Order || 'shop_order' !== $order->get_type() || $comment_data['comment_type'] !== 'order_note' ) {
			return $comment_data;
		}

		$data['comment_content'] = $comment_data['comment_content'];
		$this->process( $data );

		return $comment_data;
	}

	/**
	 * Make the required data for the current event and send it asynchronously.
	 *
	 * @param $comment_data
	 */
	public function process( $comment_data ) {
		$data                     = $this->get_default_data();
		$data['order_id']         = $comment_data['order_id'];
		$data['comment_content']  = $comment_data['comment_content'];
		$data['is_customer_note'] = $comment_data['is_customer_note'];

		$this->send_async_call( $data );
	}

	/**
	 * Capture the async data for the current event.
	 * @return array|bool
	 */
	public function capture_async_data() {
		$order_id               = BWFAN_Common::$events_async_data['order_id'];
		$this->comment_content  = BWFAN_Common::$events_async_data['comment_content'];
		$this->is_customer_note = BWFAN_Common::$events_async_data['is_customer_note'];
		$this->order_id         = $order_id;
		$this->order            = wc_get_order( $order_id );

		return $this->run_automations();
	}

	public function run_automations() {
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );

		if ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) {
			BWFAN_Core()->logger->log( 'Async callback: No active automations found. Event - ' . $this->get_slug(), $this->log_type );

			return false;
		}

		$automation_actions = [];
		foreach ( $this->automations_arr as $automation_id => $value1 ) {
			if ( $this->get_slug() !== $value1['event'] ) {
				continue;
			}
			if ( ! isset( $value1['actions'] ) || empty( $value1['actions'] ) ) {
				continue;
			}

			$ran_actions                          = $this->handle_single_automation_run( $value1, $automation_id );
			$automation_actions[ $automation_id ] = $ran_actions;
		}

		return $automation_actions;
	}

	/**
	 * Set up rules data
	 *
	 * @param $value
	 */
	public function pre_executable_actions( $value ) {
		BWFAN_Core()->rules->setRulesData( $this->event_automation_id, 'automation_id' );
		BWFAN_Core()->rules->setRulesData( $this->order, 'wc_order' );
		BWFAN_Core()->rules->setRulesData( $this->comment_content, 'wc_order_note' );
		BWFAN_Core()->rules->setRulesData( BWFAN_Common::get_bwf_customer( $this->order->get_billing_email(), $this->order->get_user_id() ), 'bwf_customer' );
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

		$meta = BWFAN_Model_Automationmeta::get_meta( $automation_id, 'event_meta' );

		if ( '' === $meta || ! is_array( $meta ) || ! isset( $meta['bwfan_order_note_type'] ) ) {
			return;
		}

		/** @var bool $note_type - if true then public */
		$note_type = wc_string_to_bool( $this->is_customer_note );
		$save_type = $meta['bwfan_order_note_type'];

		$register_task = false;
		if ( 'both' === $save_type ) {
			$register_task = true;
		} elseif ( 'public' === $save_type && true === $note_type ) {
			$register_task = true;
		} elseif ( 'private' === $save_type && true !== $note_type ) {
			$register_task = true;
		}

		if ( false === $register_task ) {
			return;
		}

		$data_to_send = $this->get_event_data( $save_type );

		$this->create_tasks( $automation_id, $integration_data, $event_data, $data_to_send );
	}

	public function get_event_data( $save_type = '' ) {
		$data_to_send                                       = [];
		$data_to_send['global']['order_id']                 = $this->order_id;
		$data_to_send['global']['current_order_note']       = $this->comment_content;
		$data_to_send['global']['order_customer_note_type'] = $this->is_customer_note;
		if ( ! is_array( $save_type ) ) {
			$data_to_send['global']['order_save_note_type'] = $save_type;
		}

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

	public function set_merge_tags_data( $task_meta ) {
		parent::set_merge_tags_data( $task_meta );

		$merge_data = BWFAN_Merge_Tag_Loader::get_data();

		$merge_data['current_order_note'] = $task_meta['global']['current_order_note'];
		BWFAN_Merge_Tag_Loader::set_data( $merge_data );
	}

	/**
	 * Validate v2 event settings
	 *
	 * @param $automation_data
	 *
	 * @return bool
	 */
	public function validate_v2_event_settings( $automation_data ) {
		if ( ! isset( $automation_data['version'] ) || 2 !== intval( $automation_data['version'] ) ) {
			return false;
		}

		/** Checking if valid order */
		if ( ! $this->validate_order( $automation_data ) ) {
			return false;
		}

		if ( 'both' === $automation_data['event_meta'] ['bwfan_order_note_type'] ) {
			return $this->note_text_matches( $automation_data );
		}

		if ( 'public' === $automation_data['event_meta'] ['bwfan_order_note_type'] && $automation_data['is_customer_note'] ) {
			return $this->note_text_matches( $automation_data );
		}

		if ( 'private' === $automation_data['event_meta'] ['bwfan_order_note_type'] && ! $automation_data['is_customer_note'] ) {
			return $this->note_text_matches( $automation_data );
		}

		return false;
	}

	/**
	 * Validate the note text
	 *
	 * @param $automation_data
	 *
	 * @return bool
	 */
	protected function note_text_matches( $automation_data ) {
		if ( ! isset( $automation_data['event_meta'] ) || ! isset( $automation_data['event_meta']['bwfan_note_text_matches'] ) || empty( trim( $automation_data['event_meta']['bwfan_note_text_matches'] ) ) ) {
			return true;
		}

		$text_to_match = $automation_data['event_meta']['bwfan_note_text_matches'];
		$text_to_match = trim( $text_to_match );

		if ( false !== strpos( $automation_data['comment_content'], $text_to_match ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Capture the async data for the current event.
	 * @return array|bool
	 */
	public function capture_v2_data( $automation_data ) {
		$this->order_id         = BWFAN_Common::$events_async_data['order_id'];
		$this->comment_content  = BWFAN_Common::$events_async_data['comment_content'];
		$this->is_customer_note = BWFAN_Common::$events_async_data['is_customer_note'];

		$automation_data['order_id']         = $this->order_id;
		$automation_data['comment_content']  = $this->comment_content;
		$automation_data['is_customer_note'] = $this->is_customer_note;

		return $automation_data;
	}

	/**
	 * v2 Method: Get fields schema
	 * @return array[][]
	 */
	public function get_fields_schema() {
		$note_types = BWFAN_Common::prepared_field_options( $this->order_note_types );

		return [
			[
				'id'          => 'bwfan_order_note_type',
				'type'        => 'wp_select',
				'options'     => $note_types,
				'label'       => __( 'Select Order Note Mode', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => true,
				"errorMsg"    => 'Select note type.',
				"description" => ""
			],
			[
				'id'          => 'bwfan_note_text_matches',
				'type'        => 'text',
				'label'       => __( 'Note Text Matches', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => '',
				"required"    => false,
				"description" => ""
			],
		];
	}

	/** set default values */
	public function get_default_values() {
		return [
			'bwfan_order_note_type' => 'both',
		];
	}

}

/**
 * Register this event to a source.
 * This will show the current event in dropdown in single automation screen.
 */
if ( bwfan_is_woocommerce_active() ) {
	return 'BWFAN_WC_Order_Note_Added';
}

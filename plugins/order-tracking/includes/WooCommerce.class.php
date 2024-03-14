<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdotpWooCommerce' ) ) {
/**
 * Class to handle interactions with the WooCommerce platform
 *
 * @since 3.0.0
 */
class ewdotpWooCommerce {

	public function __construct() {
		
		add_action( 'init', array( $this, 'add_hooks' ) );
	}

	/**
	 * Adds in the necessary hooks to handle WooCommerce integration
	 * @since 3.0.0
	 */
	public function add_hooks() {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'woocommerce-integration' ) ) ) { return; }

		add_action( 'woocommerce_checkout_order_processed', array( $this, 'add_order' ) );
		add_action( 'woocommerce_order_status_changed', 	array( $this, 'update_order' ) );

		if ( $ewd_otp_controller->settings->get_setting( 'woocommerce-replace-statuses' ) ) {

			add_action( 'wc_order_statuses', array( $this, 'filter_statuses' ) );

			add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_custom_status_bulk_actions' ), 99 );

			add_filter( 'woocommerce_payment_complete_order_status', 				array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_valid_order_statuses_for_order_again', 		array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_valid_order_statuses_for_cancel', 				array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_bacs_process_payment_order_status', 			array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_default_order_status', 						array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_valid_order_statuses_for_payment', 			array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_valid_order_statuses_for_payment_complete', 	array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_valid_order_statuses_for_cancel', 				array( $this, 'get_equivalent_status' ) );
			add_filter( 'woocommerce_reports_order_statuses', 						array( $this, 'get_equivalent_status' ) );

			add_filter( 'woocommerce_reports_get_order_report_data_args', array( $this, 'report_parent_statuses' ) );
		}

		if ( $ewd_otp_controller->settings->get_setting( 'woocommerce-show-on-order-page' ) ) {

			add_action( 'woocommerce_order_details_after_order_table', array( $this, 'add_tracking_to_order_page' ) );
		}

		if ( $ewd_otp_controller->settings->get_setting( 'woocommerce-locations-enabled' ) ) {

			add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'add_order_location' ) );
			add_action( 'save_post_shop_order', array( $this, 'save_wc_location' ), 1 );
		}
	}

	/**
	 * Automatically create an OTP order after WC checkout, if enabled
	 * @since 3.0.0
	 */
	public function add_order( $post_id ) {
		global $ewd_otp_controller;
	
		if ( get_post_type( $post_id ) != 'shop_order' ) { return; }

		$woocommerce_order = new WC_Order( $post_id );

		$order = new ewdotpOrder();

		$order->name = __( 'WooCommerce Order #', 'order-tracking' ) . $post_id;
		$order->number = $ewd_otp_controller->settings->get_setting( 'woocommerce-prefix' ) . $post_id . ( ! $ewd_otp_controller->settings->get_setting( 'woocommerce-disable-random-suffix' ) ? ewd_random_string( 4 ) : '' );
		$order->email = get_post_meta( $post_id, '_billing_email', true );

		$order->display = true;

		$order->woocommerce_id = $post_id;
		$order->payment_completed = true;

		$order->status = $order->external_status = $this->get_wc_status( get_post_status( $post_id ) );

		if ( $woocommerce_order->get_customer_id() ) { $order->customer = $ewd_otp_controller->customer_manager->get_customer_id_from_wp_id( $woocommerce_order->get_customer_id() ); }
		else { $order->customer = $ewd_otp_controller->customer_manager->get_customer_id_from_name( get_post_meta($post_id, '_billing_first_name', true ) . ' ' . get_post_meta($post_id, '_billing_last_name', true ) ); }

		$custom_fields = get_option( 'ewd-otp-custom-fields' );

		foreach ( $custom_fields  as $custom_field ) {

			if ( $custom_field->equivalent == 'none' ) { continue; }

			$order->custom_fields[ $custom_field->id ] = get_post_meta( $post_id, $custom_field->equivalent, true );
		}

		$order->insert_order();

		$order->insert_order_status();

		do_action( 'ewd_otp_admin_order_inserted', $this );
	}

	/**
	 * Update an WC order's OTP equivalent, when the WC order gets a new status
	 * @since 3.0.0
	 */
	public function update_order( $post_id, $old_status = '', $new_status = '' ) {
		global $ewd_otp_controller;
		
		if ( get_post_type( $post_id ) != 'shop_order' ) { return; }

		$woocommerce_order = $ewd_otp_controller->order_manager->get_order_from_woocommerce_id( $post_id );

		if ( ! $woocommerce_order ) { return; }
		
		$order = new ewdotpOrder();
		$order->load_order( $woocommerce_order );

		$order->set_status( $this->get_wc_status( get_post_status( $post_id ) ) );
	}

	/**
	 * Replace the WC statuses with the OTP statuses for WC products
	 * @since 3.0.0
	 */
	public function filter_statuses( $wc_statuses ) {
		global $ewd_otp_controller;
		global $wp_post_statuses;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );
	
		$wc_statuses = array();
		foreach ( $statuses as $status ) {

			$sanitized_status = sanitize_title( $status->status, '', 'ewd_otp' );

			$wc_statuses[ 'wc-' . $sanitized_status ] = $status->status;
	
			if ( ! empty( $wp_post_statuses[ 'wc-' . $sanitized_status ] ) ) { continue; }
				
			$args = array(
					'name' 						=> 'wc-' . $sanitized_status,
					'label' 					=> $status->status,
					'label_count'				=> false,
					'exclude_from_search' 		=> null,
					'_builtin' 					=> false,
					'internal' 					=> null,
					'protected' 				=> null,
					'private'					=> null,
					'publicly_queryable' 		=> null,
					'show_in_admin_status_list' => null,
					'show_in_admin_all_list' 	=> true,
					'post_type' 				=> array( 'shop_order' )
			);
	
			$wp_post_statuses[ 'wc-' . $sanitized_status ] = (object) $args;
		}
	
		return $wc_statuses;
	}

	/**
	 * Allow WC orders to be set one of the different OTP statuses
	 * @since 3.0.0
	 */
	public function add_custom_status_bulk_actions( $actions ) {
		global $ewd_otp_controller;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );
	
		if ( isset( $actions['mark_processing'] ) ) { unset( $actions['mark_processing'] ); }
		if ( isset( $actions['mark_on-hold'] ) ) { unset( $actions['mark_on-hold'] ); }
		if ( isset( $actions['mark_completed'] ) ) { unset( $actions['mark_completed'] ); }
	
		foreach ( $statuses as $status ) {

			$sanitized_status = sanitize_title( $status->status, '', 'ewd_otp' );

			$actions[ 'mark_' . $sanitized_status ] = __('Change status to ', 'order-tracking') . $status->status;
		}
	
		return $actions;
	}

	/**
	 * Get the OTP equivalent for one or multiple WC statuses
	 * @since 3.0.0
	 */
	public function get_equivalent_status( $statuses ) {
		global $ewd_otp_controller;

		$statuses_array = is_array( $statuses ) ? $statuses : (array) $statuses;

		$equivalent_statuses =  array(
			'completed' 	=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-paid-status' ), '', 'ewd_otp' ),
			'pending' 		=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-unpaid-status' ), '', 'ewd_otp' ),
			'processing' 	=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-processing-status' ), '', 'ewd_otp' ),
			'cancelled' 	=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-cancelled-status' ), '', 'ewd_otp' ),
			'on-hold' 		=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-onhold-status' ), '', 'ewd_otp' ),
			'failed' 		=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-failed-status' ), '', 'ewd_otp' ),
			'refunded' 		=> sanitize_title( $ewd_otp_controller->settings->get_setting( 'woocommerce-refunded-status' ), '', 'ewd_otp' )
		);
	
		$return_statuses = array();
		foreach ( $statuses_array as $status ) {

			$return_statuses[] = $equivalent_statuses[ $status ];
		}
	
		return is_array( $statuses ) ? $return_statuses : reset( $return_statuses );
	}

	/**
	 * Return the OTP equivalent status for the parent_order_status query_param
	 * @since 3.0.0
	 */
	public function report_parent_statuses( $query_params ) {

		if ( isset( $query_params['parent_order_status'] ) ) {

			$equivalent_status = $this->get_equivalent_status( $query_params['parent_order_status'] );
			$query_params['parent_order_status'] = $equivalent_status;
		}
	
		return $query_params;
	}

	/**
	 * Adds the tracking information for an order to that order's page
	 * @since 3.0.0
	 */
	public function add_tracking_to_order_page( $wc_order ) {
		global $ewd_otp_controller;
	
		$db_order = $ewd_otp_controller->order_manager->get_order_from_woocommerce_id( $wc_order->get_order_number() );

		if ( empty( $db_order ) ) { return; }

		$order = new ewdotpOrder();
		$order->load_order( $db_order );
		$order->load_order_status_history();

		?>
	
		<h2>
			<?php _e( 'Tracking Information', 'order-tracking' ); ?>
		</h2>

		<table class='shop_table shop_table_responsive'>
			
			<thead>

				<tr>
					
					<th><?php _e( 'Order Status', 'order-tracking' ); ?></th>
					<th><?php _e( 'Order Location', 'order-tracking' ); ?></th>
					<th><?php _e( 'Updated', 'order-tracking' ); ?></th>

				</tr>

			</thead>

			<tbody>
				
				<?php foreach ( $order->status_history as $status ) { ?>
					
					<tr>
					
						<td><?php echo esc_html( $status->status ); ?></td>
						<td><?php echo esc_html( $status->location ); ?></td>
						<td><?php echo esc_html( $status->updated ); ?></td>

					</tr>

				<?php } ?>
			
			</tbody>
		
		</table>

		<?php 
	
		if ( empty( $ewd_otp_controller->settings->get_setting( 'tracking-page-url' ) ) ) { return; }

		$args = array(
			'tracking_number'	=> $order->number,
			'order_email'		=> $order->email
		);

		echo '<p><a href="' . esc_url( add_query_arg( $args, $ewd_otp_controller->settings->get_setting( 'tracking-page-url' ) ) ) . '">' . __('View Detailed Tracking Information', 'order-tracking') . '</a></p>';
	}

	/**
	 * Adds the order's current location to an order's admin page
	 * @since 3.0.0
	 */
	public function add_order_location( $wc_order ) {
		global $ewd_otp_controller;
	
		$db_order = $ewd_otp_controller->order_manager->get_order_from_woocommerce_id( $wc_order->get_order_number() );

		if ( empty( $db_order ) ) { return; }

		$order = new ewdotpOrder();
		$order->load_order( $db_order );

		$locations = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'locations' ) );

		?>
		
		<p class="form-field form-field-wide wc-order-status">
		
			<label for="order_location"><?php _e( 'Location:', 'order-tracking' ); ?></label>

			<select id="order_location" name="order_location" class="wc-enhanced-select">
				
				<?php foreach ( $locations as $location ) { ?>
					
					<option value="<?php echo esc_attr( $location->name ); ?>" <?php echo ( $order->location == $location->name ? 'selected' : '' ); ?>>
						<?php echo esc_html( $location->name ); ?>
					</option>

				<?php } ?>

			</select>

		</p>

		<?php
	}

	/**
	 * Update an order after receiving a notification form Zendesk
	 * @since 3.0.0
	 */
	public function save_wc_location( $post_id ) {
		global $ewd_otp_controller;
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
	
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
	
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
	
		} else {
	
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	
		/* OK, it's safe for us to save the data now. If there's no order location, don't save any other information.*/
		if ( ! isset( $_POST['order_location'] ) ) {
			return;
		}

		$db_order = $ewd_otp_controller->order_manager->get_order_from_woocommerce_id( $post_id );

		if ( empty( $db_order ) ) { return; }

		$order = new ewdotpOrder();
		$order->load_order( $db_order );
	
		if ( $_POST['order_location'] == $order->location ) { return; }
		
		$order->location = sanitize_text_field( $_POST['order_location'] );

		$order->update_order();

		$order->insert_order_status();
	}

	/**
	 * Get the OTP equivalent status of a WC status
	 * @since 3.0.0
	 */
	public function get_wc_status( $wc_status ) {
		global $ewd_otp_controller;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );
	
		switch ( $wc_status ) {

			case 'wc-pending':
				$otp_status = 'Pending Payment';
				break;

			case 'wc-processing':
				$otp_status = 'Processing';
				break;

			case 'wc-on-hold':
				$otp_status = 'On Hold';
				break;

			case 'wc-completed':
				$otp_status = 'Completed';
				break;

			case 'wc-cancelled':
				$otp_status = 'Cancelled';
				break;

			case 'wc-refunded':
				$otp_status = 'Refunded';
				break;

			case 'wc-failed':
				$otp_status = 'Failed';
				break;

			default:

				$otp_status = '';

				foreach ( $statuses as $status ) {

					if ( 'wc-' . sanitize_title( $status->status, '', 'ewd_otp' ) == $wc_status ) { $otp_status = $status->status; }
				}

				break;
		}
	
		return $otp_status;
	}

	/**
	 * Return post status to the originals for WC orders when the plugin is deactivated
	 * @since 3.0.0
	 */
	public function revert_statuses() {
		global $wpdb;
		global $ewd_otp_controller;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			$sanitized_status = sanitize_title( $status->status, '', 'ewd_otp' );

			if ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-paid-status' ) ) { $wc_status = 'wc-completed'; }
			elseif ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-unpaid-status' ) ) { $wc_status = 'wc-pending'; }
			elseif ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-processing-status' )) { $wc_status = 'wc-processing'; }
			elseif ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-cancelled-status' )) { $wc_status = 'wc-cancelled'; }
			elseif ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-onhold-status' )) { $wc_status = 'wc-on-hold'; }
			elseif ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-failed-status' )) { $wc_status = 'wc-failed'; }
			elseif ( $status->status == $ewd_otp_controller->settings->get_setting( 'woocommerce-refunded-status' )) { $wc_status = 'wc-refunded'; }
			else { $wc_status = 'wc-processing'; }

			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_status=%s WHERE post_status=%s AND post_type='shop_order'", $wc_status, 'wc-' . $sanitized_status ) );
		}
	}
}

}
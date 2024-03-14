<?php


/**
 * Small utility class for storing state, since the "Reserve" processing
 * happens in one function (on the "wp" hook), and the "Reserve" display
 * happens in another function (on the shortcode).
 *
 * Ultimately, ALL reserve functionality should be migrated into a class
 * for cleaner code, but for now, this is what solves the issues at hand.
 *
 * Class WPIMReserveService
 */
class WPIMReserveService {
	/**
	 * Whether to display the reserve form or not.
	 *
	 * @var bool
	 */
	private static $display = TRUE;

	/**
	 * @var WPIMDB
	 */
	private static $db;

	/**
	 * Error / message strings.
	 *
	 * @var array - strings for "error" and "message"
	 */
	private static $value = [
		'error'   => '',
		'message' => ''
	];

	public static function init() {
		self::reset();
		add_action( 'wp', [ __CLASS__, 'attempt_process' ], - 99999 );
		add_action( 'wpim_reserve_sent', [ __CLASS__, 'wpim_reserve_sent' ], 10, 4 );
	}

	/**
	 * Getter / Setter for the "Display" variable.
	 *
	 * @param bool|null $display - optional.  Omit to "get", provide to "set"
	 *
	 * @return bool
	 */
	public static function display( $display = NULL ) {
		if ( NULL !== $display ) {
			self::$display = $display;
		} else if ( ! empty( $_GET['display'] ) ) {
			self::$display = ( 'false' != $_GET['display'] );
		}

		return self::$display;
	}

	/**
	 * Getter / Setter for the "error" variable.
	 *
	 * @param string $error   - optional.  Omit to "get", provide to "set"
	 * @param bool   $replace - optional.  Whether to replace, or to append.
	 *
	 * @return string
	 */
	public static function error( $error = '', $replace = FALSE ) {
		return self::get_or_set( 'error', $error, $replace );
	}

	/**
	 * Getter / Setter for the "message" variable.
	 *
	 * @param string $message - optional.  Omit to "get", provide to "set"
	 * @param bool   $replace - optional.  Whether to replace, or to append.
	 *
	 * @return string
	 */
	public static function message( $message = '', $replace = FALSE ) {
		return self::get_or_set( 'message', $message, $replace );
	}

	/**
	 * Handles getting / setting the strings for both errors and messages.
	 *
	 * @param string $type    - either "message" or "error"
	 * @param string $value   - optional.  Omit to "get", provide to "set"
	 * @param bool   $replace - optional.  Whether to replace, or append.
	 *
	 * @return mixed
	 */
	private static function get_or_set( $type, $value = '', $replace = FALSE ) {
		if ( $value ) {
			if ( ! $replace ) {
				if ( self::$value[ $type ] ) {
					self::$value[ $type ] .= '<br>';
				}

				self::$value[ $type ] .= $value;
			} else {
				self::$value[ $type ] = $value;
			}
		} else if ( ! empty( $_GET[ $type ] ) ) {
			self::$value[ $type ] = urldecode( $_GET[ $type ] );
		}

		return self::$value[ $type ];
	}

	/**
	 * Resets state to default / originals.
	 */
	public static function reset() {
		self::$display          = TRUE;
		self::$value['error']   = '';
		self::$value['message'] = '';
	}

	/**
	 * Attempts to process a reservation, if submitted.
	 * Hooked on the "wp" action so can safely redirect to
	 * prevent duplicated submissions with browser refresh.
	 */
	public static function attempt_process() {
		$result = FALSE;
		self::setup_db();

		if ( ! isset( $_POST['wpinventory_reserve_submit'] ) && ! isset( $_POST['_wpim_reserve_submit'] ) ) {
			return;
		}

		$args = wpinventory_get_reserve_config( [] );
		self::reset();

		if ( ! isset( $_POST['_wpim_reserve_nonce'] ) || ! wp_verify_nonce( $_POST['_wpim_reserve_nonce'], 'WPInventoryReserveNonceAction' ) ) {
			self::error( WPIMCore::__( 'Security error.  Please try again.' ) );
		}

		$data = [];
		foreach ( $args AS $field => $required ) {
			if ( stripos( $field, 'display_' ) === 0 ) {
				$field = str_replace( 'display_', '', $field );
				if ( $field ) {
					$data[ $field ] = [
						'value' => WPIMCore::request( 'wpinventory_reserve_' . $field ),
						'label' => $args[ $field . '_label' ]
					];
					if ( stripos( $field, 'quantity' ) !== FALSE ) {
						$data[ $field ]['value'] = (int) $data[ $field ]['value'];
						if ( $data[ $field ]['value'] < 0 ) {
							$data[ $field ]['value'] = 0;
						}
					}
					if ( ! trim( $data[ $field ]['value'] ) && $required === 2 ) {
						self::error( $args[ $field . '_label' ] . ' ' . WPIMCore::__( 'is required.' ) );
					}
				}
			}
		}

//		 Ensure value is one here so can properly validate....
		if ( empty( $data['quantity']['value'] ) ) {
			$data['quantity']['value'] = 1;
		}

		if ( ! self::error() ) {
			$wpim_item = new WPIMItem();
			$item      = $wpim_item->get( $args['inventory_id'] );

			if ( (int) wpinventory_get_config( 'reserve_decrement' ) ) {
				if ( $item ) {
					$on_hand = $item->inventory_quantity;
					if ( $data['quantity']['value'] > $on_hand ) {
						self::error( WPIMCore::__( 'There is not enough of this item to reserve' ) . ' ' . $data['quantity']['value'] );
					}
				}
			}

			$data['inventory_id'] = $args['inventory_id'];
		}

		self::error( apply_filters( 'wpim_reserve_form_errors', self::error(), $data ), TRUE );

		if ( ! self::error() ) {
			$result = wpinventory_process_reserve( $data );

			$inventory_id          = $args["inventory_id"];
			$price                 = (float) $item->inventory_price;
			$quantity              = (float) $data['quantity']['value'];
			$total                 = $price * $quantity;
			$email                 = $data['email']['value'];
			$format_data_for_entry = json_encode( $data );

			$table_data = [
				'reservation_email_address' => ( $email ) ? $email : '',
				'reservation_form_data'     => $format_data_for_entry,
				'reservation_total'         => (float) $total
			];

			$reservation_id = self::save_reservation_record( $table_data );

			if ( apply_filters( 'wpim_reservation_save_item_to_reservation', TRUE ) ) {
				$table_data = [
					'reservation_quantity'   => $quantity,
					'reservation_item_price' => (float) $price,
					'reservation_item_cost'  => 0,
					'reservation_id'         => $reservation_id,
					'inventory_id'           => $inventory_id
				];

				self::save_reservation_item_record( $table_data, $inventory_id, $reservation_id );
				do_action( 'wpim_reservation_item_save', $reservation_id, $inventory_id, $quantity );
			}
		}

		if ( $result ) {
			$WPIMLoop = wpinventory_get_wpim();
			$WPIMLoop->load_items( [ 'inventory_id' => $args['inventory_id'] ] );
			$WPIMLoop->the_item();
			$permalink = $WPIMLoop->get_permalink();

			$redirect_url = add_query_arg( [ 'display' => 'false', 'message' => urlencode( apply_filters( 'wpim_reserve_confirmation_message', $args['reserve_message'] ) ) ], $permalink );
			wp_safe_redirect( $redirect_url );
			die();
		} else {
			self::error( $result );
		}
	}

	public static function save_reservation_record( $data ) {
		self::setup_db();

		$data = apply_filters( 'wpim_reservation_data_pre_save', $data );

		self::$db->wpdb()->insert( self::$db->reservation_table, $data );

		return self::$db->wpdb()->insert_id;
	}

	public static function save_reservation_item_record( $data, $inventory_id, $reservation_id ) {
		self::setup_db();

		// TODO: LEDGER NEEDS TO FILTER THIS DATA AND ADD COST TO EACH ITEM
		$data = apply_filters( 'wpim_reservation_save_reserve_data', $data, $inventory_id );

		self::$db->wpdb()->insert( self::$db->reservation_item_table, $data );

		// TODO: LEDGER NEEDS TO HOOK INTO THIS
		do_action( 'wpim_reservation_item_save', $reservation_id, $inventory_id, $data['reservation_quantity'] );
	}

	/**
	 * Process any low-quantity notices after the reserve form is saved successfully.
	 * // TODO - RENAME THIS.  The function name is misleading
	 *
	 * @param int    $inventory_id
	 * @param array  $data
	 * @param string $subject
	 * @param string $message
	 */
	public static function wpim_reserve_sent( $inventory_id, $data, $subject, $message ) {
		if ( ! apply_filters( 'wpim_reserve_low_quantities', TRUE ) ) {
			return;
		}

		if ( (int) wpinventory_get_config( 'low_quantity_email_check' ) && wpinventory_get_config( 'low_quantity_email' ) ) {
			$wpim_item = new WPIMItem();
			$item      = $wpim_item->get( $inventory_id );

			$data = [
				'inventory_id' => $inventory_id,
				'item_name'    => ( $item->inventory_name ? $item->inventory_name : $item->inventory_number ),
				'new_quantity' => (float) $item->inventory_quantity,
				'alert_amount' => (float) wpinventory_get_config( 'low_quantity_amount' ),
				'email'        => wpinventory_get_config( 'low_quantity_email' )
			];

			$data = apply_filters( 'wpim_reservation_notification_data', $data );

			if ( $data['new_quantity'] <= $data['alert_amount'] ) {
				$subject = sprintf( WPIMCore::__( 'Low Quantity Alert: %s' ), $data['item_name'] );
				$message = '<p>' . sprintf( WPIMCore::__( 'This is an email alert that the following item: <strong>%s</strong> - has fallen below the quantity alert threshold of %d' ), $data['item_name'], $data['alert_amount'] ) . '</p>';

				$subject = apply_filters( 'wpim_low_quantity_email_subject', $subject );
				$message = apply_filters( 'wpim_low_quantity_email_message', $message );
				$email   = $data['email'];

				WPIMCore::mail( 'reserve_low_quantity', $email, $subject, $message );
			}
		}
	}

	private static function setup_db() {
		if ( self::$db ) {
			return;
		}

		self::$db = new WPIMDB();

	}
}

WPIMReserveService::init();

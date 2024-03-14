<?php


class Xoo_Wl_Core{

	protected static $_instance = null;
	public $waitlist_table, $waitlist_meta_table;
	public $history_count = 50;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){
		add_action( 'wp_ajax_xoo_wl_form_submit', array( $this, 'form_submit' ) );
		add_action( 'wp_ajax_nopriv_xoo_wl_form_submit', array( $this, 'form_submit' ) );
		add_action( 'xoo_wl_cron_send_back_in_stock_email_for_product', array( $this, 'cron_send_back_in_stock_email_for_product' ) );
		add_action( 'updated_postmeta', array( $this, 'send_email_on_stock_update' ) ,10 , 4 );
	}


	public function form_submit(){
		
		$meta_fields = array();

		try {

			$product_id = (int) $_POST['_xoo_wl_product_id'];
			$email 		= sanitize_email( $_POST['xoo_wl_user_email'] );
			$quantity 	= isset( $_POST['xoo_wl_required_qty'] ) ? (float) $_POST['xoo_wl_required_qty'] : 1;

			$error = new WP_Error();

			if( !$product_id ){
				$error->add( 'no-id', __( 'Product ID not found, please contact support.', 'wailist-woocommerce' ) );
			}

			if( !$email ){
				$error->add( 'no-email', __( 'Email address is required.', 'wailist-woocommerce' ) );
			}
			
			if( !xoo_wl_is_product_out_of_stock( $product_id ) ){
				$error->add( 'in-stock', __( 'Product is already in stock, Please add to cart', 'waitlist-woocommerce' ) );
			}

			$error = apply_filters( 'xoo_wl_process_errors', $error, $product_id, $email );

			if ( $error->get_error_code() ) {
				throw new Xoo_Exception( $error );
			}

			$fieldValues = xoo_wl()->aff->fields->validate_submitted_field_values( $_POST );

			if( is_wp_error( $fieldValues ) ){
				$message = '';
				if( count( $fieldValues->get_error_messages() ) > 1 ){
					foreach ( $fieldValues->get_error_messages() as $error_message ) {
						$message .= '<p>'.$error_message.'</p>';
					}
				}
				else{
					$message = $fieldValues;
				}

				throw new Xoo_Exception( $message );
			}

			$waitlist_meta_data = $fieldValues;
			unset( $waitlist_meta_data[ 'xoo_wl_user_email' ] );
			unset( $waitlist_meta_data['xoo_wl_required_qty'] );

			$waitlist_data = array(
				'product_id' 	=> $product_id,
				'email' 		=> $email,
				'quantity' 		=> $quantity ? $quantity : 1,
				'meta' 			=> $waitlist_meta_data
			);

			

			$inserted_id = xoo_wl_db()->update_waitlist_row( $waitlist_data );

			if( is_wp_error( $inserted_id ) ){
				throw new Xoo_Exception( $inserted_id->get_error_message() );	
			}

			do_action( 'xoo_wl_form_submit_success', $inserted_id );

			$message = apply_filters( 'xoo_wl_form_submit_success', '<span class="xoo-wl-icon-check_circle xoo-wl-scs-icon"></span>'.xoo_wl_helper()->get_general_option( 'txt-success-notice' ) );

			wp_send_json( array(
				'error' 	=> 0,
				'notice' 	=> xoo_wl_add_notice( $message, 'success' )
			) );

			
		} catch ( Xoo_Exception $e) {


			$message = apply_filters( 'xoo_wl_form_submit_error', $e->getMessage(), $e->getWpErrorCode() );

			do_action( 'xoo_wl_form_submit_failed', $e );

			wp_send_json( array(
				'error' 		=> 1,
				'notice' 		=> xoo_wl_add_notice( $message, 'error' ),
			) );
		}	

	}



	public function send_back_in_stock_email( $row_id ){
		return xoo_wl_emails()->backInStock->trigger( $row_id );
	}


	public function trigger_back_in_stock_email_for_product( $product_id ){

		$validate_product = xoo_wl_emails()->backInStock->product_validation( $product_id );

		if( is_wp_error( $validate_product ) ){
			return $validate_product;
		}

		$crons 	= $this->get_email_cron_history();

		$in_process = false;

		foreach ( $crons as $timestamp => $data ) {
			if( $data['product_id'] === $product_id && $data['status'] === 'processing' ){
				$in_process = $crons[ $timestamp ];
				break;
			}
		}

		if( $in_process ){
			if( ( time() - $timestamp ) < 600 ){
				return new WP_Error( 'in-process', __( 'Emails are already in process for this product. Please wait for it to finish.' ) );
			}
			else{
				$crons[ $timestamp ]['status'] = 'completed';
				update_option( 'xoo_wl_cron_emails', json_encode( $crons ) );
			}
		}

		wp_schedule_single_event( time(), 'xoo_wl_cron_send_back_in_stock_email_for_product', array(
			$product_id
		) );

		return true;

	}

	public function get_email_cron_history(){
		return (array) json_decode( get_option( 'xoo_wl_cron_emails' ), true );
	}


	public function cron_send_back_in_stock_email_for_product( $product_id ){

		$rows = xoo_wl_db()->get_waitlist_rows_by_product( $product_id );

		$crons = $this->get_email_cron_history();

		$started = time();

		$new_cron = array(
			'product_id' 	=> $product_id,
			'started' 		=> $started,
			'status' 		=> 'processing',
			'count' 		=> count( $rows ),
		);

		$crons[ $started ] = $new_cron;

		if( count( $crons ) > $this->history_count ){
			$crons = array_slice( $crons , count( $crons ) - $this->history_count, count( $crons ), true );
		}

		update_option( 'xoo_wl_cron_emails', json_encode( $crons ) );

		foreach ( $rows as $row_data ) {
			$this->send_back_in_stock_email( $row_data->xoo_wl_id );
		}

		$crons[ $started ]['status'] = 'completed';

		update_option( 'xoo_wl_cron_emails', json_encode( $crons ) );

	}
	

	public function send_email_on_stock_update( $meta_id, $object_id, $meta_key, $meta_value ){


		if( $meta_key === '_stock_status' && $meta_value === 'instock' && xoo_wl_helper()->get_email_option( 'bis-auto-send' ) === "yes" &&  ( !defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE ) && $ok_to_send = apply_filters( 'xoo_wl_auto_send_email', true, $object_id ) ){

			$this->trigger_back_in_stock_email_for_product( $object_id );

		}

	}



}

function xoo_wl_core(){
	return Xoo_Wl_Core::get_instance();
}
xoo_wl_core();

?>
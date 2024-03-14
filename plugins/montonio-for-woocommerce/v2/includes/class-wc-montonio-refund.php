<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WC_Montonio_Refund {

    /**
     * Is test mode active?
     *
     * @var bool
     */
    public $sandbox_mode;

	public function __construct( $sandbox_mode ) {
		$this->sandbox_mode = $sandbox_mode;
	}

 
    /**
	 * Initiates refund from Montonio and return true/false as result
	 *
	 * @param string $order_id order id.
	 * @param string $amount refund amount.
	 * @param string $reason reason of refund.
	 * @return bool
	 */
	public function init_refund( $order_id, $amount, $reason ) {
		if ( 0 >= $amount ) {
			return false;
		}

        try {
            $order           = wc_get_order( $order_id );
            $idempotency_key = $order->get_order_key() . time();
            $order_uuid      = $order->get_meta( '_montonio_uuid' );

            if ( empty( $order_uuid ) ) {
                WC_Montonio_Logger::log( 'Failed to initiate refund due to missing UUID. Order ID: ' .$order_id );

                return new WP_Error( 'error', __( 'Montonio order UUID missing! Please refund this order manually in Montonio Partner System.', 'montonio-for-woocommerce' ) );
            }

            // Create new Montonio API instance
            $montonio_api = new WC_Montonio_API( $this->sandbox_mode );
            $response = $montonio_api->create_refund_request( $order_uuid, $amount, $idempotency_key );

            $order->add_order_note( __( 'Refund via Montonio initiated successfully. Refund amount: ', 'montonio-for-woocommerce' ) . $amount );

            return true;
        } catch ( Exception $e ) {
            if ( ! empty( $e->getMessage() ) ) {
                $message = json_decode( $e->getMessage(), true )['message'];
                
                if ( json_last_error() !== JSON_ERROR_NONE ) {
                    $message = $e->getMessage();
                }

                if ( is_array( $message ) ) {
                    $message = implode( '; ', $message );
                }

                WC_Montonio_Logger::log( 'Refund error: ' . $message );
                
                return new WP_Error( 'error', $message );
            }
        }
    }
}
<?php

use Flutterwave\Rave\EventHandlerInterface;
use AppBuilder\Data\CartData;

// This is where you set how you want to handle the transaction at different stages
class AppEventHandle implements EventHandlerInterface {
	private $order;
	private $cart_key;

	function __construct( $order, $cart_key ) {
		$this->order    = $order;
		$this->cart_key = $cart_key;
	}

	/**
	 * This is called when the Rave class is initialized
	 * */
	function onInit( $initializationData ) {
		// Save the transaction to your DB.
		$this->order->add_order_note( 'Payment initialized via Rave' );
		update_post_meta( $this->order->get_id(), '_flw_payment_txn_ref', $initializationData['txref'] );
		$this->order->add_order_note( 'Your transaction reference: ' . $initializationData['txref'] );
	}

	/**
	 * This is called only when a transaction is successful
	 * */
	function onSuccessful( $transactionData ) {

		// Get the transaction from your DB using the transaction reference (txref)
		// Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
		if ( $transactionData->chargecode === '00' || $transactionData->chargecode === '0' ) {
			if ( $transactionData->currency == $this->order->get_currency() && $transactionData->amount == $this->order->get_total() ) {
				$this->order->payment_complete( $this->order->get_id() );
				$payment_method = $this->order->get_payment_method();

				$flw_settings = get_option( 'woocommerce_' . $payment_method . '_settings' );

				if ( isset( $flw_settings['autocomplete_order'] ) && 'yes' === $flw_settings['autocomplete_order'] ) {
					$this->order->update_status( 'completed' );
				}
				$this->order->add_order_note( 'Payment was successful on Rave' );
				$this->order->add_order_note( 'Flutterwave transaction reference: ' . $transactionData->flwref );

				$customer_note = 'Thank you for your order.<br>';
				$customer_note .= 'Your payment was successful, we are now <strong>processing</strong> your order.';

				$this->order->add_order_note( $customer_note, 1 );
			} else {
				$this->order->update_status( 'on-hold' );
				$customer_note = 'Thank you for your order.<br>';
				$customer_note .= 'Your payment successfully went through, but we have to put your order <strong>on-hold</strong> ';
				$customer_note .= 'because the we couldn\t verify your order. Please, contact us for information regarding this order.';
				$admin_note    = 'Attention: New order has been placed on hold because of incorrect payment amount or currency. Please, look into it. <br>';
				$admin_note    .= 'Amount paid: ' . $transactionData->currency . ' ' . $transactionData->amount . ' <br> Order amount: ' . $this->order->get_currency() . ' ' . $this->order->get_total() . ' <br> Reference: ' . $transactionData->txref;

				$this->order->add_order_note( $customer_note, 1 );
				$this->order->add_order_note( $admin_note );
			}

			//get order_id from the txref
			$getOrderId = explode( '_', $transactionData->txref );
			$order_id   = $getOrderId[1];

			//save the card token returned here
			FLW_WC_Payment_Gateway::save_card_details( $transactionData, $this->order->get_user_id(), $order_id );

			$cart = new CartData();
			$cart->remove_cart_by_cart_key( $this->cart_key );

		} else {

			$this->onFailure( $transactionData );

		}

	}

	/**
	 * This is called only when a transaction failed
	 * */
	function onFailure( $transactionData ) {
		// Get the transaction from your DB using the transaction reference (txref)
		// Update the db transaction record (includeing parameters that didn't exist before the transaction is completed. for audit purpose)
		// You can also redirect to your failure page from here
		$this->order->update_status( 'Failed' );
		$this->order->add_order_note( 'The payment failed on Rave' );
		$customer_note = 'Your payment <strong>failed</strong>. ';
		$customer_note .= 'Please, try funding your account.';

		$this->order->add_order_note( $customer_note, 1 );
	}

	/**
	 * This is called when a transaction is requeryed from the payment gateway
	 * */
	function onRequery( $transactionReference ) {
		// Do something, anything!
		$this->order->add_order_note( 'Confirming payment on Rave' );
	}

	/**
	 * This is called a transaction requery returns with an error
	 * */
	function onRequeryError( $requeryResponse ) {
		// Do something, anything!
		$this->order->add_order_note( 'An error occured while confirming payment on Rave' );
		$this->order->update_status( 'on-hold' );
		$customer_note = 'Thank you for your order.<br>';
		$customer_note .= 'We had an issue confirming your payment, but we have put your order <strong>on-hold</strong>. ';
		$customer_note .= 'Please, contact us for information regarding this order.';
		$admin_note    = 'Attention: New order has been placed on hold because we could not confirm the payment. Please, look into it. <br>';
		$admin_note    .= 'Payment Responce: ' . $requeryResponse->message;

		$this->order->add_order_note( $customer_note, 1 );
		$this->order->add_order_note( $admin_note );
	}

	/**
	 * This is called when a transaction is canceled by the user
	 * */
	function onCancel( $transactionReference ) {
		// Do something, anything!
		// Note: Somethings a payment can be successful, before a user clicks the cancel button so proceed with caution
		$this->order->add_order_note( 'The customer clicked on the cancel button on Rave' );
		$this->order->update_status( 'Cancelled' );
		$admin_note = 'Attention: Customer clicked on the cancel button on the payment gateway. We have updated the order to canceled. <br>';
		$admin_note .= 'Please, confirm from the order notes that there is no note of a successful transaction. If there is, this means that the user was debited and you either have to give value for the transaction or refund the customer.';
		$this->order->add_order_note( $admin_note );
	}

	/**
	 * This is called when a transaction doesn't return with a success or a failure response. This can be a timedout transaction on the Rave server or an abandoned transaction by the customer.
	 * */
	function onTimeout( $transactionReference, $data ) {
		// Get the transaction from your DB using the transaction reference (txref)
		// Queue it for requery. Preferably using a queue system. The requery should be about 15 minutes after.
		// Ask the customer to contact your support and you should escalate this issue to the flutterwave support team. Send this as an email and as a notification on the page. just incase the page timesout or disconnects
		$this->order->add_order_note( 'The payment didn\'t return a valid response. It could have timed out or abandoned by the customer on Rave' );
		$this->order->update_status( 'on-hold' );
		$customer_note = 'Thank you for your order.<br>';
		$customer_note .= 'We had an issue confirming your payment, but we have put your order <strong>on-hold</strong>. ';
		$customer_note .= 'Please, contact us for information regarding this order.';
		$admin_note    = 'Attention: New order has been placed on hold because we could not get a definite response from the payment gateway. Kindly contact the Rave support team at hi@flutterwave.com to confirm the payment. <br>';
		$admin_note    .= 'Payment Reference: ' . $transactionReference;

		$this->order->add_order_note( $customer_note, 1 );
		$this->order->add_order_note( $admin_note );
	}
}
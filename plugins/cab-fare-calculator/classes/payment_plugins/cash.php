<?php
class plgTblightPaymentCash extends tbPaymentPlugin {

	var $_name = 'cash';

	function __construct() {
		parent::__construct();
	}

	/**
	 * plgTbDisplayListFEPayment
	 * This event is fired to display the pluginmethods in the cart (edit shipment/payment) for exampel
	 *
	 * @param integer $selected ID of the method selected
	 * @return boolean True on succes, false on failures, null when this plugin was not selected.
	 * On errors, JError::raiseWarning (or JError::raiseError) must be used to set a message.
	 */
	public function plgTbDisplayListFEPayment( $selected = 0 ) {
		return $this->displayListFE( $selected );
	}

	/**
	 * @param                $method
	 * @param                $cart_prices
	 * @return int
	 */
	function getCosts( $method, $cart_price ) {

		if ( preg_match( '/%$/', $method->cost_percent_total ) ) {
			$cost_percent_total = substr( $method->cost_percent_total, 0, -1 );
		} else {
			$cost_percent_total = $method->cost_percent_total;
		}
		return ( $method->cost_per_transaction + ( $cart_price * $cost_percent_total * 0.01 ) );
	}
	/**
	 * Check if the payment conditions are fulfilled for this payment method
	 */
	protected function checkConditions( $method, $cart_price ) {

		$this->convert( $method );

		$amount = $cart_price;

		$amount_cond = ( $amount >= $method->min_amount && $amount <= $method->max_amount
			||
			( $method->min_amount <= $amount && ( $method->max_amount == 0 ) ) );

		if ( ! $amount_cond ) {
			return false;
		}

		return true;
	}

	function convert( $method ) {

		$method->min_amount           = (float) $method->min_amount;
		$method->max_amount           = (float) $method->max_amount;
		$method->cost_percent_total   = (float) $method->cost_percent_total;
		$method->cost_per_transaction = (float) $method->cost_per_transaction;
	}

	/**
	 * @param array            $cart_prices
	 * @param                $cart_prices_name
	 * @return bool|null
	 */
	public function plgTbonSelectedCalculatePricePayment( $method_id, $cart_prices ) {

		return $this->onSelectedCalculatePrice( $method_id, $cart_prices );
	}

	/**
	 * This event triggers after order submit
	 */
	public function plgTbOrderSubmit( $order ) {

		if ( ! ( $method = $this->getPluginMethod( $order->payment ) ) ) {
			return null; // Another method was selected, do nothing
		}
		if ( ! $this->selectedThisElement( $method->payment_element ) ) {
			return false;
		}

		$this->convert( $method );

		// Get the page/component configuration
		$elsettings = BookingHelper::config();

		$dbValues                         = array();
		$dbValues['payment_name']         = $this->renderPluginName( $method );
		$dbValues['order_id']             = $order->id;
		$dbValues['order_number']         = $order->order_number;
		$dbValues['paymentmethod_id']     = $order->payment;
		$dbValues['cost_per_transaction'] = $method->cost_per_transaction;
		$dbValues['cost_percent_total']   = $method->cost_percent_total;
		$dbValues['payment_currency']     = $elsettings->currency;
		$dbValues['payment_order_total']  = $order->cprice;

		$this->storePluginInternalData( $dbValues );

		return true;
	}

	/**
	 * This event triggers after order submit, redrects user to payment gateway
	 */
	public function plgTbProcessPayment( $order ) {

		global $wp;
		$booking_form_url = home_url( add_query_arg( array(), $wp->request ) );

		$html  = 'You will receive a confirmation e-mail confirming this booking.';
		$html .= '<br><br>Thank you for booking with us!';
		$html .= '&nbsp;<a href="' . $booking_form_url . '">';
		$html .= 'Go back to booking form';
		$html .= '</a>';

		return $html;
	}

	/**
	 * Display stored payment data for an order
	 */
	function plgTbOnShowOrderBEPayment( $order_id, $payment_id ) {

		if ( ! $this->selectedThisByMethodId( $payment_id ) ) {
			return null; // Another method was selected, do nothing
		}

		if ( ! ( $paymentTable = $this->getDataByOrderId( $order_id ) ) ) {
			return null;
		}

		$html  = '<table class="adminlist table table-striped">' . "\n";
		$html .= '<thead>
<tr>
<th class="key" colspan="2" style="text-align: center;">Payment Method</th>
</tr>
</thead>';
		$html .= '<tr>
<td class="key">Name</td>
<td align="left">
<span class="payment_name">' . $paymentTable[0]->payment_name . '</span>
<br>
</td>
</tr>';
		$html .= '<tr>
<td class="key">Total</td>
<td align="left">' . $paymentTable[0]->payment_order_total . ' ' . $paymentTable[0]->payment_currency . '</td>
</tr>';
		$html .= '</table>' . "\n";
		return $html;
	}

	/**
	 * Display stored payment data in confirmation emails and invoice
	 */
	function plgTbOnShowOrderEmailsInvoice( $order_id, $payment_id ) {

		if ( ! $this->selectedThisByMethodId( $payment_id ) ) {
			return null; // Another method was selected, do nothing
		}

		if ( ! ( $payment = $this->getDataByOrderId( $order_id ) ) ) {
			return null;
		}

		$html = '';

		$html .= '<tr>
<td width="35%">Payment method:</td>
<td width="35%">' . $payment[0]->payment_name . '</td>
</tr>';

		return $html;
	}
}

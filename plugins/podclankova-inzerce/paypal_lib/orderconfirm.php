<?php

/* =====================================
 *	 PayPal Express Checkout Call
 * =====================================
 */
require_once ("paypalfunctions.php");

$PaymentOption = "PayPal";
if ( $PaymentOption == "PayPal" )
{
	/*
	 '------------------------------------
	 ' this  step is required to get parameters to make DoExpressCheckout API call, 
	 ' this step is required only if you are not storing the SetExpressCheckout API call's request values in you database.
	 ' ------------------------------------
	 */
	$res = GetExpressCheckoutDetails( $_REQUEST['token'] );
	
	/*
	 '------------------------------------
	 ' The paymentAmount is the total value of
	 ' the purchase. 
	 '------------------------------------
	 */

	$finalPaymentAmount =  $res["AMT"];

	/*
	 '------------------------------------
	 ' Calls the DoExpressCheckoutPayment API call
	 '
	 ' The ConfirmPayment function is defined in the file PayPalFunctions.php,
	 ' that is included at the top of this file.
	 '-------------------------------------------------
	 */

	//Format the  parameters that were stored or received from GetExperessCheckout call.
	$token 				= $_REQUEST['token'];
	$payerID 			= $_REQUEST['PayerID'];
	$paymentType 		= 'Sale';
	$currencyCodeType 	= $res['CURRENCYCODE'];


	$resArray = ConfirmPayment ( $token, $paymentType, $currencyCodeType, $payerID, $finalPaymentAmount );
	$ack = strtoupper($resArray["ACK"]);

	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
	{
		
		/*
		 * TODO: Proceed with desired action after the payment 
		 * (ex: start download, start streaming, Add coins to the game.. etc)
		 '********************************************************************************************************************
		 '
		 ' THE PARTNER SHOULD SAVE THE KEY TRANSACTION RELATED INFORMATION LIKE
		 '                    transactionId & orderTime
		 '  IN THEIR OWN  DATABASE
		 ' AND THE REST OF THE INFORMATION CAN BE USED TO UNDERSTAND THE STATUS OF THE PAYMENT
		 '
		 '********************************************************************************************************************
		 */

		$transactionId		= $resArray["PAYMENTINFO_0_TRANSACTIONID"]; // Unique transaction ID of the payment.
		$transactionType 	= $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"]; // The type of transaction Possible values: l  cart l  express-checkout
		$paymentType		= $resArray["PAYMENTINFO_0_PAYMENTTYPE"];  // Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant
		$orderTime 			= $resArray["PAYMENTINFO_0_ORDERTIME"];  // Time/date stamp of payment
		$amt				= $resArray["PAYMENTINFO_0_AMT"];  // The final amount charged, including any  taxes from your Merchant Profile.
		$currencyCode		= $resArray["PAYMENTINFO_0_CURRENCYCODE"];  // A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD.
		$feeAmt				= $resArray["PAYMENTINFO_0_FEEAMT"];  // PayPal fee amount charged for the transaction
	//	$settleAmt			= $resArray["PAYMENTINFO_0_SETTLEAMT"];  // Amount deposited in your PayPal account after a currency conversion.
		$taxAmt				= $resArray["PAYMENTINFO_0_TAXAMT"];  // Tax charged on the transaction.
	//	$exchangeRate		= $resArray["PAYMENTINFO_0_EXCHANGERATE"];  // Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer's account.

		/*
		 ' Status of the payment:
		 'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
		 'Pending: The payment is pending. See the PendingReason element for more information.
		 */

		$paymentStatus = $resArray["PAYMENTINFO_0_PAYMENTSTATUS"];

		/*
		 'The reason the payment is pending:
		 '  none: No pending reason
		 '  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile.
		 '  echeck: The payment is pending because it was made by an eCheck that has not yet cleared.
		 '  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview.
		 '  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment.
		 '  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment.
		 '  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service.
		 */

		$pendingReason = $resArray["PAYMENTINFO_0_PENDINGREASON"];

		/*
		 'The reason for a reversal if TransactionType is reversal:
		 '  none: No reason code
		 '  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer.
		 '  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee.
		 '  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer.
		 '  refund: A reversal has occurred on this transaction because you have given the customer a refund.
		 '  other: A reversal has occurred on this transaction due to a reason not listed above.
		 */

		$reasonCode	= $resArray["PAYMENTINFO_0_REASONCODE"];

		// Add javascript to close Digital Goods frame. You may want to add more javascript code to
		// display some info message indicating status of purchase in the parent window
    
    $data = array(
        'id' => NULL,
        'id_post' => $_COOKIE["pdckl_id_post"],
        'time' => time(),
        'link' => base64_decode($_COOKIE["pdckl_gateway_flink"]),
        'active' => 1
    );
        
    $wpdb->insert($wpdb->prefix . "pdckl_links", (array) $data);
    
    //Redirect
		header("Location: ".get_option('siteurl')."/?pdckl=pp_success");
    exit;
	}
	else
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

		echo "DoExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
	?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
  </head>
  <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
    <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;">Během transakce došlo k chybě a transakce nebyla dokončena!</div><br />
    <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">Zavřít okno</a></div>
  </body>
</html>
<?php 
	}
}

?>
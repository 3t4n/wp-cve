<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  array   $data       An associative array containing the transaction details.
 * @var  array   $params     An associative array with the payment configuration.
 * @var  array   $payer      The billing details of the customer.
 * @var  string  $sdkUrl     The URL needed to load the SDK resources.
 * @var  string  $notifyUrl  The URL to reach to complete the payment validation.
 */
extract($displayData);

JHtml::fetch('script', $sdkUrl);

?>

<div id="paypal-button-container">
	<!-- PayPal buttons will be rendered here -->
</div>

<script>
	(function($) {
		'use strict';

		let notifyUrl = '<?php echo $notifyUrl; ?>';

		// fetch customer details
		const customer = <?php echo json_encode($payer); ?>;

		// set up PayPal payer billing details
		const payer = {};

		if (customer.name) {
			// inject name
			payer.name = {
				given_name: customer.first_name,
				surname: customer.last_name,
			};
		}

		if (customer.email) {
			// inject e-mail address
			payer.email_address = customer.email;
		}

		/**
		 * DO NOT pass the phone number because, in case it doesn't meet the 
		 * PayPal format requirements, the whole request would fail.
		 */

		if (customer.country) {
			// inject country code
			payer.address = {
				country_code: customer.country,
			};

			if (customer.address1) {
				// inject address
				payer.address.address_line_1 = customer.address1;

				if (customer.address2) {
					// inject address
					payer.address.address_line_2 = customer.address2;
				}
			}

			if (customer.state) {
				// inject state/province
				payer.address.admin_area_1 = customer.state;
			}

			if (customer.city) {
				// inject city
				payer.address.admin_area_2 = customer.city;
			}

			if (customer.zip) {
				// inject postal code
				payer.address.postal_code = customer.zip;
			}
		}

		$(function() {
			paypal.Buttons({
				style: {
					layout: '<?php echo $params['layout']; ?>',
					color:  '<?php echo $params['color']; ?>',
					shape:  '<?php echo $params['shape']; ?>',
					label:  'paypal',
					tagline: <?php echo $params['layout'] == 'horizontal' && $params['tagline'] == 1 ? 'true' : 'false'; ?>,
				},
				createOrder: (data, actions) => {
					// This function sets up the details of the transaction, including the amount and line item details.
					return actions.order.create({
						purchase_units: [{
							custom_id: '<?php echo $order['oid']; ?>',
							description: '<?php echo addslashes($order['transaction_name']); ?>',
							amount: {
								value: <?php echo (float) $order['total_to_pay']; ?>,
								currency_code: '<?php echo $order['transaction_currency']; ?>',
							},
						}],
						/**
						 * Both the `payer` and `application_context` properties are marked as deprecated
						 * on the PayPal website. We still provide them since it seems to only way to 
						 * auto-complete the billing details and disable the shipping method.
						 * 
						 * @link https://developer.paypal.com/docs/api/orders/v2/
						 */
						payer: payer,
						<?php if (empty($order['shipping'])): ?>
							application_context: {
								shipping_preference: "NO_SHIPPING",
							},
						<?php endif; ?>
					});
				},
				onApprove: (data, actions) => {
					// This function captures the funds from the transaction.
					return actions.order.capture().then((details) => {
						document.location.href = notifyUrl.replace(/{PAYMENT_ID}/, details.id);
					});
				},
				onError: (err) => {
					// For example, redirect to a specific error page
					console.error('onError', err);
				},
			}).render('#paypal-button-container');
		});
	})(jQuery);
</script>
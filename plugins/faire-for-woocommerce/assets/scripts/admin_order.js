/* global faireAdminOrder */

'use strict';

(function ($) {
	$(document).ready(() => {
		const btnFaireAcceptOrder = $('#btn_faire_accept_order');
		const faireOrderShippingMethod = $('#faire_order_shipping_method');
		const faireTrackingCode = $('#faire_tracking_code');
		const faireShippingCost = $('#faire_shipping_cost');
		const faireManageOrderMsg = $('#faire_manage_order_msg');
		const btnFaireAddOrderShippingMethod = $(
			'#btn_faire_add_order_shipping_method'
		);
		const btnFaireBackorderProducts = $('#btn_faire_backorder_products');
		const faireBackorderProductsMsg = $('#faire_backorder_products_msg');

		/**
		 * Reloads the page.
		 */
		const reloadPage = () => {
			window.location.reload();
			return false;
		};

		/**
		 * Retrieves current order WooCommerce ID and Faire ID.
		 *
		 * @param {$} element JQuery element holding the order IDs.
		 *
		 * @return object The current order IDs.
		 */
		const getCurrentOrderIds = ( element ) => {
			return {
				faireOrderId: element.data('faire_order_id'),
				shopOrderId: element.data('wc_order_id')
			};
		}

		/**
		 * Updates the status of a shop order.
		 *
		 * @param {number}   orderId The ID of the order.
		 * @param {string}   status  The new status for the order.
		 * @param {function} success Action to execute if the update succeeds.
		 */
		const updateOrderStatus = (orderId, status, success) => {
			const { ajaxUrl, nonce, updatingOrderStatus, updatingOrderStatusFailed } = faireAdminOrder;

			faireManageOrderMsg.html(updatingOrderStatus);
			$.post(
				ajaxUrl,
				{
					nonce: nonce,
					action: 'update_order_status',
					order_id: orderId,
					status,
				},
				response => success(response)
			)
			.fail(
				() => {
					faireManageOrderMsg.html(
						updatingOrderStatusFailed
					);
					// eslint-disable-next-line no-console
					console.error(updatingOrderStatusFailed);
				}
			);
		};

		/**
		 * Toggles visibility of controls to set order shipment.
		 */
		const toggleShippingControls = () => {
			const disableShippingControls = !faireOrderShippingMethod.val();

			btnFaireAddOrderShippingMethod.prop(
				'disabled',
				disableShippingControls
			);
			faireTrackingCode.prop('disabled', disableShippingControls);
			faireTrackingCode.val('');
		};

		toggleShippingControls();
		faireOrderShippingMethod.on('change', function () {
			toggleShippingControls();
		});

		/**
		 * Manages clicks on "Accept Order" button.
		 */
		btnFaireAcceptOrder.on('click', function (e) {
			const { ajaxUrl, nonce, acceptingOrder, acceptingOrderFailed } = faireAdminOrder;

			e.preventDefault();

			const { faireOrderId, shopOrderId } = getCurrentOrderIds($(this));
			if (!faireOrderId || !shopOrderId) {
				return;
			}

			if (btnFaireAcceptOrder.prop('disabled')) {
				return;
			}
			btnFaireAcceptOrder.prop('disabled', true);

			faireManageOrderMsg.html(acceptingOrder);

			$.post(
				ajaxUrl,
				{
					action: 'accept_faire_order',
					nonce: nonce,
					order_id: faireOrderId,
				},
				response => {
					updateOrderStatus(shopOrderId, 'processing', reloadPage);
					// eslint-disable-next-line no-console
					console.log(response);
				}
			)
			.fail(
				response => {
					faireManageOrderMsg.html(
						acceptingOrderFailed
					);
					btnFaireAcceptOrder.prop('disabled', false);
					/* eslint-disable no-console */
					console.error(response);
					console.error(faireOrderId);
					console.error(acceptingOrderFailed);
					/* eslint-enable no-console */
				}
			);
		});

		/**
		 * Manages clicks on "Add shipment" button.
		 */
		btnFaireAddOrderShippingMethod.on('click', function (e) {
			const {
				ajaxUrl,
				nonce,
				shippingCostRequired,
				shipmentTrackingCodeRequired,
				settingOrderShipment,
				setOrderShipmentFailed
			} = faireAdminOrder;

			e.preventDefault();

			const { faireOrderId, shopOrderId } = getCurrentOrderIds($(this));
			if (!faireOrderId || !shopOrderId) {
				return;
			}

			if (btnFaireAddOrderShippingMethod.prop('disabled')) {
				return;
			}
			btnFaireAddOrderShippingMethod.prop('disabled', true);

			const carrier = faireOrderShippingMethod.val();
			if (!carrier) {
				return;
			}

			// If there's an error tip next to the shipping cost field,
			// the value is invalid and we remove it.
			if (faireShippingCost.next('.wc_error_tip').length) {
				faireShippingCost.val('');
			}

			const shippingCost = faireShippingCost.val().trim();
			if (!shippingCost) {
				faireManageOrderMsg.html(shippingCostRequired);
				btnFaireAddOrderShippingMethod.prop('disabled', false);
				return;
			}

			const trackingCode = faireTrackingCode.val().trim();
			if (!trackingCode) {
				faireManageOrderMsg.html(
					shipmentTrackingCodeRequired
				);
				btnFaireAddOrderShippingMethod.prop('disabled', false);
				return;
			}

			faireManageOrderMsg.html(settingOrderShipment);

			$.post(
				ajaxUrl,
				{
					nonce: nonce,
					action: 'set_order_shipment',
					order_id: faireOrderId,
					carrier,
					tracking_code: trackingCode,
					maker_cost_cents: shippingCost,
				},
				response => {
					updateOrderStatus(shopOrderId, 'completed', reloadPage);
					// eslint-disable-next-line no-console
					console.log(response);
				}
			)
			.fail(
				response => {
					faireManageOrderMsg.html(
						setOrderShipmentFailed
					);
					btnFaireAddOrderShippingMethod.prop('disabled', false);
					/* eslint-disable no-console */
					console.error(response);
					console.error(faireOrderId);
					console.error(setOrderShipmentFailed);
					/* eslint-enable no-console */
				}
			);
		});

		/**
		 * Displays an error message if there are errors in products backorder
		 * input fields.
		 *
		 * @param {number} inputFieldErrorsCount Number of errors in the set of checked data fields.
		 * @param {string} errorMsg              Error message to display.
		 * @param {$}      errorElement          DOM element to show error messages.
		 *
		 * @return {boolean} True if error message was displayed.
		 */
		const errorBackorderProductsField = (
			inputFieldErrorsCount,
			errorMsg,
			errorElement
		) => {
			if (inputFieldErrorsCount) {
				errorElement.html(errorMsg);
				btnFaireBackorderProducts.prop('disabled', false);
				return true;
			}
			return false;
		};

		/**
		 * Handles clicks on the 'Backorder Products' button.
		 */
		btnFaireBackorderProducts.on('click', function (e) {
			const {
				ajaxUrl,
				nonce,
				orderProductsBackorderQtyUnset,
				orderProductsBackorderDateUnset,
				orderProductsBackorderDateMin,
				orderProductsBackordering,
				orderProductsBackorderSuccess,
				orderProductsBackorderFailed
			} = faireAdminOrder;

			e.preventDefault();

			const { faireOrderId, shopOrderId } = getCurrentOrderIds($(this));
			if (!faireOrderId || !shopOrderId) {
				return;
			}

			if (btnFaireBackorderProducts.prop('disabled')) {
				return;
			}
			btnFaireBackorderProducts.prop('disabled', true);

			// Get all backorder products data
			const tomorrowDate = $(this).data('tomorrow_date');
			const backorderProducts = $('.faire_backorder_product_data');
			const availabilities = {};
			const itemsData = [];

			let availableProductsQtyEmpty = 0;
			let backorderProductsDateEmpty = 0;
			let backorderProductsDateBackInTime = 0;

			backorderProducts.each(function () {
				const availableProductQty = parseInt(
					$(this).find('#backorder_products_qty').val()
				);
				const backorderDate = $(this)
					.find('#backorder_products_date')
					.val();

				if (availableProductQty <= 0) {
					availableProductsQtyEmpty++;
				}
				if (!backorderDate) {
					backorderProductsDateEmpty++;
				}
				if (backorderDate < tomorrowDate) {
					backorderProductsDateBackInTime++;
				}
				availabilities[$(this).data('faire_item_id')] = {
					available_quantity: availableProductQty,
					discontinued: false,
					backordered_until: backorderDate
						? new Date(backorderDate).toISOString()
						: '',
				};
				if (availableProductQty > 0) {
					itemsData.push({
						item_id: parseInt($(this).data('wc_item_id')),
						backordered: availableProductQty
					});
				}
			});

			// Validate backorder quantities and dates.
			if (
				errorBackorderProductsField(
					availableProductsQtyEmpty,
					orderProductsBackorderQtyUnset,
					faireBackorderProductsMsg
				) ||
				errorBackorderProductsField(
					backorderProductsDateEmpty,
					orderProductsBackorderDateUnset,
					faireBackorderProductsMsg
				) ||
				errorBackorderProductsField(
					backorderProductsDateBackInTime,
					orderProductsBackorderDateMin,
					faireBackorderProductsMsg
				)
			) {
				return;
			}

			faireBackorderProductsMsg.html(
				orderProductsBackordering
			);

			$.post(
				ajaxUrl,
				{
					nonce: nonce,
					action: 'backorder_products',
					wc_order_id: shopOrderId,
					items_data: itemsData,
					faire_order_id: faireOrderId,
					availabilities: availabilities,
				},
				// Request succeeds.
				() => {
					faireBackorderProductsMsg.html(
						orderProductsBackorderSuccess
					);
					updateOrderStatus(shopOrderId, 'faire-backordered', reloadPage);
				}
			)
				// Request fails.
			.fail(
				response => {
					// noinspection JSUnresolvedVariable
					const errorMsg =
						response.responseJSON === undefined ||
						response.responseJSON.data === undefined
							? orderProductsBackorderFailed
							: response.responseJSON.data;
					faireBackorderProductsMsg.html(errorMsg);
					// eslint-disable-next-line no-console
					console.error(response);
				}
			)
			.always(
				() => btnFaireBackorderProducts.prop('disabled', false)
			);
		});
	});
})(window.jQuery);

<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Endpoints while creating a payment.
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/payment-endpoints/get-payment-intent.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/payment-endpoints/email-transaction-receipt.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/payment-endpoints/save-note-with-tithe.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/payment-endpoints/validate-currency.php';

// Manage Payments endpoints.
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/check-if-user-logged-in.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/login-email.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/attempt-user-login.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/get-arrangements.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/get-arrangement.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/get-subscription-payment-method.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/update-arrangement.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/cancel-arrangement.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/get-transactions.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/manage-payments-endpoints/get-transaction.php';

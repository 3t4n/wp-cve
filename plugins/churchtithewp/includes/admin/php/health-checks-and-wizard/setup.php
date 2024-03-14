<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2019, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include the functions that control the Health Checks and Wizard Steps.
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/functions-wizard.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/functions-health-check.php';

// Include the Onboarding Wizard steps.
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/wizard/default-currency.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/wizard/default-amount.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/wizard/form-image.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/wizard/thank-you-message.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/wizard/terms-and-conditions.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/wizard/complete-wizard.php';

// Include the Health Checks (health checks may also be wizard steps).
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/do-wizard.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/apple-pay.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/ssl.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/stripe-live-mode.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/stripe-live-webhook.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/stripe-test-mode.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/stripe-test-webhook.php';
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/health-checks/wp-mail.php';

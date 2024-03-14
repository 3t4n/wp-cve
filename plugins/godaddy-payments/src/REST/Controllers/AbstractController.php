<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\REST\Controllers;

use WP_REST_Controller;

defined('ABSPATH') or exit;

/**
 * Abstract controller.
 *
 * @since 1.2.0
 */
abstract class AbstractController extends WP_REST_Controller
{
    /** @var string namespace of the controller's route */
    protected $namespace = 'godaddy-payments/v1';
}

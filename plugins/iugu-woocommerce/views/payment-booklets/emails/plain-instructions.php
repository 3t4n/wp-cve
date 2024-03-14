<?php

/**
 * payment-booklets - Plain email instructions.
 *
 * @author  Iugu
 * @package Iugu_WooCommerce/Templates
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

_e('Payment', IUGU);

echo "\n\n";

echo sprintf(__('Payment successfully made using payment booklets in %s.', IUGU), $installments . 'x');

echo "\n\n****************************************************\n\n";

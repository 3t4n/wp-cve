<?php
/**
 * SecurePay.
 *
 * @author  SecurePay Sdn Bhd
 * @license GPL-2.0+
 *
 * @see    https://securepay.net
 */

/*
 * @wordpress-plugin
 * Plugin Name:         SecurePay
 * Plugin URI:          https://www.securepay.my/?utm_source=wp-plugins-securepay&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Version:             1.0.18
 * Description:         Plugin for SecurePay payment platform with WooCommerce
 * Author:              SecurePay Sdn Bhd
 * Author URI:          https://www.securepay.my/?utm_source=wp-plugins-securepay&utm_campaign=author-uri&utm_medium=wp-dash
 * Requires at least:   5.4
 * Requires PHP:        5.6.20
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         securepay
 * Domain Path:         /languages
 */
if (!\defined('ABSPATH') || \defined('SECUREPAY_FILE')) {
    exit;
}

\define('SECUREPAY_VER', '1.0.18');
\define('SECUREPAY_SLUG', 'securepay');
\define('SECUREPAY_ENDPOINT_LIVE', 'https://securepay.my/api/v1/');
\define('SECUREPAY_ENDPOINT_SANDBOX', 'https://sandbox.securepay.my/api/v1/');
\define('SECUREPAY_ENDPOINT_PUBLIC_LIVE', 'https://securepay.my/api/public/v1/');
\define('SECUREPAY_ENDPOINT_PUBLIC_SANDBOX', 'https://sandbox.securepay.my/api/public/v1/');

\define('SECUREPAY_FILE', __FILE__);
\define('SECUREPAY_HOOK', plugin_basename(SECUREPAY_FILE));
\define('SECUREPAY_PATH', realpath(plugin_dir_path(SECUREPAY_FILE)).'/');
\define('SECUREPAY_URL', trailingslashit(plugin_dir_url(SECUREPAY_FILE)));

require __DIR__.'/includes/load.php';
SecurePay::attach();

<?php
/**
 * @package   Axima
 * @author    Tomáš Blatný <blatny@kurzor.net>
 * @license   GPL-2.0+
 * @link      http://www.kurzor.net
 * @copyright 2016 Kurzor
 *
 * Plugin Name:       pays.cz – payment gateway
 * Plugin URI:        https://www.pays.cz
 * Description:       Payment gate, supporting EUR, USD and CZK. Contains all common payment methods, including mobile and SMS payments. Support for EET included.
 * Version:           2.4
 * Author:            Axima
 * Author URI:        https://www.axima-brno.cz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       axima-payment-gateway
 */

use Pays\PaymentGate\Plugin;


if (!defined('WPINC')) {
	die;
}

require_once __DIR__ . '/src/autoload.php';

function payscz_allowed_redirect_hosts( $hosts ) {
    $allowed_hosts = array('www.pays.cz');

    return array_merge( $hosts, $allowed_hosts );
}

add_filter( 'allowed_redirect_hosts', 'payscz_allowed_redirect_hosts', 10, 1 );

$plugin = new Plugin;
$plugin->register(__FILE__);

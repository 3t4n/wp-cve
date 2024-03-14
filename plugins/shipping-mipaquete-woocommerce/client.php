<?php
error_reporting(0);
/**
 * Plugin Name: Shipping Mipaquete Woocommerce
 * Description: Genera los envíos de tu tienda virtual en woocommerce de forma automatizada. Selecciona tu transportadora de preferencia o por criterio (precio, tiempo de entrega o calificación del servicio). Logra trazabilidad de todos tus envíos y solución de novedades en una sola plataforma. Todo esto gratis, solo pagas por tus envíos. Si tienes dudas, contáctanos al whatsapp +573164736403
 * Version: 3.2.10
 * Author: mipaquete.com
 * Author URI: https://mipaquete.com
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * WC tested up to: 3.6
 * WC requires at least: 2.6
 */



if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    require_once('config.php');
}


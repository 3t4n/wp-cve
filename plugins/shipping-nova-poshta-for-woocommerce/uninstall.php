<?php
/**
 * Shipping Nova Poshta for WooCommerce Uninstall
 *
 * Uninstalling Shipping Nova Poshta for WooCommerce deletes.
 *
 * @package Shipping Nova Poshta for WooCommerce
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/shipping-nova-poshta-for-woocommerce-pro.php' ) ) {
	require_once __DIR__ . '/shipping-nova-poshta-for-woocommerce-pro.php';
} else {
	require_once __DIR__ . '/shipping-nova-poshta-for-woocommerce.php';
}

if ( ! ( defined( 'NOVA_POSHTA_PRO' ) && defined( 'NOVA_POSHTA_LITE' ) ) ) {
	$db = nova_poshta()->make( 'DB' );
	$db->drop();
}

$transient_cache = nova_poshta()->make( 'Cache\TransientCache' );
$transient_cache->flush();

$object_cache = nova_poshta()->make( 'Cache\ObjectCache' );
$object_cache->flush();

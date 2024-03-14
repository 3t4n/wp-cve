<?php
/**
 * Plugin Name: Product Assembly / Gift Wrap / ... Cost for WooCommerce
 * Plugin URI: https://www.webdados.pt/wordpress/plugins/product-assembly-cost-for-woocommerce/
 * Description: Add an option to your WooCommerce products to enable assembly, gift wrap or any other service and optionally charge a fee for it.
 * Version: 3.3
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com
 * Text Domain: product-assembly-cost
 * Domain Path: /languages/
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * WC requires at least: 5.0
 * WC tested up to: 8.6
 * 
 * 	License: GNU General Public License v3.0
 * 	License URI: http://www.gnu.org/licenses/gpl-3.0.html
**/

/* WooCommerce CRUD ready */
/* WooCommerce HPOS ready - https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book */
/* WooCommerce block-based Cart and Checkout ready */


/*
	TO-DO:
	- Nova opção de custo no carrinho em vez de por produto. Pode ser custo fixo ou em cada produto ter o valor a somar ao total.
	- Custo percentual: https://wordpress.org/support/topic/assembly-as-of-product-cost/
	- Poder escolhar cobrar para todos os produtos ou apenas 1x (o valor maior) no final do carrinho (apenas se está a mostrar como taxa global)
	- Ecrã de opções independente do Geral do WooCommerce
	- Possibilidade de criar vários "serviços" e definir TODOS os settings por serviço
	- Na edição do produto, nova tab de serviços adicionais e possibilidade de activar um a um e marcar o preço
	- Deve ser possível comprar com vários serviços ou apenas um (definição global ou por produto??)
		- O que acontece se colocarmos um mesmo produto com opções diferentes?
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Main class */
function WC_Product_Extra_Service_Assembly() {
	return WC_Product_Extra_Service_Assembly::instance(); 
}

add_action( 'plugins_loaded', 'product_extra_service_assembly_init', 1 );
function product_extra_service_assembly_init() {
	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '4.0', '>=' ) ) {
		define( 'PRODUCT_ASSEMBLY_COST_BASENAME', plugin_basename( __FILE__ ) );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-product-extra-service-assembly.php' );
		$GLOBALS['WC_Product_Extra_Service_Assembly'] = WC_Product_Extra_Service_Assembly();
	}
}

/* HPOS & block-based Cart & Checkout Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */

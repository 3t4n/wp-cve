<?php
/**
 * SKU for WooCommerce - Regenerator Tool
 *
 * @version 1.6.1
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU_Tools_Regenerator' ) ) :

class Alg_WC_SKU_Tools_Regenerator {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 */
	function __construct() {
		$this->id   = 'regenerator';
		$this->desc = __( 'Regenerator Tool', 'sku-for-woocommerce' );
		add_filter( 'woocommerce_get_sections_alg_sku',         array( $this, 'settings_section' ) );
		add_action( 'alg_sku_for_woocommerce_regenerator_tool', array( $this, 'create_sku_tool' ) );
	}

	/**
	 * create_sku_tool.
	 *
	 * @version 1.2.0
	 */
	function create_sku_tool() {
		echo '<h3>' . __( 'SKU Regenerator', 'sku-for-woocommerce' ) . '</h3>';
		if ( 'yes' === get_option( 'alg_sku_for_woocommerce_enabled', 'yes' ) ) {
			do_action( 'alg_sku_for_woocommerce_before_regenerator_tool' );
			$button_template = '<p>' . '<a class="button button-primary" href="%s">%s</a>' . '</p>';
			echo sprintf( $button_template,
				add_query_arg( 'alg_preview_sku', '', remove_query_arg( 'alg_set_sku' ) ),
				__( 'Generate SKU preview for all products', 'sku-for-woocommerce' )
			);
			if ( isset( $_GET['alg_preview_sku'] ) ) {
				echo sprintf( $button_template,
					add_query_arg( 'alg_set_sku', '', remove_query_arg( 'alg_preview_sku' ) ),
					__( 'Set SKUs for all products', 'sku-for-woocommerce' )
				);
			}
			do_action( 'alg_sku_for_woocommerce_after_regenerator_tool' );
		} else {
			echo '<em>' . __( 'To use regenerator, SKU Generator for WooCommerce must be enabled in General settings tab.', 'sku-for-woocommerce' ) . '</em>';
		}
	}

	/**
	 * settings_section.
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}
}

endif;

return new Alg_WC_SKU_Tools_Regenerator();

<?php
/**
 * Formats the invoice fee sent to Nets.
 *
 * @package DIBS_Easy/Classes/Requests/Helpers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Nets_Easy_Payment_Method_Helper class.
 *
 * Class that formats the invoice fee sent to Nets.
 */
class Nets_Easy_Payment_Method_Helper {

	/**
	 * Gets invoice fee.
	 *
	 * @return array
	 */
	public static function get_invoice_fees() {
		$dibs_settings  = get_option( 'woocommerce_dibs_easy_settings' );
		$invoice_fee_id = $dibs_settings['dibs_invoice_fee'] ?? '';
		$items          = array();
		if ( $invoice_fee_id ) {
			$product = wc_get_product( $invoice_fee_id );
			if ( is_object( $product ) ) {
				$price_excl_tax = wc_get_price_excluding_tax( $product );
				$tax_data       = self::get_tax_data( $product );

				$invoice_items = array(
					'name' => 'easyinvoice',
					'fee'  => array(
						'reference'        => self::get_sku( $product, $invoice_fee_id ),
						'name'             => wc_dibs_clean_name( $product->get_name() ),
						'quantity'         => 1,
						'unit'             => __( 'pcs', 'dibs-easy-for-woocommerce' ),
						'unitPrice'        => intval( round( $price_excl_tax, 2 ) * 100 ),
						'taxRate'          => intval( round( $tax_data['tax_rate'], 2 ) * 100 ),
						'taxAmount'        => intval( round( $tax_data['tax_amount'], 2 ) * 100 ),
						'grossTotalAmount' => intval( round( $price_excl_tax + $tax_data['tax_amount'], 2 ) * 100 ),
						'netTotalAmount'   => intval( round( $price_excl_tax, 2 ) * 100 ),
					),
				);
				$items[]       = $invoice_items;
			}
		}
		return $items;
	}

	/**
	 * Gets tax data for invoice fee product.
	 *
	 * @param WC_Product $product The WooCommerce product used as invoice fee.
	 *
	 * @return array
	 */
	public static function get_tax_data( $product ) {
		$tmp_rates = WC_Tax::get_base_tax_rates( $product->get_tax_class() );
		$_vat      = array_shift( $tmp_rates );
		$item_tax  = array();
		if ( $product->is_taxable() && isset( $_vat['rate'] ) ) {
			$item_tax['tax_rate']   = $_vat['rate'];
			$item_tax['tax_amount'] = ( $_vat['rate'] * 0.01 ) * wc_get_price_excluding_tax( $product );
		} else {
			$item_tax['tax_rate']   = 0;
			$item_tax['tax_amount'] = 0;
		}
		return $item_tax;
	}

	/**
	 * Gets the sku for the invoice fee item.
	 *
	 * @param WC_Product $product The WooCommerce product.
	 * @param string     $invoice_fee_id The WooCommerce product ID.
	 * @return string
	 */
	public static function get_sku( $product, $invoice_fee_id ) {
		$part_number = $product->get_sku();
		if ( empty( $part_number ) ) {
			$part_number = $product->get_id();
		}
		return substr( $part_number, 0, 32 );
	}
}

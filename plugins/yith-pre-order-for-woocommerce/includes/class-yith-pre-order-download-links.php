<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YITH_Pre_Order_Download_Links' ) ) {
	/**
	 * Class YITH_Pre_Order_Download_Links
	 */
	class YITH_Pre_Order_Download_Links {

		/**
		 * Main Instance
		 *
		 * @var YITH_Pre_Order_Download_Links
		 * @since  1.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_Pre_Order_Download_Links
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Construct
		 *
		 * @since 1.3.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_get_item_downloads', array( $this, 'hide_pre_order_download_link_from_single_order_page' ), 10, 2 );
			add_filter( 'woocommerce_customer_get_downloadable_products', array( $this, 'delete_pre_order_download_from_array' ) );
		}

		/**
		 * Hide the download links for ongoing pre-orders in the single order page (My account).
		 *
		 * @param array                 $files Downloadable files array.
		 * @param WC_Order_Item_Product $item The WC_Order_Item_Product object.
		 *
		 * @return array
		 */
		public function hide_pre_order_download_link_from_single_order_page( $files, $item ) {

			if ( ( isset( $item['ywpo_item_for_sale_date'] ) && $item['ywpo_item_for_sale_date'] > time() ) ) {
				foreach ( $files as $download_id => $file ) {
					unset( $files[ $download_id ] );
				}
			}

			return $files;
		}

		/**
		 * Remove customer downloads for ongoing pre-orders.
		 *
		 * @param array $downloads Array of downloadable products.
		 *
		 * @return array
		 */
		public function delete_pre_order_download_from_array( $downloads ) {

			foreach ( $downloads as $key => &$download ) {
				$order = wc_get_order( $download['order_id'] );

				if ( $order instanceof WC_Order && 'yes' === $order->get_meta( '_order_has_preorder' ) ) {
					foreach ( $order->get_items() as $item ) {
						if ( ( isset( $item['ywpo_item_for_sale_date'] ) && $item['ywpo_item_for_sale_date'] > time() ) ) {
							$product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
							if ( $product_id === $download['product_id'] ) {
								unset( $downloads[ $key ] );
								break;
							}
						}
					}
				}
			}

			return $downloads;
		}

	}
}

/**
 * Unique access to instance of YITH_Pre_Order_Download_Links class
 *
 * @return YITH_Pre_Order_Download_Links
 */
function YITH_Pre_Order_Download_Links() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_Pre_Order_Download_Links::get_instance();
}

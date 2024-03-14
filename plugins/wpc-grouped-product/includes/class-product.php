<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Product_Woosg' ) && class_exists( 'WC_Product' ) ) {
	class WC_Product_Woosg extends WC_Product {
		public function __construct( $product = 0 ) {
			if ( WPCleverWoosg_Helper()::get_setting( 'archive_purchasable', 'no' ) === 'yes' ) {
				$this->supports[] = 'ajax_add_to_cart';
			}

			parent::__construct( $product );
		}

		public function get_type() {
			return 'woosg';
		}

		public function add_to_cart_url() {
			$product_id = $this->id;

			if ( $this->is_purchasable() && $this->is_in_stock() && ( WPCleverWoosg_Helper()::get_setting( 'archive_purchasable', 'no' ) === 'yes' ) ) {
				$url = remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product_id ) );
			} else {
				$url = get_permalink( $product_id );
			}

			$url = apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );

			return apply_filters( 'woosg_product_add_to_cart_url', $url, $this );
		}

		public function add_to_cart_text() {
			if ( $this->is_purchasable() && $this->is_in_stock() ) {
				if ( WPCleverWoosg_Helper()::get_setting( 'archive_purchasable', 'no' ) === 'yes' ) {
					$text = WPCleverWoosg_Helper()::localization( 'button_add', esc_html__( 'Add to cart', 'wpc-grouped-product' ) );
				} else {
					$text = WPCleverWoosg_Helper()::localization( 'button_select', esc_html__( 'Select options', 'wpc-grouped-product' ) );
				}
			} else {
				$text = WPCleverWoosg_Helper()::localization( 'button_read', esc_html__( 'Read more', 'wpc-grouped-product' ) );
			}

			return apply_filters( 'woosg_product_add_to_cart_text', $text, $this );
		}

		public function single_add_to_cart_text() {
			$text = WPCleverWoosg_Helper()::localization( 'button_single', esc_html__( 'Add to cart', 'wpc-grouped-product' ) );

			return apply_filters( 'woosg_product_single_add_to_cart_text', $text, $this );
		}

		public function get_price( $context = 'view' ) {
			if ( ( $context === 'view' ) && ( (float) $this->get_regular_price() == 0 ) ) {
				return '0';
			}

			if ( ( $context === 'view' ) && ( (float) parent::get_price( $context ) == 0 ) ) {
				return '0';
			}

			return parent::get_price( $context );
		}

		// extra functions

		public function has_variables() {
			if ( $items = $this->get_items() ) {
				foreach ( $items as $item ) {
					$item_product = wc_get_product( $item['id'] );

					if ( $item_product && $item_product->is_type( 'variable' ) ) {
						return true;
					}
				}
			}

			return false;
		}

		public function get_ids() {
			$product_id = $this->id;

			return apply_filters( 'woosg_get_ids', get_post_meta( $product_id, 'woosg_ids', true ), $this );
		}

		public function get_ids_str() {
			$ids_arr = [];
			$ids     = $this->get_ids();

			if ( is_array( $ids ) ) {
				foreach ( $ids as $item ) {
					if ( ! empty( $item['id'] ) ) {
						$ids_arr[] = $item['id'] . '/' . $item['qty'];
					}
				}

				$ids_str = implode( ',', $ids_arr );
			} else {
				$ids_str = $ids;
			}

			return apply_filters( 'woosg_get_ids_str', $ids_str, $this );
		}

		public function get_items() {
			$data = [];

			if ( ( $ids = $this->get_ids() ) && ! empty( $ids ) ) {
				if ( is_array( $ids ) ) {
					// new version 4.0
					foreach ( $ids as $item ) {
						$item = array_merge( [
							'id'    => 0,
							'sku'   => '',
							'qty'   => 0,
							'attrs' => []
						], $item );

						// check for variation
						if ( ( $parent_id = wp_get_post_parent_id( $item['id'] ) ) && ( $parent = wc_get_product( $parent_id ) ) ) {
							$parent_sku = $parent->get_sku();
						} else {
							$parent_sku = '';
						}

						if ( apply_filters( 'woosg_use_sku', false ) && ! empty( $item['sku'] ) && ( $item['sku'] !== $parent_sku ) && ( $new_id = wc_get_product_id_by_sku( $item['sku'] ) ) ) {
							// get product id by SKU for export/import
							$item['id'] = $new_id;
						}

						$data[] = $item;
					}
				} else {
					$items = explode( ',', $ids );

					if ( is_array( $items ) && count( $items ) > 0 ) {
						foreach ( $items as $item ) {
							$item_data = explode( '/', $item );
							$data[]    = [
								'id'  => apply_filters( 'woosg_item_id', absint( isset( $item_data[0] ) ? $item_data[0] : 0 ) ),
								'qty' => apply_filters( 'woosg_item_qty', (float) ( isset( $item_data[1] ) ? $item_data[1] : 0 ) )
							];
						}
					}
				}
			}

			return apply_filters( 'woosg_get_items', $data, $this );
		}
	}
}

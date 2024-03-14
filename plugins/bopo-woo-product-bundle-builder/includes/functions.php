<?php

/**
 * Function include all files in folder
 *
 * @param $path   Directory address
 * @param $ext    array file extension what will include
 * @param $prefix string Class prefix
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'vi_include_folder' ) ) {
	function vi_include_folder( $path, $prefix = '', $ext = array( 'php' ) ) {

		/*Include all files in payment folder*/
		if ( ! is_array( $ext ) ) {
			$ext = explode( ',', $ext );
			$ext = array_map( 'trim', $ext );
		}
		$sfiles = scandir( $path );
		foreach ( $sfiles as $sfile ) {
			if ( $sfile != '.' && $sfile != '..' ) {
				if ( is_file( $path . "/" . $sfile ) ) {
					$ext_file  = pathinfo( $path . "/" . $sfile );
					$file_name = $ext_file['filename'];
					if ( $ext_file['extension'] ) {
						if ( in_array( $ext_file['extension'], $ext ) ) {
							$class = preg_replace( '/\W/i', '_', $prefix . ucfirst( $file_name ) );

							if ( ! class_exists( $class ) ) {
								require_once $path . $sfile;
								if ( class_exists( $class ) ) {
									new $class;
								}
							}
						}
					}
				}
			}
		}
	}
}

if ( ! function_exists( 'bopobb_woocommerce_version_check' ) ) {
	function bopobb_woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;

		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'bopobb_sanitize_block' ) ) {
	function bopobb_sanitize_block( $var ) {
		return stripslashes( $var );
	}
}

if ( ! function_exists( 'bopobb_get_template' ) ) {
	function bopobb_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		$located = $default_path . $template_name;

		if ( ! file_exists( $located ) ) {
			wc_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'woo-bopo-bundle' ), '<code>' . $located . '</code>' ), '2.1' );

			return;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$located = $located;


		include( $located );

	}
}

if ( ! function_exists( 'bopobb_register_product_type' ) ):
	function bopobb_register_product_type() {
		class VI_WOO_BOPO_Type extends WC_Product {
			protected $items = null;

			public function __construct( $product = 0 ) {
				$this->supports[] = 'ajax_add_to_cart';

				parent::__construct( $product );

				$this->build_data();
			}

			public function get_type() {
				return 'bopobb';
			}

			public function is_manage_stock() {

				return $this->get_meta('bopobb_manage_stock', true ) === 'on';
			}

			public function is_fixed_price() {
				$product_id  = $this->id;
				$fixed_price = get_post_meta( $product_id, '_regular_price', true );
				if ( ! empty( $fixed_price ) ) {
					return true;
				} else {
					return false;
				}
			}

			public function get_discount_amount() {
				$discount_amount = array();
				$product_id  = $this->id;

				// discount amount
				if ( ! $this->is_fixed_price() ) {
					$discount_amount = $this->get_data( 'discount' );
				} else {
					$_price_meta = get_post_meta( $product_id, '_regular_price', true );
					$_sale_meta = get_post_meta( $product_id, '_sale_price', true );
					$_price          = empty( $_price_meta ) ? 0 : floatval( $_price_meta );
					$_salePrice      = empty( $_sale_meta ) ? $_price : floatval( $_sale_meta );
					$discount_amount = ( ( $_price - $_salePrice ) * 100 ) / $_price;
				}

				return $discount_amount;
			}

			public function get_discount() {
				$discount_percentage = 0;

				// discount percentage
				if ( ! $this->is_fixed_price() && ! $this->get_discount_amount() && ( $discount_percentage = $this->get_meta('bopobb_discount', true ) ) && is_numeric( $discount_percentage ) && ( (float) $discount_percentage < 100 ) && ( (float) $discount_percentage > 0 ) ) {
					$discount_percentage = (float) $discount_percentage;
				}

				return $discount_percentage;
			}

			public function get_ids() {
				$product_id = $this->id;
				$items_data = $this->items['items'];
				$ids        = '';
				$isDefault  = true;
				foreach ( $items_data as $item ) {
					if ( $item['bopobb_bpi_set_default'] != 1 || ! isset( $item['bopobb_bpi_default_product'] ) ) {
						$isDefault = false;
					}
				}
				if ( $isDefault ) {
					foreach ( $items_data as $item ) {
						$id_array         = explode( '/', $item['bopobb_bpi_default_product'] );
						$variation_string = '';
						if ( count( $id_array ) > 1 ) {
							$variation_string = '/' . $id_array[1];
						}
						if ( $ids == '' ) {
							$ids .= $id_array[0] . '/' . $item['bopobb_bpi_quantity'] . $variation_string;
						} else {
							$ids .= ',' . $item['bopobb_bpi_default_product'] . '/' . $item['bopobb_bpi_quantity'] . $variation_string;
						}
					}
				}

				return $ids;
			}

			public function build_items( $ids = null ) {
				$items = array();

				if ( ! $ids ) {
					$ids = $this->get_ids();
				}

				if ( $ids ) {
					$ids_arr = explode( ',', $ids );

					if ( is_array( $ids_arr ) && count( $ids_arr ) > 0 ) {
						foreach ( $ids_arr as $ids_item ) {
							$data = explode( '/', $ids_item );

							if ( $pid = absint( isset( $data[0] ) ? $data[0] : 0 ) ) {
								$item        = [];
								$item['id']  = $pid;
								$item['qty'] = (float) ( isset( $data[1] ) ? $data[1] : 1 );
								if ( isset( $data[2] ) ) {
									$item['variations'] = $data[2];
								}
								$items[] = $item;
							}
						}
					}
				}

				return $items;
			}

			public function build_data( $items = null ) {
				if ( ! $items ) {
					$items = $this->get_data();
				}

				$this->items = $items;
			}

			public function get_data( $target = '' ) {
				$product_id     = $this->id;
				$bundle_data    = array();
				$items_data     = array();
				$items_discount = array();
				$item_discount  = array();
				$item_count     = $this->get_meta('bopobb_count', true );
				$item_f_price   = get_post_meta( $product_id, '_regular_price', true ) ? get_post_meta( $product_id, '_regular_price', true ) : 0;
				$item_s_price   = get_post_meta( $product_id, '_sale_price', true ) ? get_post_meta( $product_id, '_sale_price', true ) : 0;
				$item_title     = $this->get_meta('bopobb_title', true );
				for ( $i = 0; $i < $item_count; $i ++ ) {
					$meta_item = $this->get_meta('bopobb_item_' . $i, true );
					if ( $meta_item ) {
						if ( $target == 'discount' ) {
							if ( isset( $meta_item['bopobb_bpi_discount'] ) ) {
								$item_discount['by'] = $meta_item['bopobb_bpi_discount'];
							}
							if ( isset( $meta_item['bopobb_bpi_discount_number'] ) ) {
								$item_discount['number'] = $meta_item['bopobb_bpi_discount_number'];
							} else {
								$item_discount['number'] = 0;
							}
							array_push( $items_discount, $item_discount );
						}
						array_push( $items_data, $meta_item );
					}
				}
				if ( $target == 'discount' ) {
					return $items_discount;
				}
				$bundle_data['count'] = $item_count;
				$bundle_data['fixed'] = $item_f_price;
				$bundle_data['sale']  = $item_s_price;
				$bundle_data['title'] = $item_title;
				$bundle_data['items'] = $items_data;

				return $bundle_data;
			}

			public function get_items() {
				return $this->items;
			}
		}
	}
endif;
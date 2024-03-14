<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VI_WOO_BOPO_BUNDLE_Helper' ) ) {
	class VI_WOO_BOPO_BUNDLE_Helper {
		public static function bopohp_get_price( $product, $min_or_max = 'min' ) {
			switch ( $min_or_max ) {
				case 'src':
					return wc_get_price_excluding_tax( $product );
					break;
				default:
					return $product->get_price();
					break;
			}
		}

		public static function bopohp_build_items( $ids = null ) {
			$items = array();

			if ( ! $ids ) {
				return;
			}

			if ( $ids ) {
				$ids_arr = explode( ',', $ids );

				if ( is_array( $ids_arr ) && count( $ids_arr ) > 0 ) {
					foreach ( $ids_arr as $ids_item ) {
						$data = explode( '/', $ids_item );

						if ( $pid = absint( isset( $data[0] ) ? $data[0] : 0 ) ) {
							$items[] = array(
								'id'  => $pid,
								'qty' => (float) ( isset( $data[1] ) ? $data[1] : 1 )
							);
						}
					}
				}
			}

			return $items;
		}

		public static function bopohp_get_price_to_display( $product, $qty = 1, $min_or_max = 'min' ) {
			return (float) wc_get_price_to_display( $product, array(
				'price' => self::bopohp_get_price( $product, $min_or_max ),
				'qty'   => $qty
			) );
		}

		public static function bopohp_get_original_price_total( $product, $qty = 1, $min_or_max = 'min' ) {
			$product_params['product_id']    = $product->get_ID();
			$product_params['product_price'] = wc_get_price_excluding_tax( $product );
			$price_params                    = apply_filters( 'bopobb_get_original_price', $product_params );

			return (float) $price_params['product_price'] * $qty;
		}

		public static function bopohp_clean_ids( $ids ) {
//			$ids = preg_replace( '/[^,.:_\/\|a-zA-Z0-9]/', '', $ids );

			return $ids;
		}

		public static function bopohp_minify_items( $items ) {
			$minify_items = array();

			foreach ( $items as $item ) {
				if ( empty( $minify_items ) ) {
					$minify_items[] = $item;
				} else {
					$has_item = false;

					foreach ( $minify_items as $key => $minify_item ) {
						if ( $minify_item['id'] === $item['id'] && $minify_item['variations'] === $item['variations'] ) {
							$minify_items[ $key ]['qty'] += $item['qty'];
							$has_item                    = true;
							continue;
						}
					}

					if ( ! $has_item ) {
						$minify_items[] = $item;
					}
				}
			}

			return $minify_items;
		}

		public static function bopobb_decode_variations( $vr_arr, $mode = 0 ) {
			$o                    = '';
			$attr_array           = explode( '&', $vr_arr );
			$attr_array_formatted = [];
			foreach ( $attr_array as $attr_array_v ) {
				$attr_str_arr                             = explode( '=', $attr_array_v );
				$attr_array_formatted[ $attr_str_arr[0] ] = isset( $attr_str_arr[1] ) ? $attr_str_arr[1] : '';
			}
			if ( ! $mode ) {
				return $attr_array_formatted;
			}
			foreach ( $attr_array_formatted as $attr_k => $attr_v ) {
				$cur_key  = substr( $attr_k, 10 );
				$cur_term = get_term_by( 'slug', $attr_v, $cur_key );

				if ( ! empty( $cur_term ) ) {
					$o .= ' - ' . $cur_term->name;
				}
			}

			return $o;
		}

		public static function bopobb_build_title( $vr_arr ) {
			$o = '';
			if ( is_array( $vr_arr ) || is_object( $vr_arr ) ) {
				foreach ( $vr_arr as $att_k => $att_v ) {
					$cur_key  = substr( $att_k, 10 );
					$cur_term = get_term_by( 'slug', $att_v, $cur_key );
					if ( ! empty( $cur_term ) ) {
						$o .= ' - ' . $cur_term->name;
					}
				}
			}

			return $o;
		}

		public static function bopobb_build_variations( $vr_arr ) {
			$o = '';
			if ( is_array( $vr_arr ) || is_object( $vr_arr ) ) {
				foreach ( $vr_arr as $vr_k => $vr_v ) {
					if ( empty( $o ) ) {
						$o = $vr_k . '=' . $vr_v;
					} else {
						$o .= '&' . $vr_k . '=' . $vr_v;
					}
				}
			}

			return $o;
		}

		public static function bopobb_is_variation_allow( $pr_prd, $vr_id, $vr_arr, $vr_cp ) {
			$vr_pre   = self::bopobb_get_any( $pr_prd, $vr_id, $vr_arr );
			$vr_ready = self::bopobb_get_simple_compare( $vr_cp );
			if ( ( $vr_ready ) && ( is_array( $vr_ready ) || is_object( $vr_ready ) ) ) {
				if ( is_array( $vr_pre ) || is_object( $vr_pre ) ) {
					if ( in_array( $vr_pre, $vr_ready ) ) {
						return false;
					}
				} else {
					if ( in_array( [ $vr_pre ], $vr_ready ) ) {
						return false;
					}
				}
			}

			return true;
		}

		public static function bopobb_get_variation_default( $pr_prd, $vr_id, $vr_arr ) {
			$vr_op  = [];
			$vr_pre = self::bopobb_get_any( $pr_prd, $vr_id, $vr_arr );
			$vr_op  = self::bopobb_any_default( $vr_pre );

			return $vr_op;
		}

		public static function bopobb_get_variations( $pr_prd, $vr_id, $vr_arr, $vr_cp ) {
			// get any array
			$vr_op    = [];
			$vr_pre   = self::bopobb_get_any( $pr_prd, $vr_id, $vr_arr );
			$vr_op    = self::bopobb_calc_any( $vr_pre );
			$vr_ready = self::bopobb_get_simple_compare( $vr_cp );
			if ( ! is_array( $vr_op ) || empty( $vr_op ) ) {

				return array();
			}
			// check with compare array
			foreach ( $vr_op as $ck_k => $ck_v ) {
				if ( ( $vr_ready ) && ( is_array( $vr_ready ) || is_object( $vr_ready ) ) ) {
					if ( is_array( $ck_v ) || is_object( $ck_v ) ) {
						if ( in_array( $ck_v, $vr_ready ) ) {
							unset( $vr_op[ $ck_k ] );
						}
					} else {

						if ( in_array( [ $ck_v ], $vr_ready ) ) {
							unset( $vr_op[ $ck_k ] );
						}
					}
				}
			}

			return array_values( $vr_op );
		}

		public static function bopobb_get_simple_compare( $vr_cp ) {
			if ( is_array( $vr_cp ) || is_object( $vr_cp ) ) {
				foreach ( $vr_cp as $vr_k2 => $vr_v2 ) {
					if ( isset( $vr_v2 ['id'] ) ) {
						unset( $vr_cp[ $vr_k2 ]['id'] );
						continue;
					}
				}

				return array_values( $vr_cp );
			} else {
				return false;
			}
		}

		public static function bopobb_set_array( $vr_cp, $vr_id ) {
			$vr_id = [ 'id' => $vr_id ];
			if ( is_array( $vr_cp ) || is_object( $vr_cp ) ) {
				foreach ( $vr_cp as $vr_k => $vr_v ) {
					if ( is_array( $vr_v ) || is_object( $vr_v ) ) {
						$vr_cp[ $vr_k ] = array_merge( $vr_id, $vr_cp[ $vr_k ] );
					} else {
						$vr_cp[ $vr_k ] = array_merge( $vr_id, [ $vr_cp[ $vr_k ] ] );
					}
				}
			}

			return $vr_cp;
		}

		public static function bopobb_get_any( $pr_prd, $vr_id, $vr_arr ) {
			$o = [];
			foreach ( $vr_arr as $attr_k => $attr_v ) {
				if ( empty( $attr_v ) ) {
					$cur_key  = substr( $attr_k, 10 );
					$get_atts = $pr_prd->get_variation_attributes();
					if ( isset( $get_atts[ $cur_key ] ) ) {
						$o[] = $get_atts[ $cur_key ];
					} elseif ( isset( $get_atts[ ucfirst( $cur_key ) ] ) ) {
						$o[] = $get_atts[ ucfirst( $cur_key ) ];
					}
				} else {
					$o[] = $attr_v;
				}
			}

			return $o;
		}

		public static function bopobb_any_default( $any_arr ) {
			if ( is_array( $any_arr ) || is_object( $any_arr ) ) {
				$o = [];
				foreach ( $any_arr as $att_arr ) {
					if ( is_array( $att_arr ) || is_object( $att_arr ) ) {
						$o[] = $att_arr[0];
					} else {
						$o[] = $att_arr;
					}
				}

				return $o;
			} else {
				return $any_arr;
			}
		}

		public static function bopobb_calc_any( $any_arr ) {
			$o           = [];
			$_i          = 0;
			$count_input = count( $any_arr );

			return self::bopobb_recursive_any( $o, $any_arr, $_i, intval( $count_input ) );
		}

		public static function bopobb_recursive_any( $arr_o, $arr_any, $index, $count ) {
			if ( $index >= $count - 1 ) {
				return $arr_any[ $index ];
			} else {
				return self::bopobb_map_array( $arr_any[ $index ], self::bopobb_recursive_any( $arr_o, $arr_any, $index + 1, $count ) );
			}
		}

		public static function bopobb_map_array( $arr_1, $arr_2 ) {
			$o = [];
			if ( is_array( $arr_1 ) || is_object( $arr_1 ) ) {
				foreach ( $arr_1 as $attr_v ) {
					if ( is_array( $arr_2 ) || is_object( $arr_2 ) ) {
						foreach ( $arr_2 as $attr_v2 ) {
							if ( is_array( $attr_v ) || is_object( $attr_v ) ) {
								if ( is_array( $attr_v2 ) || is_object( $attr_v2 ) ) {
									$o[] = array_merge( $attr_v, $attr_v2 );
								} else {
									$o[] = array_merge( $attr_v, [ $attr_v2 ] );
								}
							} else {
								if ( is_array( $attr_v2 ) || is_object( $attr_v2 ) ) {
									$o[] = array_merge( [ $attr_v ], $attr_v2 );
								} else {
									$o[] = array_merge( [ $attr_v ], [ $attr_v2 ] );
								}
							}
						}
					} else {
						if ( is_array( $attr_v ) || is_object( $attr_v ) ) {
							$o[] = array_merge( $attr_v, [ $arr_2 ] );
						} else {
							$o[] = array_merge( [ $attr_v ], [ $arr_2 ] );
						}
					}
				}
			} else {
				if ( is_array( $arr_2 ) || is_object( $arr_2 ) ) {
					foreach ( $arr_2 as $attr_v2 ) {
						if ( is_array( $attr_v2 ) || is_object( $attr_v2 ) ) {
							$o[] = array_merge( [ $arr_1 ], $attr_v2 );
						} else {
							$o[] = array_merge( [ $arr_1 ], [ $attr_v2 ] );
						}
					}
				} else {
					$o[] = array_merge( [ $arr_1 ], [ $arr_2 ] );
				}
			}

			return $o;
		}

		public static function bopobb_tax_rate( $product ) {
			$return_tax = 0;

			if ( $product->is_taxable() ) {
				if ( ! wc_prices_include_tax() ) {
					$tax_rates  = WC_Tax::get_rates( $product->get_tax_class() );
					$return_tax = self::bopobb_tax_from_class( $tax_rates );
				} else {
					$tax_rates      = WC_Tax::get_rates( $product->get_tax_class() );
					$base_tax_rates = WC_Tax::get_base_tax_rates( $product->get_tax_class( 'unfiltered' ) );
					$return_tax = self::bopobb_tax_from_class( $base_tax_rates );
					/**
					 * If the customer is excempt from VAT, remove the taxes here.
					 * Either remove the base or the user taxes depending on woocommerce_adjust_non_base_location_prices setting.
					 */
					if ( ! empty( WC()->customer ) && WC()->customer->get_is_vat_exempt() ) { // @codingStandardsIgnoreLine.
						$return_tax = 0;
						/**
						 * The woocommerce_adjust_non_base_location_prices filter can stop base taxes being taken off when dealing with out of base locations.
						 * e.g. If a product costs 10 including tax, all users will pay 10 regardless of location and taxes.
						 * This feature is experimental @since 2.4.7 and may change in the future. Use at your risk.
						 */
					} elseif ( $tax_rates !== $base_tax_rates && apply_filters( 'woocommerce_adjust_non_base_location_prices', true ) ) {
						$return_tax = self::bopobb_tax_from_class( $tax_rates );
					}
				}
			}

			return $return_tax;
		}

		public static function bopobb_tax_from_class( $rates = array() ) {
			$taxes_rate = 0;
			if ( $rates ) {
				foreach ( $rates as $key => $rate ) {
					if ( $rate['rate'] ) {
						$taxes_rate = $rate['rate'];
					}
				}
			}

			return $taxes_rate;
		}

		public static function bopobb_price_standard( $product, $line_price ) {
			$return_price = $line_price;
			if ( $product->is_taxable() && wc_prices_include_tax() ) {
				$base_tax_rates = WC_Tax::get_base_tax_rates( $product->get_tax_class( 'unfiltered' ) );
				$standard_tax   = WC_Tax::calc_tax( $line_price, $base_tax_rates, false );
				if ( 'yes' === get_option( 'woocommerce_tax_round_at_subtotal' ) ) {
					$standard_tax = array_sum( $standard_tax );
				} else {
					$standard_tax = array_sum( array_map( 'wc_round_tax_total', $standard_tax ) );
				}
				$return_price = $line_price + $standard_tax;
			}

			return $return_price;
		}

		public static function bopobb_price_show( $product, $line_price ) {
			$return_price = $line_price;
			$show_tax     = 'incl' === get_option( 'woocommerce_tax_display_shop' ) ? 1 : 0;
			if ( $product->is_taxable() && $show_tax ) {
				if ( ! wc_prices_include_tax() ) {
					$tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
					$taxes     = WC_Tax::calc_tax( $line_price, $tax_rates, false );

					if ( 'yes' === get_option( 'woocommerce_tax_round_at_subtotal' ) ) {
						$taxes_total = array_sum( $taxes );
					} else {
						$taxes_total = array_sum( array_map( 'wc_round_tax_total', $taxes ) );
					}

					$return_price = $line_price + $taxes_total;
				} else {
					$tax_rates      = WC_Tax::get_rates( $product->get_tax_class() );
					$base_tax_rates = WC_Tax::get_base_tax_rates( $product->get_tax_class( 'unfiltered' ) );
					if ( ! empty( WC()->customer ) && WC()->customer->get_is_vat_exempt() ) {

						return $return_price;
					} else {
						if ( apply_filters( 'woocommerce_adjust_non_base_location_prices', true ) ) {
							$additional_tax = $tax_rates !== $base_tax_rates ?
								WC_Tax::calc_tax( $line_price, $tax_rates, false ) :
								WC_Tax::calc_tax( $line_price, $base_tax_rates, false );
						} else {
							$additional_tax = WC_Tax::calc_tax( $line_price, $base_tax_rates, false );
						}
						if ( 'yes' === get_option( 'woocommerce_tax_round_at_subtotal' ) ) {
							$additional_tax = array_sum( $additional_tax );
						} else {
							$additional_tax = array_sum( array_map( 'wc_round_tax_total', $additional_tax ) );
						}
						$return_price = $line_price + $additional_tax;
					}
				}
			}

			return $return_price;
		}
	}

	new VI_WOO_BOPO_BUNDLE_Helper();
}
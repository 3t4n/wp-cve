<?php

defined( 'ABSPATH' ) || exit;

#[AllowDynamicProperties]

  class WFACP_Product_Switcher_Merge_Tags {

	public static $threshold_to_date = 30;
	protected static $pro;
	protected static $price_data;
	protected static $_data_shortcode = array();
	protected static $coupon = '';
	protected static $cart_item = [];
	protected static $product_data = [];
	protected static $cart_item_key = '';

	/**
	 * Maybe try and parse content to found the wfacp merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public static function maybe_parse_merge_tags( $content, $price_data, $pro = false, $product_data = [], $cart_item = [], $cart_item_key = '' ) {

		$matched_tags = self::get_matched_tags( $content );

		if ( empty( $matched_tags ) ) {
			return $content;
		}

		self::$price_data = $price_data;
		if ( $pro instanceof WC_Product ) {
			self::$pro = $pro;
		}
		if ( ! empty( $product_data ) ) {
			self::$product_data = $product_data;
		}
		if ( ! empty( $cart_item ) ) {
			self::$cart_item = $cart_item;
		}
		if ( '' != $cart_item_key ) {
			self::$cart_item_key = $cart_item_key;
		}
		$have_save_merge_tag = self::check_saving_tags_present( $matched_tags );
		if ( $have_save_merge_tag ) {
			$percentage = self::saving_percentage();
			if ( '' == $percentage ) {
				return '';
			}
		}
		$content = self::parse_merge_tags( $content, $matched_tags );

		return $content;

	}

	private static function saving_percentage() {

		$regular_org = floatval( self::$price_data['regular_org'] );
		$price       = floatval( self::$price_data['price'] );
		$percentage  = 0;
		if ( $regular_org == 0 ) {
			return '';
		}
		// get price of product is zero means 100% off
		if ( $price == 0 ) {
			return 100 . '%';
		}
		if ( $regular_org == $price ) {
			return '';
		}
		if ( $price > $regular_org ) {
			return '';
		}
		$temp_percentage = ( ( ( $price / $regular_org ) * 100 ) );
		if ( $temp_percentage > 0 ) {
			$percentage = 100 - ( ( $price / $regular_org ) * 100 );
		} else {
			return '';
		}

		$t = absint( $percentage );
		if ( is_numeric( $t ) && $t > 0 ) {

			if ( ( $percentage / $t ) > 0 ) {
				$percentage = apply_filters( 'wfacp_percentage_number_format', number_format( $percentage, 2 ), $percentage );
			}
			unset( $t );

			return $percentage . '%';
		}

		return '';
	}

	private static function parse_merge_tags( $content, $tags ) {

		//if match found
		if ( $tags && is_array( $tags ) && count( $tags ) > 0 ) {
			//iterate over the found matches
			foreach ( $tags as $exact_match ) {
				//preserve old match
				$old_match = $exact_match;
				$single    = str_replace( '{{', '', $old_match );
				$single    = str_replace( '}}', '', $single );
				if ( method_exists( __CLASS__, $single ) ) {
					$get_parsed_value = self::$single();
					$content          = trim( str_replace( $old_match, $get_parsed_value, $content ) );
				}
			}
		}

		return $content;
	}

	private static function get_matched_tags( $content ) {
		$get_all      = self::get_all_tags();
		$get_all_tags = wp_list_pluck( $get_all, 'tag' );
		//iterating over all the merge tags
		$matches = array();
		if ( $get_all_tags && is_array( $get_all_tags ) && count( $get_all_tags ) > 0 ) {
			foreach ( $get_all_tags as $tag ) {
				$re         = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str        = $content;
				$temp_match = [];
				preg_match_all( $re, $str, $temp_match );
				if ( is_array( $temp_match[0] ) && count( $temp_match[0] ) > 0 ) {
					$matches = array_merge( $matches, $temp_match[0] );
				}
			}
		}

		return $matches;

	}


	public static function get_all_tags() {

		$tags = array(
			array(
				'name' => __( 'Subscription Product String', 'woofunnels-aero-checkout' ),
				'tag'  => 'subscription_product_string',
			),
			array(
				'name' => __( 'You Save', 'woofunnels-aero-checkout' ),
				'tag'  => 'saving_value',
			),
			array(
				'name' => __( 'You Save', 'woofunnels-aero-checkout' ),
				'tag'  => 'saving_percentage',
			),
			array(
				'name' => __( 'Quantity', 'woocommerce' ),
				'tag'  => 'quantity',
			),
			array(
				'name' => __( 'Coupon code', 'woocommerce' ),
				'tag'  => 'coupon_code',
			),
			array(
				'name' => __( 'Coupon_value', 'woocommerce' ),
				'tag'  => 'coupon_value',
			),

		);

		return $tags;
	}

	public static function parse_coupon_merge_tag( $content, $coupon ) {

		if ( '' == $coupon ) {
			return '';
		}
		self::$coupon = $coupon;

		$matched_tags = self::get_matched_tags( $content );

		return self::parse_merge_tags( $content, $matched_tags );

	}

	private static function saving_value() {
		$difference = floatval( self::$price_data['regular_org'] ) - floatval( self::$price_data['price'] );

		if ( 0 < $difference ) {
			return wc_price( $difference );
		}

		return '';
	}

	private static function quantity() {
		return self::$price_data['quantity'];
	}

	private static function subscription_product_string() {
		if ( self::$pro instanceof WC_Product ) {
			return WFACP_Common::subscription_product_string( self::$pro, self::$product_data, self::$cart_item, self::$cart_item_key );
		} else {
			return '';
		}

	}

	private static function coupon_code() {
		return '<strong>' . WFACP_Common::wc_cart_totals_coupon_label( self::$coupon ) . '</strong>';
	}

	private static function coupon_value() {
		return WFACP_Common::wc_cart_totals_coupon_total( self::$coupon );
	}

	private static function check_saving_tags_present( $matched_tags ) {
		$status = array_intersect( [ '{{saving_value}}', '{{saving_percentage}}' ], $matched_tags );
		if ( is_null( $status ) ) {
			return false;
		}

		return ! empty( $status );
	}
}

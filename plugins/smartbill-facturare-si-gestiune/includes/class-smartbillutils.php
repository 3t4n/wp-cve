<?php
/**
 * SmartBill Utilities class.
 *
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @copyright  Copyright 2018 © Intelligent IT SRL. All rights reserved.
 */

/**
 * SmartBill Utilities class.
 *
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 */
class SmartBillUtils {
	/**
	 * This will store the products measuring unit.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Array    $stocks    SmartBill product measuring units.
	 */
	public static $stocks = null;


	/**
	 * Get formated order products
	 *
	 * @param WC_Order $order  woocommerce order.
	 * @param Array    $product_settings SmartBill product settings.
	 *
	 * @return Array   $products
	 */
	public static function get_order_products( $order, $product_settings ) {
		$products = [];

		$order_items = $order->get_items();
		if ( ! empty( $order_items ) ) {
			foreach ( $order_items as $item_id => $s_item ) {
				$product = wc_get_product( $s_item['product_id'] );

				$qty = method_exists( $s_item, 'get_quantity' ) ? $s_item->get_quantity() : $s_item['qty'];

				$order_product = self::create_order_product(
					$order,
					$s_item,
					$qty,
					$product_settings
				);
				if ( ! $order_product ) {
					continue;
				}
				$products[ $item_id ] = $order_product;
				
				// add custom discount if exists
				$custom_line_discount = self::create_custom_line_discount(
					$order,
					$s_item,
					$qty,
					$product_settings
				);
				if ( ! $custom_line_discount ) {
					continue;
				}
				$products[ $item_id."-line-discount" ] = $custom_line_discount;

			}
		}
		// order discount (NON-refunds).
		$existing_products = $products;
		$merged=[];
		$order_discounts   = self::get_order_discounts( $order, $product_settings, $existing_products );
		if ( ! empty( $order_discounts ) ) {
			foreach ( $products as $item_id => $product ) {
				$merged[ $item_id ] = $product;
				if ( isset( $order_discounts[ $item_id ] ) ) {
					$merged[ $item_id . '-discount' ] = $order_discounts[ $item_id ];
				}
			}
			$products = $merged;
		}

		// order discount (coupons).
		$existing_products = $products;
		$order_coupons     = self::get_order_coupons( $order, $product_settings, $existing_products );
		if ( ! empty( $order_coupons ) ) {
			if ( count( self::smrt_get_items_tax_classes( $order ) ) > 1 && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $product_settings['product_vat'] ) {
				foreach ( $products as $item_id => $product ) {
					$merged[ $item_id ] = $product;
					if ( isset( $order_coupons[ $item_id ] ) ) {
						$merged[ $item_id . $order_coupons[ $item_id ]->code ] = $order_coupons[ $item_id ];
					}
				}
				$products = $merged;
			} else {
				$products = array_merge( $products, $order_coupons );
			}
		}

		// shipping.
		if ( $product_settings['include_shipping'] && count($order->get_items( 'shipping' )) > 0 ) {
			if(0 < $order->get_shipping_total() || ( 0 == $order->get_shipping_total() && false == $product_settings['free_shipping'] ) ){
				$products[] = self::create_order_transport( $product_settings['shipping_name'], $product_settings['shipping_name'], 1, $order, $product_settings );
			}
		}

		// fees.
		foreach ( $order->get_fees() as $fee_id => $fee ) {
			$fee_prod = self::create_order_fees( $fee, $order, $product_settings);		
			if(!is_null($fee_prod)){
				$products[] = $fee_prod;	
			}
		}

		return $products;
	}

	/**
	 * Get formated custom line discount
	 *
	 * @param  WC_Order      $order woocommerce order.
	 * @param  WC_Order_Item $order_item woocommerce order item.
	 * @param  int           $quantity order item quantity.
	 * @param  Array         $product_settings smartbill product settings.
	 *
	 *
	 * @return stdClass|null $discount smartbill discount.
	 */
	public static function create_custom_line_discount($order, $order_item, $quantity, $product_settings){
		$total_before_discount = $order_item->get_subtotal();
		$total_after_discount = $order_item->get_total();
		$total_discount_before_tax = $total_before_discount - $total_after_discount;	
		$coupon_discount_before_tax = 0;
		// when multiple vat are applied, line discount is included in the cupon discount.
		
		if ( ! empty( $order->get_data()['coupon_lines'] ) ) {
			if ( count( self::smrt_get_items_tax_classes( $order ) ) > 1 && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $product_settings['product_vat'] ) {
				return null;		
			}

			foreach ( $order->get_data()['coupon_lines'] as $coupon_line ) {
				$coupon = new WC_Coupon( $coupon_line->get_code() );
				if(!empty($coupon)){
					switch ($coupon->get_discount_type()) {
						case 'percent':
							// Percentage discount: calculate for each item
							$coupon_discount_before_tax += $coupon->get_discount_amount($total_before_discount, $order_item, $order );
							break;
						default:
							$coupon_discount_before_tax += $coupon_line->get_discount() / $order->get_item_count() * $order_item->get_quantity();
							break;
					}
				}else{
					$coupon_discount_before_tax += $coupon_line->get_discount() / $order->get_item_count() * $order_item->get_quantity();
				}		
			}
		}

		$automatic_discount_before_tax = number_format($total_discount_before_tax - $coupon_discount_before_tax,2);

		// skip discount if 0
		if(0 == $automatic_discount_before_tax || 0 == $total_discount_before_tax){
			return null;
		}
		
		try{
			$taxes = $order_item->get_taxes();
			$tax_amount = array_sum( $taxes['subtotal']) / $total_before_discount ;
			$automatic_discount_after_tax =$automatic_discount_before_tax / ($tax_amount + 1 );
		}catch(DivisionByZeroError $e){
			// catch division by zero error.
			return null;
		}
		
		// Check if woocommerce tax is enabled
		$woocommerce_taxe_settings = 'yes' == get_option( 'woocommerce_calc_taxes' );
		// If disabled use smartbill tax settings
		$woocommerce_taxes = $woocommerce_taxe_settings ? false : $product_settings['included_vat'];

		$product = new stdClass();
		$product->discountValue = -1*$automatic_discount_before_tax;

		if($product->discountValue >=-0.1){
			return null;
		}

		$product->code               = 'discount';
		$product->currency           = $product_settings['billing_currency'];
		$product->isDiscount         = true;
		$product->discountPercentage = 0;
		$product->discountType       = 1;
		$product->isTaxIncluded      = (bool) $woocommerce_taxes;
		$um                          = $product_settings['um'];

		if ( ! in_array( strtolower( $um ), array( 'no_value', 'preluata-din-smartbill' ) ) ) {
			$product->measuringUnitName = $product_settings['um'];
		} else {
			$product->measuringUnitName = '@@@@@';
		}

		$product->name='Discount ('.self::get_product_variation_name( $order_item ).')';
		$admin_settings= new Smartbill_Woocommerce_Admin_Settings_Fields();
		
		$product->numberOfItems = 1;
		if ( $product_settings['isTaxPayer'] ) {
			$saved_vat = $admin_settings->get_product_vat();
			if ( 0 > $automatic_discount_before_tax && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $saved_vat ) {
				// We can not get VAT if line item is 0+0.
				return null;
			}
			
			$tax_details            = self::get_tax_name_details_for_values( $automatic_discount_before_tax,$automatic_discount_after_tax, $product->name );
			$product->taxName       = $tax_details['taxName'];
			$product->taxPercentage = $tax_details['taxPercentage'];
		}
		$product->warehouseName = $product_settings['warehouse'];
		return $product;
	}

	/**
	 * Get formated order discounts
	 *
	 * @param WC_Order $order  woocommerce order.
	 * @param Array    $product_settings SmartBill product settings.
	 * @param Array    $existing_products formated products.
	 *
	 * @return Array   $products
	 */
	private static function get_order_discounts( $order, $product_settings, $existing_products ) {
		$items    = $order->get_items();
		$products = array();
		if ( ! empty( $items ) ) {
			$admin_settings            = new Smartbill_Woocommerce_Admin_Settings_Fields();
			$saved_config_for_discount = $admin_settings->get_show_discount_on_document();

			$existing_keys = array_keys( $existing_products );
			foreach ( $items as $item_id => $item ) {

				if ( ! $saved_config_for_discount ) {
					if ( in_array( $item_id, $existing_keys, true ) ) {
						continue;
					}
				}
				$item_product = $item->get_product();

				$product = new stdClass();
				$qty     = method_exists( $item, 'get_quantity' ) ? $item->get_quantity() : $item['qty'];

				$price         = $order->get_item_subtotal( $item, true, true );
				$regular_price = self::smrt_float(wc_get_order_item_meta( $item->get_id(), '_smartbill_prod_reg_price' ));			
				if(empty($regular_price)){
					$regular_price=$item_product->get_regular_price();
				}
				
				$regular_price_vat = self::smrt_float(wc_get_order_item_meta( $item->get_id(), '_smartbill_prod_reg_price_excluding_tax' ));
				if(empty($regular_price_vat)){
					$regular_price_vat= wc_get_price_excluding_tax( $item->get_product() , array('price' => $item->get_product()->get_regular_price() ));
				}

				// skip lines with no discount.
				$line_item  = $item->get_subtotal();
				if (round(($line_item)/$qty,2) >= $regular_price_vat) {
					continue;
				}

				$line_total = $item->get_subtotal();
				$product->discountValue =  ( -1 ) * floatval( abs( $regular_price_vat*$qty - $line_item ) );

				// skip discount if 0
				if(0 == $product->discountValue){
					continue;
				}
				
				// Check if woocommerce tax is enabled
				$woocommerce_taxe_settings = 'yes' == get_option( 'woocommerce_calc_taxes' );
				// If disabled use smartbill tax settings
				$woocommerce_taxes = $woocommerce_taxe_settings ? false : $product_settings['included_vat'];


				$product->code               = 'discount';
				$product->currency           = $product_settings['billing_currency'];
				$product->isDiscount         = true;
				$product->discountPercentage = 0;
				$product->discountType       = 1;
				$product->isTaxIncluded      = (bool) $woocommerce_taxes;
				$um                          = $product_settings['um'];

				if ( ! in_array( strtolower( $um ), array( 'no_value', 'preluata-din-smartbill' ) ) ) {
					$product->measuringUnitName = $product_settings['um'];
				} else {
					$product->measuringUnitName = '@@@@@';
				}

				$product_name = $item->get_name();
				if ( (bool) $admin_settings->get_smartbill_product() ) {
					$product_name = '';
				}

				$product->name = str_replace( '#nume_produs#', $product_name, $product_settings['discount_text'] );
				$product->name = str_replace( '#cantitate_produse#', $qty, $product->name );
				// Backwards compatibility for typo
				$product->name = str_replace( '#cantiate_produse#', $qty, $product->name );

				$product->price         = 0;
				$product->numberOfItems = 1;
				$product->saveToDb      = false;
				if ( $product_settings['isTaxPayer'] ) {
					$saved_vat = $admin_settings->get_product_vat();
					if ( 0 > $line_item && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $saved_vat ) {
						// We can not get VAT if line item is 0+0.
						return null;
					}
					$tax_details            = self::get_tax_name_details_for_values( $line_total, $line_item, $product->name );
					$product->taxName       = $tax_details['taxName'];
					$product->taxPercentage = $tax_details['taxPercentage'];
				}

				$product->warehouseName = $product_settings['warehouse'];
				$products[ $item_id ]   = $product;
			}
		}

		return $products;
	}

	/**
	 * Get total value without VAT and group duplicated VATs, in order to calculate coupon discount corectly
	 *
	 * @param Array    $coupon_items woocommerce coupons.
	 * @param WC_Order $order  woocommerce order.
	 * @param Array    $existing_products formated products.
	 *
	 * @return false|Array   $arr
	 */
	private static function extract_vat_from_coupon( $coupon_items, $order, $existing_products ) {
		if ( empty( $coupon_items ) || empty( $order ) || empty( $existing_products ) ) {
			return false;
		}
		$arr = null;
		foreach ( $order->get_items() as $item_id => $order_item ) {
			if ( $order_item->get_subtotal() !== $order_item->get_total() ) {
				$discount_value = $order_item->get_subtotal() - $order_item->get_total();
				if ( ! isset( $arr[ $existing_products[ $item_id ]->taxPercentage ] ) ) {
					$arr[ $existing_products[ $item_id ]->taxPercentage ] = $discount_value;
				} else {
					$arr[ $existing_products[ $item_id ]->taxPercentage ] += $discount_value;
				}
			}
		}
		$coupon_sum = 0;

		foreach ( $coupon_items as $coupon_item ) {
			$line_coupon_item = $coupon_item->get_discount();
			$coupon_sum      += $line_coupon_item;
		}
		if ( array_sum( $arr ) == $coupon_sum ) {
			return $arr;
		}
		return false;

	}


	/**
	 * Get formated order coupons
	 *
	 * @param WC_Order $order  woocommerce order.
	 * @param Array    $product_settings SmartBill product settings.
	 * @param Array    $existing_products formated products.
	 *
	 * @return Array   $products
	 */
	private static function get_order_coupons( $order, $product_settings, $existing_products ) {
		$data              = $order->get_data();
		$coupons           = $data['coupon_lines'];

		// Check if woocommerce tax is enabled
		$woocommerce_taxe_settings = 'yes' == get_option( 'woocommerce_calc_taxes' );
		// If disabled use smartbill tax settings
		$woocommerce_taxes = $woocommerce_taxe_settings ? false : $product_settings['included_vat'];

		$products          = array();
		if ( ! empty( $coupons ) ) {
			if ( count( self::smrt_get_items_tax_classes( $order ) ) > 1 && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $product_settings['product_vat'] ) {
				foreach ( $order->get_items() as $item_id => $temp_item ) {
					$product                     = new stdClass();
					$product->code               = 'coupon';
					$product->currency           = $product_settings['billing_currency'];
					$product->isDiscount         = true;
					$product->discountPercentage = 0;
					$product->discountType       = 1;
					$product->isTaxIncluded      = $woocommerce_taxes;
					$product->measuringUnitName  = '@@@@@';

					if ( ! in_array( strtolower( $product_settings['um'] ), array( 'no_value', 'preluata-din-smartbill' ) ) ) {
						$product->measuringUnitName = $product_settings['um'];
					}
					$coupons_name = '';
					foreach ( $coupons as $item ) {
						$coupons_name .= $item->get_name() . ' ';
					}
					$product->name = str_replace( '#nume_cupon#', $coupons_name, $product_settings['coupon_text'] );

					$product->price         = 0;
					$product->quantity      = 0;
					$product->numberOfItems = 1;
					$product->saveToDb      = false;
					$item_subtotal          = $temp_item->get_subtotal();
					
					if(0 == $item_subtotal){
						continue;
					}

					$item_total             = $temp_item->get_total();
					$new_discount           = $item_subtotal - $item_total;
					$tax                    = number_format( (float) $temp_item->get_subtotal_tax(), 2 );
					$tabper                 = number_format( (float) $tax / $item_subtotal, 2 ) * 100;
					$product->discountValue = -1 * $new_discount;
					
					if(0 == $product->discountValue){
						continue;
					}
					
					if ( $product_settings['isTaxPayer'] ) {
						$vat_rates              = Smartbill_Woocommerce_Settings::get_vat_rates();
						$tax_name               = self::get_tax_name_by_percentage( $vat_rates, $tabper, $temp_item );
						$product->taxName       = $tax_name;
						$product->taxPercentage = $tabper;
					}
					$product->warehouseName = $product_settings['warehouse'];
					$products[ $item_id ]   = $product;
				
				}
			} else {
				foreach ( $coupons as $item ) {
					$product                     = new stdClass();
					$product->code               = 'coupon';
					$product->currency           = $product_settings['billing_currency'];
					$product->isDiscount         = true;
					$product->discountPercentage = 0;
					$product->discountValue      = -1 * floatval( $item->get_discount() );
					
					if(0 == $product->discountValue){
						continue;
					}

					$product->discountType       = 1;
					$product->isTaxIncluded      = $woocommerce_taxes;
					$product->measuringUnitName  = '@@@@@';
					if ( ! in_array( strtolower( $product_settings['um'] ), array( 'no_value', 'preluata-din-smartbill' ) ) ) {
						$product->measuringUnitName = $product_settings['um'];
					}

					$product->name          = str_replace( '#nume_cupon#', $item->get_name(), $product_settings['coupon_text'] );
					$product->price         = 0;
					$product->quantity      = 0;
					$product->numberOfItems = count( $existing_products ) + count( $products );
					$product->saveToDb      = false;

					$line_item = $item->get_discount();
					$line_ammount = $item->get_discount() + $item->get_discount_tax();

					if ( $product_settings['isTaxPayer'] ) {
						if ( 0 == $line_item && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $product_settings['product_vat'] ) {
							continue;
						}
						$tax_details            = self::get_tax_name_details_for_values( $line_ammount, $line_item, $item );
						$product->taxName       = $tax_details['taxName'];
						$product->taxPercentage = $tax_details['taxPercentage'];
						
					}
					$product->warehouseName = $product_settings['warehouse'];
					$products[]             = $product;
				}
			}
		}

		return $products;
	}

	/**
	 *
	 * Replacement for method $order->get_items_tax_classes();
	 * This functions adds an entry for products that have no tax.
	 * Is used for adding cupons when there are multiple types of taxes per order item (this includes no having tax)
	 *
	 * @param  WC_Order $order woocommerce order item.
	 *
	 * @return array // tax classes array
	 */
	public static function smrt_get_items_tax_classes( $order ) {
		$found_tax_classes = array();

		foreach ( $order->get_items() as $item ) {
			if ( is_callable( array( $item, 'get_tax_status' ) ) ) {
				if ( in_array( $item->get_tax_status(), array( 'taxable', 'shipping' ) ) ) {
					$found_tax_classes[] = $item->get_tax_class();
				} else {
					$found_tax_classes[] = 'missing';
				}
			}
		}

		return array_unique( $found_tax_classes );
	}

	/**
	 * Get product with variation name using regex
	 *
	 * @param  WC_Order_Item $order_item woocommerce order item.
	 *
	 * @return string $product->name
	 */
	private static function get_product_variation_name( $order_item ) {
		$product = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );
		if ( $product->is_type( 'variation' ) ) {
			// Remove special characters so that the product name looks like smartbill product name.
			$variables = preg_replace( '/[^:^,]*[\:]/', '', $product->get_attribute_summary() );
			return $product->get_title() . ' -' . $variables;
		}
		return $product->get_name();
	}

	/**
	 * Get order item sku
	 *
	 * @param  WC_Order_Item $order_item woocommerce order item.
	 *
	 * @return string $product_sku
	 */
	private static function get_product_variation_sku( $order_item ) {
		if ( ! empty( $order_item['variation_id'] ) ) {
			$product = wc_get_product( $order_item['variation_id'] );
		} else {
			$product = wc_get_product( $order_item['product_id'] );
		}

		if(empty($product)){
			throw new \Exception( sprintf(__( 'Produsul "%s" nu exista in nomenclatorul Woocommerce.', 'smartbill-woocommerce' ), $order_item['name'] ));
		}

		$product_sku = $product->get_sku();
		if ( empty( $product_sku ) ) {
			$product_sku = sanitize_title( $product->get_title() );
		}

		return $product_sku;
	}

	/**
	 * Get formated order product
	 *
	 * @param  WC_Order      $order woocommerce order.
	 * @param  WC_Order_Item $order_item woocommerce order item.
	 * @param  int           $quantity order item quantity.
	 * @param  Array         $product_settings smartbill product settings.
	 *
	 * @throws \Exception Invalid/missing data.
	 *
	 * @return stdClass $product smartbill product
	 */
	private static function create_order_product( $order, $order_item, $quantity, $product_settings ) {
		$admin_settings            = new Smartbill_Woocommerce_Admin_Settings_Fields();
		$saved_config_for_discount = $admin_settings->get_show_discount_on_document();
		$line_item                 = $order->get_line_subtotal( $order_item, (bool) $admin_settings->get_included_vat(), false );
		$base_item_price           = round( $line_item / $quantity, 2 );
		$line_tax                  = $order_item['line_subtotal_tax'];

		$product                = new stdClass();
		$product->code          = self::get_product_variation_sku( $order_item );
		$product->name          = self::get_product_variation_name( $order_item );
		$product->currency      = $product_settings['billing_currency'];
		$product->isDiscount    = false;
		$product->isTaxIncluded = (bool) $product_settings['included_vat'];
		$um                     = $product_settings['um'];

		if ( 'no_value' == strtolower( $um ) ) {
			throw new \Exception( __( '<br>Este necesara selectarea unei unitati de masura.', 'smartbill-woocommerce' ) );
		}

		if ( ! in_array( strtolower( $um ), array( 'no_value', 'preluata-din-smartbill' ) ) ) {
			$product->measuringUnitName = $product_settings['um'];
		} else {
			$product->measuringUnitName = '@@@@@';
		}

		if ( ! property_exists( $product, 'measuringUnitName' ) ) {
			if ( empty( $product_settings['warehouse'] ) ) {
				$product_settings['warehouse'] = 'Fara gestiune';
			}
			if ( empty( $product->code ) ) {
				throw new \Exception( "Produsul {$product->name} ({$product->code}) nu exista in SmartBill" );
			} else {
				throw new \Exception( "Produsul {$product->name} nu exista in SmartBill" );
			}
		}

		$product->name          = self::get_product_variation_name( $order_item );
		$product->quantity      = $quantity;
		$product->saveToDb      = (bool) $product_settings['saveProductToDb'];
		$product->warehouseName = $product_settings['warehouse'];
		if ( $saved_config_for_discount ) {
			$product_item    = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );
			// get saved price
			$base_item_price_temp = self::smrt_float(wc_get_order_item_meta( $order_item->get_id(), '_smartbill_prod_reg_price' ));
			
			$line_total_exl_vat=self::smrt_float(wc_get_order_item_meta( $order_item->get_id(), '_smartbill_prod_reg_price_including_tax' ));
			if(empty($line_total_exl_vat)){
				$line_total_exl_vat = wc_get_price_excluding_tax( $order_item->get_product() , array('price' => $order_item->get_product()->get_regular_price() ));
			}
			if("1" == $product->isTaxIncluded){
				$line_total = $line_total_exl_vat;
			}else{
				$line_total = self::smrt_float(wc_get_order_item_meta( $order_item->get_id(), '_smartbill_prod_reg_price_excluding_tax' ));
				if(empty($line_total)){
					$line_total = wc_get_price_including_tax( $order_item->get_product() , array('price' => $order_item->get_product()->get_regular_price() ));
				}
			}
			
			$base_item_price_temp2 = round( $order->get_line_subtotal( $order_item, (bool) $admin_settings->get_included_vat(), true ) / $quantity, 2 );

			if($base_item_price_temp2 < round($line_total_exl_vat,2)){
				$base_item_price = $line_total;
				
				// backwards compatibility
				if(empty($base_item_price)){
					$base_item_price = $product_item->get_regular_price();
				}

				$line_item       = $order->get_item_subtotal( $order_item, (bool) $admin_settings->get_included_vat(), true );
				
				if (empty($base_item_price) || $line_item > $base_item_price ) {
					$base_item_price = $line_item;
				}		

				$line_tax   = $line_total - $line_item;
			}
			
		}

		if ( $product_settings['isTaxPayer'] ) {
			$saved_vat = $admin_settings->get_product_vat();

			if ( 0 > $line_item && Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $saved_vat ) {
				// We can not get VAT if line item is 0+0.
				return null;
			}
			if ( Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $saved_vat ) {
				$line_item = $order_item['line_subtotal'];
				$line_tax  = $order_item['line_subtotal_tax'];
			}
			$order_tax = null;
			foreach($order->get_items('tax') as $order_rate_id => $order_rate){
				if($order_rate->get_rate_id() == key($order_item->get_taxes()['subtotal'])){
					$order_tax = $order_rate;
				}
			}

			$tax_details = self::get_tax_name_details_for_values( $line_item + $line_tax, $line_item, $order_item, $order_tax );
			$product->taxName       = $tax_details['taxName'];
			$product->taxPercentage = $tax_details['taxPercentage'];

		}
		// convert string (romanian number) to float.
		$base_item_price = self::smrt_float( $base_item_price );
		$product->price  = $base_item_price;
		if ( 'yes' == wc_get_order_item_meta( $order_item->get_id(), '_smartbill_service' ) ) {
			$product->isService = true;
		}

		return $product;
	}

	/**
	 * Get formated shipping product
	 *
	 * @param  string   $code smartbill shipping code.
	 * @param  string   $name shipping name.
	 * @param  int      $quantity order item quantity.
	 * @param  WC_Order $order woocommerce order.
	 * @param  Array    $product_settings smartbill product settings.
	 *
	 * @return stdClass $product
	 */
	private static function create_order_transport( $code, $name, $quantity, $order, $product_settings ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		$saved_vat      = $admin_settings->get_shipping_vat();
		$options        = get_option( 'smartbill_plugin_options_settings' );
		$vat_rates      = Smartbill_Woocommerce_Settings::get_vat_rates();
		$product                    = new stdClass();
		
		$product->currency          = $product_settings['billing_currency'];
		$product->isDiscount        = false;
		$product->isTaxIncluded     = (bool) $product_settings['shipping_included_vat'];
		$product->measuringUnitName = 'buc';
		
		$name = str_replace("#nume_transport#", $order->get_shipping_method(),$name);
		$code = str_replace("#nume_transport#", $order->get_shipping_method(),$code);

		$product->code              = $code;
		$product->name              = $name;
		$product->price             = floatval( $order->get_total_shipping() );
		$product->quantity          = $quantity;
		$product->saveToDb          = false;

		// if company pays VAT.
		if ( is_array( $vat_rates ) ) {
			if("WooCommerce" == $saved_vat){
				if( 0 != $order->get_shipping_tax() ){
					$temp_tax  = round((floatval($order->get_shipping_tax())/floatval($order->get_total_shipping())*100),1);
				}else{
					$temp_tax = 0;
				}

                $temp_name = get_option( 'woocommerce_shipping_tax_class' );
                $temp_name = str_replace("-"," ", $temp_name );
                foreach($vat_rates as $vat_rate){
					$vat_rate['name'] = strtolower( $vat_rate['name'] );
					if($temp_name == $vat_rate['name'] && $temp_tax == $vat_rate['percentage']){
						$product->taxPercentage = $vat_rate['percentage'];
                        $product->taxName = $vat_rate['name'];
                        break;
					}
                    if($temp_name != $vat_rate['name'] && $temp_tax == $vat_rate['percentage']){
                    	$product->taxPercentage = $vat_rate['percentage'];
                        $product->taxName = $vat_rate['name'];
                    }
                }
			}else{
				$product->taxName       = $vat_rates[ $saved_vat ]['name'];
				$product->taxPercentage = $vat_rates[ $saved_vat ]['percentage'];
			}
		}
		$product->isService = true;

		return $product;
	}

	/**
	 * Create code to use in smartbill document for custom fee
	 *
	 * @param  string $string custom fee name.
	 * @return string
	 */
	private static function get_fee_code( $string ) {
		// Lower case everything.
		$string = strtolower( $string );
		// Make alphanumeric (removes all other characters).
		$string = preg_replace( '/[^a-z0-9_\s-]/', '', $string );
		// Clean up multiple dashes or whitespaces.
		$string = preg_replace( '/[\s-]+/', ' ', $string );
		// Convert whitespaces and underscore to dash.
		$string = preg_replace( '/[\s_]/', '-', $string );
		return $string;
	}

	/**
	 * Create formated order fee for smartbill document
	 *
	 * @param WC_Order_Custom_Fee $fee woocommerce custom fee.
	 * @param WC_Order            $order woocomemrce order.
	 * @param Array               $product_settings smartbill product settings.
	 *
	 * @return stdClass $product
	 */
	private static function create_order_fees( $fee, $order, $product_settings ) {
		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		$saved_vat      = $admin_settings->get_shipping_vat();
		$options        = get_option( 'smartbill_plugin_options_settings' );
		$vat_rates      = Smartbill_Woocommerce_Settings::get_vat_rates();
		$name           = $fee->get_name();
		$code           = self::get_fee_code( $name );

		$product                    = new stdClass();
		$product->code              = $code;
		$product->currency          = $product_settings['billing_currency'];
		$product->isDiscount        = false;
		$product->isTaxIncluded     = (bool) $product_settings['shipping_included_vat'];
		$product->measuringUnitName = 'buc';
		$product->name              = $name;
		$product->price             = floatval( $fee->get_total() );
		$product->quantity          = 1;
		$product->saveToDb          = false;

		// If company pays VAT.

		if ( is_array( $vat_rates ) ) {
			if( Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM == $product_settings['product_vat']){
				if(count( self::smrt_get_items_tax_classes( $order ) ) > 1 ){
					// bug: VAT is incorect in this case
					return null;
				}
				$tax_details = self::get_tax_name_details_for_values($product->price + $fee->get_total_tax(), $product->price,2);

				$product->taxName         = $tax_details['taxName'];
				$product->taxPercentage   = $tax_details['taxPercentage'];
			}else{
				$product->taxName       = $vat_rates[ $saved_vat ]['name'];
				$product->taxPercentage = $vat_rates[ $saved_vat ]['percentage'];
			}
		}
		$product->isService = true;

		return $product;
	}


	/**
	 * Format woocommerce price for float value
	 *
	 * @param  string $float price string.
	 *
	 * @return float
	 */
	private static function smrt_float( $float ) {
		if ( self::smrt_is_localized( $float ) ) {
			$find  = get_option( 'woocommerce_price_thousand_sep' );
			$float = str_replace( $find, '', $float );
			$find  = get_option( 'woocommerce_price_decimal_sep' );
			$float = str_replace( $find, '.', $float );
		}

		return floatval( $float );
	}

	/**
	 * Get decimal separator position if exist.
	 *
	 * @param  string $number price string.
	 *
	 * @return int|false
	 */
	private static function smrt_is_localized( $number ) {
		$find = get_option( 'woocommerce_price_decimal_sep' );

		return false !== strpos( $number, $find );
	}

	/**
	 * Export smartbill settings to array
	 *
	 * @return array with db settings
	 */
	public static function export_settings() {
		$class          = 'Smartbill_Woocommerce_Admin_Settings_Fields';
		$settings_class = new $class();
		$methods        = get_class_methods( $class );
		$settings       = array();
		$i              = 0;
		foreach ( $methods as $method ) {
			if ( 'get_' == substr( $method, 0, 4 ) ) {
				$setting_name              = substr( $method, 4 );
				$setting_value             = $settings_class->$method();
				$settings[ $setting_name ] = $setting_value;
			}
		}

		return $settings;
	}

	/**
	 * Export order data to array
	 *
	 * @param int        $id the WC order ID.
	 * @param Array|null $fields  woocommerce_api_order_response param.
	 * @param Array      $filter  woocommerce_api_order_response param.
	 *
	 * @return Array with order info.
	 */
	public static function export_order( $id, $fields = null, $filter = array() ) {
		if ( ! is_numeric( $id ) ) {
			return false;
		}
		// Get the decimal precession.
		$dp    = ( isset( $filter['dp'] ) ) ? intval( $filter['dp'] ) : 2;
		$order = wc_get_order( $id ); // getting order Object.
		if ( ! $order ) {
			return false;
		}
		$order_data = array(
			'id'                        => $order->get_id(),
			'number'                    => $order->get_order_number(),
			'created_at'                => $order->get_date_created()->date( 'Y-m-d H:i:s' ),
			'updated_at'                => $order->get_date_modified()->date( 'Y-m-d H:i:s' ),
			'completed_at'              => ! empty( $order->get_date_completed() ) ? $order->get_date_completed()->date( 'Y-m-d H:i:s' ) : '',
			'status'                    => $order->get_status(),
			'currency'                  => $order->get_currency(),
			'total'                     => round( $order->get_total(), $dp ),
			'subtotal'                  => round( $order->get_subtotal(), $dp ),
			'total_line_items_quantity' => $order->get_item_count(),
			'total_tax'                 => round( $order->get_total_tax(), $dp ),
			'total_shipping'            => round( $order->get_total_shipping(), $dp ),
			'cart_tax'                  => round( $order->get_cart_tax(), $dp ),
			'shipping_tax'              => round( $order->get_shipping_tax(), $dp ),
			'total_discount'            => round( $order->get_total_discount(), $dp ),
			'shipping_methods'          => $order->get_shipping_method(),
			'key'                       => $order->get_order_key(),
			'payment_method_id'         => $order->get_payment_method(),
			'payment_method_title'      => $order->get_payment_method_title(),
			'payment_paid_at'           => ! empty( $order->get_date_paid() ) ? $order->get_date_paid()->date( 'Y-m-d H:i:s' ) : '',
			'billing_first_name'        => $order->get_billing_first_name(),
			'billing_last_name'         => $order->get_billing_last_name(),
			'billing_company'           => $order->get_billing_company(),
			'billing_address_1'         => $order->get_billing_address_1(),
			'billing_address_2'         => $order->get_billing_address_2(),
			'billing_city'              => $order->get_billing_city(),
			'billing_state'             => $order->get_billing_state(),
			'billing_formated_state'    => @WC()->countries->states[ $order->get_billing_country() ][ $order->get_billing_state() ], // human readable formated state name.
			'billing_postcode'          => $order->get_billing_postcode(),
			'billing_country'           => $order->get_billing_country(),
			'billing_formated_country'  => WC()->countries->countries[ $order->get_billing_country() ], // human readable formated country name.
			'billing_email'             => $order->get_billing_email(),
			'billing_phone'             => $order->get_billing_phone(),
			'shipping_first_name'       => $order->get_shipping_first_name(),
			'shipping_last_name'        => $order->get_shipping_last_name(),
			'shipping_company'          => $order->get_shipping_company(),
			'shipping_address_1'        => $order->get_shipping_address_1(),
			'shipping_address_2'        => $order->get_shipping_address_2(),
			'shipping_city'             => $order->get_shipping_city(),
			'shipping_state'            => $order->get_shipping_state(),
			'shipping_formated_state'   => @WC()->countries->states[ $order->get_shipping_country() ][ $order->get_shipping_state() ], // human readable formated state name.
			'shipping_postcode'         => $order->get_shipping_postcode(),
			'shipping_country'          => $order->get_shipping_country(),
			'shipping_formated_country' => @WC()->countries->countries[ $order->get_shipping_country() ], // human readable formated country name.
			'note'                      => $order->get_customer_note(),
			'customer_ip'               => $order->get_customer_ip_address(),
			'customer_user_agent'       => $order->get_customer_user_agent(),
			'customer_id'               => $order->get_user_id(),
			'view_order_url'            => $order->get_view_order_url(),
		);

		// Getting all line items.
		$i = 0;
		foreach ( $order->get_items() as $item_id => $item ) {
			$product     = $item->get_product();
			$product_id  = null;
			$product_sku = null;
			// Check if the product exists.
			if ( is_object( $product ) ) {
				$product_id  = $product->get_id();
				$product_sku = $product->get_sku();
			}
			$order_data[ 'line_item_' . $i . '_id' ]                    = $item_id;
			$order_data[ 'line_item_' . $i . '_subtotal' ]              = round( $order->get_line_subtotal( $item, false, false ), $dp );
			$order_data[ 'line_item_' . $i . '_subtotal_tax' ]          = round( $item['line_subtotal_tax'], $dp );
			$order_data[ 'line_item_' . $i . '_total' ]                 = round( $order->get_line_total( $item, false, false ), $dp );
			$order_data[ 'line_item_' . $i . '_total_tax' ]             = round( $item['line_tax'], $dp );
			$order_data[ 'line_item_' . $i . '_price' ]                 = round( $order->get_item_total( $item, false, false ), $dp );
			$order_data[ 'line_item_' . $i . '_quantity' ]              = $item['qty'];
			$order_data[ 'line_item_' . $i . '_tax_class' ]             = ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null;
			$order_data[ 'line_item_' . $i . '_name' ]                  = $item['name'];
			$order_data[ 'line_item_' . $i . '_product_id' ]            = ( ! empty( $item->get_variation_id() ) && ( 'product_variation' == $product->post_type ) ) ? $product->get_parent_id() : $product_id;
			$order_data[ 'line_item_' . $i . '_variation_id' ]          = ( ! empty( $item->get_variation_id() ) && ( 'product_variation' == $product->post_type ) ) ? $product_id : 0;
			$order_data[ 'line_item_' . $i . '_product_url' ]           = get_permalink( $product_id );
			$order_data[ 'line_item_' . $i . '_product_thumbnail_url' ] = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'thumbnail', true )[0];
			$order_data[ 'line_item_' . $i . '_sku' ]                   = $product_sku;
			$order_data[ 'line_item_' . $i . '_meta' ]                  = wp_strip_all_tags( wc_display_item_meta( $item, array( 'echo' => false ) ) );
			$i++;
		}

		// getting shipping.
		$i = 0;
		foreach ( $order->get_shipping_methods() as $shipping_item_id => $shipping_item ) {
			$order_data[ 'shipping_lines_' . $i . '_id' ]           = $shipping_item_id;
			$order_data[ 'shipping_lines_' . $i . '_method_id' ]    = $shipping_item['method_id'];
			$order_data[ 'shipping_lines_' . $i . '_method_title' ] = $shipping_item['name'];
			$order_data[ 'shipping_lines_' . $i . '_total' ]        = round( $shipping_item['cost'], $dp );
			$i++;
		}

		// getting taxes.
		$i = 0;
		foreach ( $order->get_tax_totals() as $tax_code => $tax ) {
			$order_data[ 'tax_lines_' . $i . '_id' ]       = $tax->id;
			$order_data[ 'tax_lines_' . $i . '_rate_id' ]  = $tax->rate_id;
			$order_data[ 'tax_lines_' . $i . '_code' ]     = $tax_code;
			$order_data[ 'tax_lines_' . $i . '_title' ]    = $tax->label;
			$order_data[ 'tax_lines_' . $i . '_total' ]    = round( $tax->amount, $dp );
			$order_data[ 'tax_lines_' . $i . '_compound' ] = (bool) $tax->is_compound;
			$i++;
		}

		// getting fees.
		$i = 0;
		foreach ( $order->get_fees() as $fee_item_id => $fee_item ) {
			$order_data[ 'fee_lines_' . $i . '_id' ]        = $fee_item_id;
			$order_data[ 'fee_lines_' . $i . '_title' ]     = $fee_item['name'];
			$order_data[ 'fee_lines_' . $i . '_tax_class' ] = ( ! empty( $fee_item['tax_class'] ) ) ? $fee_item['tax_class'] : null;
			$order_data[ 'fee_lines_' . $i . '_total' ]     = round( $order->get_line_total( $fee_item ), $dp );
			$order_data[ 'fee_lines_' . $i . '_total_tax' ] = round( $order->get_line_tax( $fee_item ), $dp );
			$i++;
		}

		// getting coupons.
		$i = 0;
		foreach ( $order->get_items( 'coupon' ) as $coupon_item_id => $coupon_item ) {
			$order_data[ 'coupon_lines_' . $i . '_id' ]     = $coupon_item_id;
			$order_data[ 'coupon_lines_' . $i . '_code' ]   = $coupon_item['name'];
			$order_data[ 'coupon_lines_' . $i . '_amount' ] = round( $coupon_item['discount_amount'], $dp );
			$i++;
		}
		return apply_filters( 'woocommerce_api_order_response', $order_data, $order, $fields );
	}

	/**
	 * Calculate VAT percentage for 2 values.
	 *
	 * @param float $price_with_vat price with vat.
	 * @param float $price_without_vat price without vat.
	 *
	 * @return int
	 */
	public static function calculate_tax_percentage( $price_with_vat, $price_without_vat ) {	
		if(0 === $price_with_vat && "0" === $price_without_vat ){
			return -0;
		}
		if ( ! $price_with_vat || ! $price_without_vat ) {
			return -1;
		}

		$tax_percentage = round( ( ( ( $price_with_vat / $price_without_vat ) - 1 ) * 100 ),1);
		return $tax_percentage;
	}

	/**
	 * In cazul in care se preia TVA-ul din WooCommerce, aceasta functie va depista care sunt datele necesare pentru TVA
	 * si va returna numele taxei din SmartBill Cloud
	 *
	 * @param Array  $vat_rates VATS.
	 * @param int    $tax_percentage tax percentage.
	 * @param string $product  product name used for custom error message.
	 * @param Order_Tax_Item $order_tax used for mapping woocommerce tax to smartbill tax.
	 * 
	 * @throws \Exception Missing VAT error.
	 *
	 * @return string
	 */
	public static function get_tax_name_by_percentage( $vat_rates, $tax_percentage = 19, $product = null, $order_tax = null ) {
		if ( ! is_array( $vat_rates ) ) {
			throw new \Exception( __( 'Eroare la conectarea la SmartBill Cloud pentru afisarea valorilor TVA sau firma este neplatitoare de TVA.', 'smartbill-woocommerce' ) );
		}
		$tax_name       = null;
		$final_tax_name = null;
		if(!is_null($order_tax)){
			$tax_name = $order_tax->get_label();
		}

		$tax_percentage = $tax_percentage;

		foreach ( $vat_rates as $tax ) {
			$tax['percentage'] = $tax['percentage'];
			if ( (float)$tax_percentage == (float)$tax['percentage'] ) {
				if(is_null($final_tax_name)){
					$final_tax_name = $tax['name'];
				}
				if(!is_null($tax_name) && strtolower($tax_name) == strtolower($tax['name'])){
					$final_tax_name = $tax['name'];
					break;
				}
			}
		}
		if ( $final_tax_name ) {
			return $final_tax_name;
		}

		// If not found get smartbill taxes and try again
		update_option( 'smartbill_s_taxes', "" );
		$vat_rates = Smartbill_Woocommerce_Settings::get_vat_rates();

		if ( ! is_array( $vat_rates ) ) {
			throw new \Exception( __( 'Eroare la conectarea la SmartBill Cloud pentru afisarea valorilor TVA sau firma este neplatitoare de TVA.', 'smartbill-woocommerce' ) );
		}

		foreach ( $vat_rates as $tax ) {
			$tax['percentage'] = $tax['percentage'];
			if ( (float)$tax_percentage == (float)$tax['percentage'] ) {
				if(is_null($final_tax_name)){
					$final_tax_name = $tax['name'];
				}
				if(!is_null($tax_name) && strtolower($tax_name) == strtolower($tax['name'])){
					$final_tax_name = $tax['name'];
					break;
				}
			}
		}
		if ( $final_tax_name ) {
			return $final_tax_name;
		}

		$error_msg 		  = __('Cota TVA (%1%) nu exista in SmartBill. Adauga cota in SmartBill Cloud > Configurare > Cote TVA apoi factureaza din nou comanda', 'smartbill-woocommerce');
		$error_msg_parsed = str_replace( '%1', (float) $tax_percentage, $error_msg );
		throw new \Exception( $error_msg_parsed );
	}
	/**
	 * Get VAT name and value
	 * To get VAT from woocommerce value must be calculated.
	 *
	 * @param float  $price_with_vat price with vat.
	 * @param float  $price_without_vat price without vat.
	 * @param string $product  product name used for custom error message.
	 * @param Order_Tax_Item $order_tax used to map woocommerce tax to smartbill tax.
	 *
	 * @throws \Exception VAT error.
	 *
	 * @return array
	 */
	public static function get_tax_name_details_for_values( $price_with_vat, $price_without_vat, $product = null, $order_tax = null) {
		if ( ! is_numeric( $price_with_vat ) || ! is_numeric( $price_without_vat ) ) {
			throw new \Exception( __( 'Eroare la calcularea TVA-ului pentru aceste perechi de valori.', 'smartbill-woocommerce' ) );
		}

		$admin_settings = new Smartbill_Woocommerce_Admin_Settings_Fields();
		$saved_vat      = $admin_settings->get_product_vat();
		$options        = get_option( 'smartbill_plugin_options_settings' );

		$vat_rates = Smartbill_Woocommerce_Settings::get_vat_rates();

		if ( Smartbill_Woocommerce_Settings::SMARTBILL_VAT_VALUE_FOR_PLATFORM !== $saved_vat ) {
			$result['taxName']       = $vat_rates[ $saved_vat ]['name'];
			$result['taxPercentage'] = $vat_rates[ $saved_vat ]['percentage'];
		} else {
			$tax_percentage = self::calculate_tax_percentage( $price_with_vat, $price_without_vat );
			$tax_name       = self::get_tax_name_by_percentage( $vat_rates, $tax_percentage, $product, $order_tax);

			$result['taxName']       = $tax_name;
			$result['taxPercentage'] = $tax_percentage;
			
			if(0 === $price_with_vat && "0" === $price_without_vat && !is_null($order_tax) ){
				foreach ( $vat_rates as $tax ) {
					if(strtolower($order_tax->get_label()) == strtolower($tax['name'])){
						$result['taxName']       = $tax['name'];
						$result['taxPercentage'] = $tax['percentage'];
					}
				}
			}
		}
		return $result;

	}

}
// phpcs: ignore.

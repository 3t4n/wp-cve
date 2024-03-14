<?php
namespace CTXFeed\V5\Compatibility;
//use AWDP_Discount;
use CTXFeed\V5\Helper\ProductHelper;
use WAD_Discount;
use WCCS_Pricing;
use Wdr\App\Controllers\Configuration;

class DynamicDiscount {

	public function __construct() {
		// Discounted price filter
		add_filter( 'woo_feed_filter_product_sale_price', [$this,'get_dynamic_discounted_product_price'], 9, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', [$this,'get_dynamic_discounted_product_price'], 9, 5 );

		add_filter( 'woo_feed_filter_product_price', [$this,'get_dynamic_discounted_product_price'], 9, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', [$this,'get_dynamic_discounted_product_price'], 9, 5 );
	}

	/**
	 * Filter and Change Location data for tax calculation
	 *
	 * @param array $location Location array.
	 * @param string $tax_class Tax class.
	 * @param WC_Customer $customer WooCommerce Customer Object.
	 *
	 * @return array
	 */
	public function woo_feed_apply_tax_location_data( $location, $tax_class, $customer ) {
		// @TODO use filter. add tab in feed editor so user can set custom settings.
		// @TODO tab should not list all country and cities. it only list available tax settings and user can just select one.
		// @TODO then it will extract the location data from it to use here.
		$wc_tax_location = [
			WC()->countries->get_base_country(),
			WC()->countries->get_base_state(),
			WC()->countries->get_base_postcode(),
			WC()->countries->get_base_city(),
		];
		/**
		 * Filter Tax Location to apply before product loop
		 *
		 * @param array $tax_location
		 *
		 * @since 3.3.0
		 */
		$tax_location = apply_filters('woo_feed_tax_location_data', $wc_tax_location);
		if ( ! is_array($tax_location) || (is_array($tax_location) && 4 !== count($tax_location)) ) {
			$tax_location = $wc_tax_location;
		}

		return $tax_location;
	}

	/**
	 * Get price with dynamic discount
	 *
	 * @param WC_Product|WC_Product_Variable $product product object
	 * @param $price
	 * @param $config
	 * @param bool $tax product taxable or not
	 * @return mixed $price
	 */
	public function get_dynamic_discounted_product_price( $price, $product, $feedConfig, $tax , $price_type) {

		$base_price = $price;
		$discount_plugin_activate = false;
		/**
		 * PLUGIN: Discount Rules for WooCommerce
		 * URL: https://wordpress.org/plugins/woo-discount-rules/
		 */

		if ( is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) ) {
			$discount_plugin_activate = true;

			//WPML multicurrency
			$WooDiscountRulesFlycart = new WooDiscountRulesFlycart();
			$price = $WooDiscountRulesFlycart->woo_discount_rules_flycart( $price, $product, $feedConfig, $price_type );

		}

		/**
		 * PLUGIN: Dynamic Pricing With Discount Rules for WooCommerce
		 * URL: https://wordpress.org/plugins/aco-woo-dynamic-pricing/
		 *
		 * This plugin does not apply discount on product page.
		 *
		 * Don't apply discount manually.
		 */

		if (is_plugin_active('aco-woo-dynamic-pricing/start.php') || is_plugin_active('aco-woo-dynamic-pricing-pro/start.php')) {
			$discount_plugin_activate = true;
			$AcoWooDynamicPricing = new AcoWooDynamicPricing();
			$price = $AcoWooDynamicPricing->aco_dynamic_pricing( $price, $product );
		}

		/**
		 * PLUGIN: Conditional Discounts for WooCommerce
		 * URL: https://wordpress.org/plugins/woo-advanced-discounts/
		 *
		 * NOTE:* Automatically apply discount to $product->get_sale_price() method.
		 */
		if (is_plugin_active('woo-advanced-discounts/wad.php')) {

			$discount_plugin_activate = true;
			$WadDiscountPrice = new WooAdvancedDiscountWad();
			$price = $WadDiscountPrice->wad_discount_price( $price, $product );

		}

		/**
		 * PLUGIN: Pricing Deals for WooCommerce
		 * URL: https://wordpress.org/plugins/pricing-deals-for-woocommerce/
		 */
		if ( is_plugin_active( 'pricing-deals-for-woocommerce/vt-pricing-deals.php' ) ) {

			$discount_plugin_activate = true;
			$VtPricingDealsDiscount = new PricingDealsForWoocommerceVT();
			$price = $VtPricingDealsDiscount->vt_pricing_deals_discount_price( $price, $product );

		}

		/**
		 * PLUGIN: Easy woo-commerce discount plugin
		 * URL: https://wordpress.org/plugins/easy-woocommerce-discounts/
		 */
		if (is_plugin_active('easy-woocommerce-discounts/easy-woocommerce-discounts.php')) {

			if ( doing_action( 'woo_feed_update' ) || doing_action( 'woo_feed_update_single_feed' ) ) {

				//all_products, products_in_list thn $products= [];

				$discount_plugin_activate = true;
				$EasyWoocommerceDiscounts = new EasyWoocommerceDiscounts();
				$price = $EasyWoocommerceDiscounts->easy_woocommerce_discounts_price( $price, $product );

			}

//			$product_Pricing = new WCCS_Public_Product_Pricing( $product, $pricing, $apply_method = '' );
//			$price = $product_Pricing ->get_discounted_price( $discount, $discount_type );

		}

		//######################### YITH #########################################################
		/**
		 * PLUGIN: YITH WOOCOMMERCE DYNAMIC PRICING AND DISCOUNTS
		 * URL: hhttps://yithemes.com/themes/plugins/yith-woocommerce-dynamic-pricing-and-discounts/
		 *
		 * NOTE:*  YITH Automatically apply discount to $product->get_sale_price() method.
		 */
		//######################### RightPress ###################################################
		/**
		 * PLUGIN: WooCommerce Dynamic Pricing & Discounts
		 * URL: https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279
		 *
		 * RightPress dynamic pricing supported. Filter Hooks applied to "woo_feed_apply_hooks_before_product_loop"
		 * to get the dynamic discounted price via $product->ger_sale_price(); method.
		 */

		if (is_plugin_active('wc-dynamic-pricing-and-discounts/wc-dynamic-pricing-and-discounts.php')) {
			// RightPress dynamic pricing support.
			add_filter( 'rightpress_product_price_shop_change_prices_in_backend', '__return_true', 9999 );
			add_filter( 'rightpress_product_price_shop_change_prices_before_cart_is_loaded', '__return_true', 9999 );
		}

		//###################### Dynamic Pricing ##################################################
		/**
		 * PLUGIN: Dynamic Pricing
		 * URL: https://woocommerce.com/products/dynamic-pricing/
		 *
		 * Dynamic Pricing plugin doesn't show the options or any price change on your frontend.
		 * So a user will not even notice the discounts until he reaches the checkout.
		 * No need to add the compatibility.
		 */


		// Get Price with tax
		if ( $discount_plugin_activate && $tax ) {
			$price = ProductHelper::get_price_with_tax($price, $product);
		}

		$price = isset($base_price) || ($price > 0) && ($price < $base_price)  ? $price : $base_price;

		return ( isset($base_price) || ($price > 0) && ($price < $base_price) ) ? $price : $base_price;
	}

}

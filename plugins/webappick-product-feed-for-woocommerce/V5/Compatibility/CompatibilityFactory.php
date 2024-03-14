<?php
/**
 * Compatibility Factory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @category   MyCategory
 * @since      5.0.0
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Common\Helper;

/**
 * Class Compatibility Factory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class CompatibilityFactory {
	/**
	 * Initialize the compatibility classes
	 */
	public static function init() {
		$classes            = self::get_classes();
		$compatible_plugins = self::compatible_plugins();
		foreach ( $classes as $class ) {
			$class_name = __NAMESPACE__ . '\\' . $class . 'Compatibility';

			if ( ! isset( $compatible_plugins[ $class ] ) || ! is_plugin_active( $compatible_plugins[ $class ] ) || ! class_exists( $class_name ) ) {
				continue;
			}

			new $class_name;
		}

	}

	/**
	 * Get the compatibility class for the current plugin version
	 *
	 * @return array Array of compatibility classes
	 */
	public static function get_classes() {
		// Get the current working directory
		$directory = plugin_dir_path( __FILE__ );

		// Scan the directory for files
		$all_files = scandir( $directory );

		// Filter files to get only those ending with 'Compatibility.php'
		$filtered_files = array_filter(
			$all_files,
			static function ( $file ) {
				return strpos( $file, 'Compatibility.php' ) && substr( $file, - strlen( 'Compatibility.php' ) ) === 'Compatibility.php';
			}
		);

		// Extract the part of the filename before 'Compatibility'
		return array_map(
			static function ( $file ) {
				return str_replace( 'Compatibility.php', '', $file );
			},
			$filtered_files
		);
	}

	/**
	 * Get the compatible plugins list by CTX-Feed with their class name and absolute path
	 * Some plugins don't have class name, so we have to check them by their absolute path
	 *
	 * @return array
	 */
	private static function compatible_plugins() {
		/**
		 * IMPORTANT: Never change the key and value of the array below. Never remove any key or value from the array below.
		 * If you want to add any plugin, just add the plugin absolute path as key and the plugin class name as value.
		 * Create a file name with value ( class name ) as well as "Compatibility" as suffix. Other-wise it will not work.
		 *
		 * Example: 'woocommerce-multilingual/wpml-woocommerce.php' => 'woocommerce_wpml',
		 * Here 'woocommerce-multilingual/wpml-woocommerce.php' is the plugin absolute path and 'woocommerce_wpml' is the plugin class name.
		 * And the file name is 'woocommerce_wpmlCompatibility.php' with value 'Compatibility' as suffix.
		 * So, the file name is 'woocommerce_wpmlCompatibility.php' and the class name is 'woocommerce_wpmlCompatibility'.
		 */
		$AWDP_Discount = [];
		if (is_plugin_active('aco-woo-dynamic-pricing/start.php') ){
			$AWDP_Discount = [ 'aco-woo-dynamic-pricing/start.php'  => 'AWDP_Discount' ];
		}else if( is_plugin_active('aco-woo-dynamic-pricing-pro/start.php') ){
			$AWDP_Discount = [ 'aco-woo-dynamic-pricing-pro/start.php'  => 'AWDP_Discount'];
		}
		$compatible_plugins = [
			#################################################################################
			# WooCommerce Dynamic Pricing & Discounts plugins                               #
			#################################################################################
			/**
			 * This plugin has been closed as of September 12, 2023 and is not available for download. Reason: Security Issue.
			 * // TODO remove this plugin from the list
			 */
//			'pricing-deals-for-woocommerce/vt-pricing-deals.php'                        => 'PricingDealsForWoocommerceVT',
			// https://wordpress.org/plugins/pricing-deals-for-woocommerce/
//			'aco-woo-dynamic-pricing/start.php'                                         => 'AWDP_Discount',
			// DONE
			// https://wordpress.org/plugins/aco-woo-dynamic-pricing/
//			'aco-woo-dynamic-pricing-pro/start.php'                                     => 'AWDP_Discount',
			// https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279
			'woo-discount-rules/woo-discount-rules.php'                                 => 'Wdr_Configuration',
			// DONE
			// https://wordpress.org/plugins/woo-discount-rules/
			'woo-advanced-discounts/wad.php'                                            => 'WAD_Discount',
			// DONE
			// https://wordpress.org/plugins/woo-advanced-discounts/
			'easy-woocommerce-discounts/easy-woocommerce-discounts.php'                 => 'WCCS_Pricing',
			// DONE
			// https://wordpress.org/plugins/easy-woocommerce-discounts/
			'wc-dynamic-pricing-and-discounts/wc-dynamic-pricing-and-discounts.php'     => 'RP_WCDPD',
			// DONE
			// https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279


			#################################################################################
			# Composite Products for WooCommerce plugins                                    #
			#################################################################################
			'woocommerce-composite-products/woocommerce-composite-products.php'         => 'WC_Composite_Products',
			// DONE
			// https://woocommerce.com/products/composite-products/
			'wpc-composite-products/wpc-composite-products.php'                         => 'WPCleverWooco',
			// DONE
			// https://wordpress.org/plugins/wpc-composite-products/


			#################################################################################
			# WooCommerce Product Bundles plugins                                           #
			#################################################################################
			'woocommerce-product-bundles/woocommerce-product-bundles.php'               => 'WC_Product_Bundle',
			// DONE
			// https://woocommerce.com/products/product-bundles/


			#################################################################################
			# WooCommerce Currency Switcher plugins                                         #
			#################################################################################
			'woocommerce-currency-switcher/index.php'                                   => 'WOOCS',
			// DONE
			// https://wordpress.org/plugins/woocommerce-currency-switcher/
			'currency-switcher-woocommerce/currency-switcher-woocommerce.php'           => 'Alg_WC_Currency_Switcher',
			// DONE
			// https://wordpress.org/plugins/currency-switcher-woocommerce/
			'woocommerce-multicurrency/woocommerce-multicurrency.php'                   => 'WOOMC_API',
			// DONE // TODO this plugin is not tested because plugin is not found anywhere.
			// https://wordpress.org/plugins/woocommerce-multicurrency/
			'woocommerce-multi-currency/woocommerce-multi-currency.php'                 => '',
			// https://woo.com/products/multi-currency/
			'woocommerce-multilingual/wpml-woocommerce.php'                             => 'woocommerce_wpml',
			// DONE
			// https://wordpress.org/plugins/woocommerce-multilingual/
			'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php' => 'WC_Aelia_CurrencySwitcher',
			// DONE
			// https://aelia.co/shop/currency-switcher-woocommerce/
			'woo-multi-currency/woo-multi-currency.php'                                 => 'WOOMULTI_CURRENCY_F',
			// DONE
			// https://wordpress.org/plugins/woo-multi-currency/


			#################################################################################
			# WooCommerce Translation plugins                                               #
			#################################################################################
			'sitepress-multilingual-cms/sitepress.php'                                  => 'SitePress',
			// DONE
			// https://wpml.org/
			'translatepress-multilingual/index.php'                                     => 'TRP_Translate_Press',
			// DONE
			// https://wordpress.org/plugins/translatepress-multilingual/
			'polylang/polylang.php'                                                     => 'Polylang',
			// DONE
		];
		$compatible_plugins = array_merge( $compatible_plugins, $AWDP_Discount );

		$compatible_plugins_for_free = [
			#################################################################################
			# WooCommerce Dynamic Pricing & Discounts plugins                               #
			#################################################################################
			/**
			 * This plugin has been closed as of September 12, 2023 and is not available for download. Reason: Security Issue.
			 * // TODO remove this plugin from the list
			 */
//			'pricing-deals-for-woocommerce/vt-pricing-deals.php'                        => 'PricingDealsForWoocommerceVT',
			// https://wordpress.org/plugins/pricing-deals-for-woocommerce/
//			'aco-woo-dynamic-pricing/start.php'                                         => 'AWDP_Discount',
			// DONE
			// https://wordpress.org/plugins/aco-woo-dynamic-pricing/
//			'aco-woo-dynamic-pricing-pro/start.php'                                     => 'AWDP_Discount',
			// https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279
			'woo-discount-rules/woo-discount-rules.php'                                 => 'Wdr_Configuration',
			// DONE
			// https://wordpress.org/plugins/woo-discount-rules/
			'woo-advanced-discounts/wad.php'                                            => 'WAD_Discount',
			// DONE
			// https://wordpress.org/plugins/woo-advanced-discounts/
			'easy-woocommerce-discounts/easy-woocommerce-discounts.php'                 => 'WCCS_Pricing',
			// DONE
			// https://wordpress.org/plugins/easy-woocommerce-discounts/
			'wc-dynamic-pricing-and-discounts/wc-dynamic-pricing-and-discounts.php'     => 'RP_WCDPD',
			// DONE
			// https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279

		];
		$compatible_plugins_for_free = array_merge( $compatible_plugins_for_free, $AWDP_Discount );

		// If WooCommerce Multi Currency Pro version by VillaTheme is active, Free version will be removed from the list.
		if ( is_plugin_active( 'woocommerce-multi-currency/woocommerce-multi-currency.php' ) ) {
			$compatible_plugins['woocommerce-multi-currency/woocommerce-multi-currency.php'] = 'WOOMULTI_CURRENCY'; // DONE
			// https://villatheme.com/extensions/woo-multi-currency/
			unset( $compatible_plugins['woo-multi-currency/woo-multi-currency.php'] );
		}

		if( Helper::is_pro() ){
			return array_flip( $compatible_plugins );
		}else{
			return array_flip( $compatible_plugins_for_free );
		}

	}

}

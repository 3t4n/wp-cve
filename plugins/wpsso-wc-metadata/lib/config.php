<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdConfig' ) ) {

	class WpssoWcmdConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssowcmd' => array(			// Plugin acronym.
					'version'     => '4.1.1',	// Plugin version.
					'opt_version' => '22',		// Increment when changing default option values.
					'short'       => 'WPSSO WCMD',	// Short plugin name.
					'name'        => 'WPSSO Product Metadata for WooCommerce SEO',
					'desc'        => 'MPN, ISBN, GTIN, GTIN-8, UPC, EAN, GTIN-14, net dimensions, and fluid volume for WooCommerce products and variations.',
					'slug'        => 'wpsso-wc-metadata',
					'base'        => 'wpsso-wc-metadata/wpsso-wc-metadata.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-wc-metadata',
					'domain_path' => '/languages',

					/*
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '17.13.0',
						),
						'woocommerce' => array(
							'name'          => 'WooCommerce',
							'home'          => 'https://wordpress.org/plugins/woocommerce/',
							'plugin_class'  => 'WooCommerce',
							'version_const' => 'WC_VERSION',
							'min_version'   => '6.0.0',
						),
					),

					/*
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/*
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/*
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'submenu' => array(
							'wc-metadata' => 'WC Metadata',
						),
					),

					/*
					 * Declare compatibility with WooCommerce HPOS.
					 *
					 * See https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book.
					 */
					'wc_compat' => array(
						'custom_order_tables',
					),
				),
			),
		);

		/*
		 * Since WPSSO WCMD v3.0.0.
		 */
		public static function is_editable( $md_key ) {

			$md_config = self::get_md_config();

			return isset( $md_config[ $md_key ][ 'prefixes' ][ 'defaults' ][ 'wcmd_edit' ] ) ? true : false;
		}

		/*
		 * Since WPSSO WCMD v3.0.0.
		 */
		public static function is_showable( $md_key ) {

			$md_config = self::get_md_config();

			return isset( $md_config[ $md_key ][ 'prefixes' ][ 'defaults' ][ 'wcmd_show' ] ) ? true : false;
		}

		public static function get_md_config() {

			static $local_cache = null;

			if ( null !== $local_cache ) {

				return $local_cache;
			}

			if ( ! class_exists( 'WpssoUtilUnits' ) ) {	// Just in case.

				return $local_cache = array();			// Must return an array.
			}

			$dimension_unit_text  = WpssoUtilUnits::get_dimension_text();
			$dimension_unit_label = WpssoUtilUnits::get_dimension_label( $dimension_unit_text );

			$fl_vol_unit_text  = WpssoUtilUnits::get_fluid_volume_text();
			$fl_vol_unit_label = WpssoUtilUnits::get_fluid_volume_label( $fl_vol_unit_text );

			$weight_unit_text  = WpssoUtilUnits::get_weight_text();
			$weight_unit_label = WpssoUtilUnits::get_weight_label( $weight_unit_text );

			/*
			 * Metadata options will be down in the order listed here.
			 */
			$local_cache = array(
				'product_mfr_part_no' => array(
					'label'      => _x( 'Product MPN', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product Manufacturer Part Number (MPN).', 'wpsso-wc-metadata' ),
					'searchable' => true,
					'type'       => 'text',
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'MPN',
							'wcmd_edit_holder' => 'Part number',			// Capitalize the first word.
							'wcmd_show'        => 1,
							'wcmd_show_label'  => 'Manufacturer Part Number',
							'plugin_cf'        => '_wpsso_product_mfr_part_no',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_isbn' => array(
					'label'      => _x( 'Product ISBN', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to an ISBN code (aka International Standard Book Number).', 'wpsso-wc-metadata' ),
					'searchable' => true,
					'type'       => 'text',
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 0,
							'wcmd_edit_label'  => 'ISBN',
							'wcmd_edit_holder' => 'Book number',		// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'ISBN',
							'plugin_cf'        => '_wpsso_product_isbn',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_gtin14' => array(
					'label'      => _x( 'Product GTIN-14', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product GTIN-14 code (aka ITF-14).', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'searchable' => true,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 0,
							'wcmd_edit_label'  => 'GTIN-14',
							'wcmd_edit_holder' => '14-digit bar code',	// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'GTIN-14',
							'plugin_cf'        => '_wpsso_product_gtin14',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_gtin13' => array(
					'label'      => _x( 'Product GTIN-13', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product GTIN-13 code (aka 13-digit ISBN codes or EAN/UCC-13).', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'searchable' => true,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'GTIN-13 (EAN)',
							'wcmd_edit_holder' => '13-digit bar code',	// Capitalize the first word.
							'wcmd_show'        => 1,
							'wcmd_show_label'  => 'EAN',
							'plugin_cf'        => '_wpsso_product_gtin13',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_gtin12' => array(
					'label'      => _x( 'Product GTIN-12', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product GTIN-12 code (12-digit GS1 identification key composed of a UPC company prefix, item reference, and check digit).', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'searchable' => true,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'GTIN-12 (UPC)',
							'wcmd_edit_holder' => '12-digit bar code',	// Capitalize the first word.
							'wcmd_show'        => 1,
							'wcmd_show_label'  => 'UPC',
							'plugin_cf'        => '_wpsso_product_gtin12',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_gtin8' => array(
					'label'      => _x( 'Product GTIN-8', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product GTIN-8 code (aka EAN/UCC-8 or 8-digit EAN).', 'wpsso-wc-metadata' ),
					'searchable' => true,
					'type'       => 'text',
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 0,
							'wcmd_edit_label'  => 'GTIN-8',
							'wcmd_edit_holder' => '8-digit bar code',	// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'GTIN-8',
							'plugin_cf'        => '_wpsso_product_gtin8',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_gtin' => array(
					'label'      => _x( 'Product GTIN', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product GTIN code (GTIN-8, GTIN-12/UPC, GTIN-13/EAN, or GTIN-14).', 'wpsso-wc-metadata' ),
					'searchable' => true,
					'type'       => 'text',
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 0,
							'wcmd_edit_label'  => 'GTIN',
							'wcmd_edit_holder' => 'Bar code',		// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'GTIN',
							'plugin_cf'        => '_wpsso_product_gtin',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_length_value' => array(
					'label'      => _x( 'Product Net Length', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product\'s net length (in %2$s), as opposed to a shipping or packaged length used for shipping cost calculations.', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'data_type'  => 'decimal',	// Uses the WooCommerce decimal separator.
					'unit_label' => $dimension_unit_label,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'Net Length / Depth (%s)',
							'wcmd_edit_holder' => 'Net length or depth in %s',	// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'Net Length or Depth',
							'plugin_cf'        => '_wpsso_product_length_value',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_width_value' => array(
					'label'      => _x( 'Product Net Width', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product\'s net width (in %2$s), as opposed to a shipping or packaged width used for shipping cost calculations.', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'data_type'  => 'decimal',	// Uses the WooCommerce decimal separator.
					'unit_label' => $dimension_unit_label,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'Net Width (%s)',
							'wcmd_edit_holder' => 'Net width in %s',		// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'Net Width',
							'plugin_cf'        => '_wpsso_product_width_value',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_height_value' => array(
					'label'      => _x( 'Product Net Height', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product\'s net height (in %2$s), as opposed to a shipping or packaged height used for shipping cost calculations.', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'data_type'  => 'decimal',	// Uses the WooCommerce decimal separator.
					'unit_label' => $dimension_unit_label,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'Net Height (%s)',
							'wcmd_edit_holder' => 'Net height in %s',		// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'Net Height',
							'plugin_cf'        => '_wpsso_product_height_value',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_dimensions' => array(	// Not an input field.
					'label'      => _x( 'Product Net Dimensions', 'option label', 'wpsso-wc-metadata' ),
					'data_type'  => 'decimal',	// Data type used to sanitize imploded values.
					'unit_label' => $dimension_unit_label,
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_show'        => 1,
							'wcmd_show_label'  => 'Net Dimensions (L x W x H)',
						),
					),
					'implode' => array(
						'separator' => ' &times; ',
						'md_keys'   => array(
							'product_length_value',
							'product_width_value',
							'product_height_value',
						),
					),
				),
				'product_weight_value' => array(
					'label'      => _x( 'Product Net Weight', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product\'s net weight (in %2$s), as opposed to a shipping or packaged weight used for shipping cost calculations.', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'data_type'  => 'decimal',	// Uses the WooCommerce decimal separator.
					'unit_label' => $weight_unit_label,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 1,
							'wcmd_edit_label'  => 'Net Weight (%s)',
							'wcmd_edit_holder' => 'Net weight in %s',		// Capitalize the first word.
							'wcmd_show'        => 1,
							'wcmd_show_label'  => 'Net Weight',
							'plugin_cf'        => '_wpsso_product_weight_value',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
				'product_fluid_volume_value' => array(
					'label'      => _x( 'Product Fluid Volume', 'option label', 'wpsso-wc-metadata' ),
					'desc'       => __( '%1$s refers to a product\'s fluid volume (in %2$s).', 'wpsso-wc-metadata' ),
					'type'       => 'text',
					'data_type'  => 'decimal',	// Uses the WooCommerce decimal separator.
					'unit_label' => $fl_vol_unit_label,
					'actions'    => array(
						'woocommerce_product_options_sku'       => true,
						'woocommerce_variation_options_pricing' => true,
					),
					'filters' => array(
						'woocommerce_display_product_attributes' => true,
					),
					'prefixes' => array(
						'defaults' => array(
							'wcmd_edit'        => 0,
							'wcmd_edit_label'  => 'Fluid Volume (%s)',
							'wcmd_edit_holder' => 'Fluid volume in %s',		// Capitalize the first word.
							'wcmd_show'        => 0,
							'wcmd_show_label'  => 'Fluid Volume',
							'plugin_cf'        => '_wpsso_product_fluid_volume_value',
						),
						'options' => array(
							'plugin_attr' => '',
						),
					),
				),
			);

			$local_cache = apply_filters( 'wpsso_wc_metadata_config', $local_cache );

			return $local_cache;
		}

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssowcmd' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file ) {

			if ( defined( 'WPSSOWCMD_VERSION' ) ) {	// Define constants only once.

				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssowcmd' ];

			/*
			 * Define fixed constants.
			 */
			define( 'WPSSOWCMD_FILEPATH', $plugin_file );
			define( 'WPSSOWCMD_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-wc-metadata/wpsso-wc-metadata.php.
			define( 'WPSSOWCMD_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file ) ) ) );
			define( 'WPSSOWCMD_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-wc-metadata.
			define( 'WPSSOWCMD_URLPATH', trailingslashit( plugins_url( '', $plugin_file ) ) );
			define( 'WPSSOWCMD_VERSION', $info[ 'version' ] );
		}

		public static function require_libs( $plugin_file ) {

			require_once WPSSOWCMD_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOWCMD_PLUGINDIR . 'lib/register.php';
			require_once WPSSOWCMD_PLUGINDIR . 'lib/search.php';
			require_once WPSSOWCMD_PLUGINDIR . 'lib/woocommerce.php';

			add_filter( 'wpssowcmd_load_lib', array( __CLASS__, 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $success = false, $filespec = '', $classname = '' ) {

			if ( false !== $success ) {

				return $success;
			}

			if ( ! empty( $classname ) ) {

				if ( class_exists( $classname ) ) {

					return $classname;
				}
			}

			if ( ! empty( $filespec ) ) {

				$file_path = WPSSOWCMD_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {

						return SucomUtil::sanitize_classname( 'wpssowcmd' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}

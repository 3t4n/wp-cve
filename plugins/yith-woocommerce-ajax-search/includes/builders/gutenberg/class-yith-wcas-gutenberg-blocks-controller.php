<?php
/**
 * YITH_WCAS_Gutenberg_Blocks_Controller is class to initialize Gutenberg blocks
 *
 * @author  YITH
 * @package YITH/Builders/Gutenberg
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'YITH_WCAS_Gutenberg_Blocks_Controller' ) ) {
	/**
	 * Class YITH_WCAS_Gutenberg
	 */
	class YITH_WCAS_Gutenberg_Blocks_Controller {

		use YITH_WCAS_Trait_Singleton;

		/**
		 * Constructor.
		 */
		private function __construct() {
			$this->init();

		}

		/**
		 * Initialize class features.
		 */
		protected function init() {
			add_action( 'init', array( $this, 'register_assets' ) );
			add_action( 'init', array( $this, 'register_blocks' ) );
			add_action( is_admin() ? 'admin_enqueue_scripts' : 'wp_print_footer_scripts', array(
				$this,
				'enqueue_asset_data'
			), 1 );
			add_filter( 'render_block', array( $this, 'add_data_attributes' ), 10, 2 );
			add_filter( 'block_categories_all', array( $this, 'block_category' ), 100, 2 );
			add_filter( 'pre_load_script_translations', array( $this, 'script_translations' ), 10, 4 );
		}


		public function register_assets() {
			$build_url = YITH_WCAS_ASSETS_URL . '/js/blocks/build/';
			wp_register_style( 'ywcas-blocks-editor-style', $build_url . 'ywcas-blocks-editor-style.css', [ 'wp-edit-blocks' ], YITH_WCAS_VERSION, 'all' );
			$common_scripts = array(
				'ywcas-block-settings'    => 'ywcas-settings.js',
				'ywcas-blocks-data-store' => 'ywcas-blocks-data.js',
				'ywcas-blocks-vendors'    => 'ywcas-blocks-vendors.js',
				'ywcas-blocks-registry'   => 'ywcas-blocks-registry.js',
				'ywcas-blocks'            => 'ywcas-blocks.js',
			);

			foreach ( $common_scripts as $handle => $file_name ) {
				$deps = $this->get_assets_data( $file_name );

				$version      = empty( $deps['version'] ) ? YITH_WCAS_VERSION : $deps['version'];
				$dependencies = empty( $deps['dependencies'] ) ? array() : $deps['dependencies'];

				if ( 'ywcas-blocks' === $handle ) {
					$dependencies[] = 'ywcas-blocks-vendors';
				}
				wp_register_script( $handle, $build_url . $file_name, $dependencies, $version, true );

			}


		}

		public function get_assets_data( $file_name ) {
			$file_path = YITH_WCAS_BUILD_BLOCK_PATH . str_replace( '.js', '.asset.php', $file_name );

			if ( file_exists( $file_path ) ) {
				return include $file_path;
			}

			return array();
		}

		/**
		 * Register blocks, hooking up assets and render functions as needed.
		 */
		public function register_blocks() {
			$blocks = $this->get_blocks();

			foreach ( $blocks as $block ) {
				$block_class = 'YITH_WCAS_Gb_' . $block;
				if ( class_exists( $block_class ) ) {
					new $block_class();
				}
			}

		}

		/**
		 * Callback for enqueuing asset data via the WP api.
		 *
		 * Note: while this is hooked into print/admin_print_scripts, it still only
		 * happens if the script attached to `wc-settings` handle is enqueued. This
		 * is done to allow for any potentially expensive data generation to only
		 * happen for routes that need it.
		 */
		public function enqueue_asset_data() {

			if ( wp_script_is( 'ywcas-block-settings', 'registered' ) ) {

				$data                  = $this->get_common_localize();
				$data                  = rawurlencode( wp_json_encode( $data ) );
				$ywcas_settings_script = "var ywcasSettings = ywcasSettings || JSON.parse( decodeURIComponent( '" . esc_js( $data ) . "' ) );";
				wp_add_inline_script(
					'ywcas-block-settings',
					$ywcas_settings_script,
					'before'
				);
			}
		}

		/**
		 * Return the list of blocks that should be registered
		 *
		 * @return array
		 */
		public function get_blocks() {
			return array_merge(
				YITH_WCAS_Gb_Search_Block::get_search_block_types()
			);
		}

		/**
		 * Add block category
		 *
		 * @param array   $categories Array block categories array.
		 * @param WP_Post $post Current post.
		 *
		 * @return array block categories
		 */
		public function block_category( $categories, $post ) {

			$found_key = array_search( 'yith-blocks', array_column( $categories, 'slug' ), true );

			if ( ! $found_key ) {
				$categories[] = array(
					'slug'  => 'yith-blocks',
					'title' => _x( 'YITH', 'Author of the plugin/theme', 'yith-plugin-fw' ),
				);
			}

			return $categories;
		}

		/**
		 * Add data-attributes to blocks when rendered if the block is under the woocommerce/ namespace.
		 *
		 * @param string $content Block content.
		 * @param array  $block Parsed block data.
		 *
		 * @return string
		 */
		public function add_data_attributes( $content, $block ) {
			$block_name      = $block['blockName'];
			$block_namespace = strtok( $block_name ?? '', '/' );
			/**
			 * Filters the list of allowed block namespaces.
			 *
			 * This hook defines which block namespaces should have block name and attribute `data-` attributes appended on render.
			 *
			 * @param array $allowed_namespaces List of namespaces.
			 *
			 * @since 5.9.0
			 *
			 */
			$allowed_namespaces = array_merge( array( 'yith' ), (array) apply_filters( '__experimental_yith_ajax_search_blocks_add_data_attributes_to_namespace', array() ) );

			/**
			 * Filters the list of allowed Block Names
			 *
			 * This hook defines which block names should have block name and attribute data- attributes appended on render.
			 *
			 * @param array $allowed_namespaces List of namespaces.
			 *
			 * @since 5.9.0
			 *
			 */
			$allowed_blocks = (array) apply_filters( '__experimental_yith_ajax_search_blocks_blocks_add_data_attributes_to_block', array() );

			if ( ! in_array( $block_namespace, $allowed_namespaces, true ) && ! in_array( $block_name, $allowed_blocks, true ) ) {
				return $content;
			}

			$attributes              = (array) apply_filters( 'ywcas_block_data_attributes', $block['attrs'], $block );
			$exclude_attributes      = array( 'className', 'align' );
			$escaped_data_attributes = array(
				'data-block-name="' . esc_attr( $block['blockName'] ) . '"',
			);

			foreach ( $attributes as $key => $value ) {
				if ( in_array( $key, $exclude_attributes, true ) ) {
					continue;
				}
				if ( is_bool( $value ) ) {
					$value = $value ? 'true' : 'false';
				}
				if ( ! is_scalar( $value ) ) {
					$value = wp_json_encode( $value );
				}
				$escaped_data_attributes[] = 'data-' . esc_attr( strtolower( preg_replace( '/(?<!\ )[A-Z]/', '-$0', $key ) ) ) . '="' . esc_attr( $value ) . '"';
			}

			return preg_replace( '/^<div /', '<div ' . implode( ' ', $escaped_data_attributes ) . ' ', trim( $content ) );
		}

		/**
		 * Create the json translation through the PHP file.
		 * So, it's possible using normal translations (with PO files) also for JS translations
		 *
		 * @param string|null $json_translations Translations.
		 * @param string      $file The file.
		 * @param string      $handle The handle.
		 * @param string      $domain The text-domain.
		 *
		 * @return string|null
		 */
		public function script_translations( $json_translations, $file, $handle, $domain ) {

			if ( 'yith-woocommerce-ajax-search' === $domain ) {

				if ( in_array( $handle, array(
					'ywcas-search-block-block',
					'ywcas-overlay-search-block-block'
				), true ) ) {
					$path = trailingslashit( YITH_WCAS_DIR . 'languages/' ) . 'js-i18n.php';
					if ( file_exists( $path ) ) {
						$translations = include $path;

						$json_translations = wp_json_encode(
							array(
								'domain'      => 'yith-woocommerce-ajax-search',
								'locale_data' => array(
									'messages' =>
										array(
											'' => array(
												'domain'       => 'yith-woocommerce-ajax-search',
												'lang'         => get_locale(),
												'plural-forms' => 'nplurals=2; plural=(n != 1);',
											),
										)
										+
										$translations,
								),
							)
						);

					}
				}
			}

			return $json_translations;
		}

		/**
		 * Return an array of information about localization.
		 *
		 * @return mixed|null
		 */
		public function get_common_localize() {
			return
				apply_filters(
					'ywcas_block_common_localize',
					array(
						'ajaxURL'                => WC_AJAX::get_endpoint( '%%endpoint%%' ),
						'wcData'                 => $this->get_wc_data(),
						'ywcasBuildBlockURL'     => YITH_WCAS_ASSETS_URL . '/js/blocks/build/',
						'siteURL'                => get_home_url(),
						'lang'                   => ywcas_get_current_language(),
						'addToCartLabel'         => apply_filters( 'ywcas_add_to_cart_label', __( 'Add to cart', 'yith-woocommerce-ajax-search' ) ),
						'readMoreLabel'          => apply_filters( 'ywcas_read_more_label', _x( 'Read more', 'add to cart label for not purchasable products', 'yith-woocommerce-ajax-search' ) ),
						'selectOptionsLabel'     => apply_filters( 'ywcas_select_options_label', _x( 'Select options', 'add to cart label for variable products', 'yith-woocommerce-ajax-search' ) ),
						'inStockLabel'           => __( 'In stock', 'yith-woocommerce-ajax-search' ),
						'outOfStockLabel'        => __( 'Out of stock', 'yith-woocommerce-ajax-search' ),
						'skuLabel'               => __( 'SKU: ', 'yith-woocommerce-ajax-search' ),
						'showAutoComplete'       => ywcas()->settings->get_is_autocomplete(),
						'minChars'               => ywcas()->settings->get_min_chars(),
						'classicDefaultSettings' => ywcas()->settings->get_classic_default_settings(),
						'popularSearches'        => class_exists( 'YITH_WCAS_Search_History_Premium' ) ? YITH_WCAS_Search_History_Premium::get_instance()->get_popular_searches( ywcas_get_current_language() ) : array(),
						'historySearches'        => class_exists( 'YITH_WCAS_Search_History_Premium' ) ? YITH_WCAS_Search_History_Premium::get_instance()->get_history( ywcas_get_current_language() ) : array(),
						'singleResultLabel'      => _x( '%1$s result for "%2$s"', '1 result for "shoes"', 'yith-woocommerce-ajax-search' ),
						'pluralResultLabel'      => _x( '%1$s results for "%2$s"','3 results for "shoes"' ,'yith-woocommerce-ajax-search' ),
						'fuzzyResults'           => _x( 'Results for "%s"','Results for "shoes"', 'yith-woocommerce-ajax-search' ),
						'deleteAll'              => __( 'Delete all', 'yith-woocommerce-ajax-search' ),
						'inCategoryString'       => _x( 'in', 'T-shirts in Woman Clothes', 'yith-woocommerce-ajax-search' )
					)
				);
		}

		/** -------------------------------------------------------
		 * Public Static Getters - to get specific settings
		 */

		/**
		 * Get WC data
		 *
		 * @return array
		 */
		public function get_wc_data() {
			$currency_code = get_woocommerce_currency();

			return array(
				'currency'             => array(
					'code'         => $currency_code,
					'decimals'     => wc_get_price_decimals(),
					'symbol'       => html_entity_decode( get_woocommerce_currency_symbol( $currency_code ) ),
					'decimal_sep'  => esc_attr( wc_get_price_decimal_separator() ),
					'thousand_sep' => esc_attr( wc_get_price_thousand_separator() ),
					'format'       => html_entity_decode( str_replace( array( '%1$s', '%2$s' ), array(
						'%s',
						'%v'
					), get_woocommerce_price_format() ) ), // For accounting JS.
				),
				'placeholderImageSrc'  => wc_placeholder_img_src(),
				'discountRoundingMode' => defined( 'WC_DISCOUNT_ROUNDING_MODE' ) && PHP_ROUND_HALF_UP === WC_DISCOUNT_ROUNDING_MODE ? 'half-up' : 'half-down',
			);
		}
	}
}

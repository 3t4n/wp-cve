<?php /** @noinspection PhpUnusedPrivateMethodInspection, PhpUnused, PhpUnusedLocalVariableInspection, DuplicatedCode */

/**
 * Created by PhpStorm.
 * User: wahid
 * Date: 11/16/19
 * Time: 5:10 PM
 */

use RankMath\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Woo_Feed_Products_v3
 */
class Woo_Feed_Products_v3 {
	/**
	 * The Increment
	 *
	 * @var int
	 */
	protected $pi = 0;
	/**
	 * Feed file headers
	 *
	 * @var string|array
	 */
	public $feedHeader;
	/**
	 * Feed File Body
	 *
	 * @var string|array
	 */
	public $feedBody;
	/**
	 * Feed file footer
	 *
	 * @var string|array
	 */
	public $feedFooter;
	/**
	 * CSV|TXT column (text|word) enclosure
	 *
	 * @var string
	 */
	protected $enclosure;
	/**
	 * CSV|TXT column delimiter
	 *
	 * @var string
	 */
	protected $delimiter;
	/**
	 * Feed Rules
	 *
	 * @var array
	 */
	protected $config;
	/**
	 * Post status to query
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	/**
	 * Processed Products
	 *
	 * @var array
	 */
	public $products = [];


	/**
	 * Products IDs
	 *
	 * @var array
	 */
	public $product_ids = [];

	/**
	 * Feed Big Data
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * Processed Products
	 *
	 * @var array
	 */
	public $product_types = [
		'simple',
		'variable',
		'variation',
		'grouped',
		'external',
		'composite',
		'bundle',
		'bundled',
		'yith_bundle',
		'yith-composite',
		'subscription',
		'variable-subscription',
		'woosb',
	];
	/**
	 * Query Method Selector
	 *
	 * @var string
	 */
	protected $queryType = 'wp';
	/**
	 * To replace google product highlight attribute for CSV & TXT feed
	 *
	 * @var array
	 */
	protected $google_product_highlights = array(
		'product highlight 1',
		'product highlight 2',
		'product highlight 3',
		'product highlight 4',
		'product highlight 5',
		'product highlight 6',
		'product highlight 7',
		'product highlight 8',
		'product highlight 9',
		'product highlight 10',
	);

	/**
	 * To replace google additional image link attribute for CSV & TXT feed
	 *
	 * @var array
	 */
	protected $google_additional_image = array(
		'additional image link 1',
		'additional image link 2',
		'additional image link 3',
		'additional image link 4',
		'additional image link 5',
		'additional image link 6',
		'additional image link 7',
		'additional image link 8',
		'additional image link 9',
		'additional image link 10',
	);
	/**
	 * Google shipping tax attributes
	 *
	 * @var array
	 */
	/**
	 * Google shipping tax attributes
	 *
	 * @var array
	 */
	protected $google_shipping_tax = array(
		'shipping_country',
		'shipping_region',
		'shipping_postal_code',
		'shipping_service',
		'shipping_price',
		'min_handling_time',
		'max_handling_time',
		'min_transit_time',
		'max_transit_time',
		'tax',
		'tax_country',
		'tax_region',
		'tax_rate',
		'tax_ship',
		'installment_months',
		'installment_amount',
		'subscription_period',
		'subscription_period_length',
		'subscription_amount',
		'section_name',
		'attribute_name',
		'attribute_value',
		'unit_price_value',
		'unit_price_currency',
		'unit_price_unit',
		'additional_variant_label',
		'additional_variant_value',
	);
	/**
	 * XML Wrapper Array
	 * Contains 'header' and 'footer' for template.
	 *
	 * @var array
	 */
	protected $xml_wrapper = [];


	/**
	 * Attribute to skip in attribute loop for processing separately
	 *
	 * @var array
	 */
	protected $skipped_merchant_attributes = array(
		'google'    => array(
			'shipping_country',
			'shipping_region',
			'shipping_postal_code',
			'shipping_service',
			'shipping_price',
			'min_handling_time',
			'max_handling_time',
			'min_transit_time',
			'max_transit_time',
			'tax_country',
			'tax_region',
			'tax_rate',
			'tax_ship',
			'installment_months',
			'installment_amount',
			'subscription_period',
			'subscription_period_length',
			'subscription_amount',
			'section_name',
			'attribute_name',
			'attribute_value',
		),
		'facebook'  => array(
			'shipping_country',
			'shipping_region',
			'shipping_service',
			'shipping_price',
			'tax_country',
			'tax_region',
			'tax_rate',
			'tax_ship',
			'installment_months',
			'installment_amount',
			'subscription_period',
			'subscription_period_length',
			'subscription_amount',
			'section_name',
			'attribute_name',
			'attribute_value',
			'unit_price_value',
			'unit_price_currency',
			'unit_price_unit',
			'additional_variant_label',
			'additional_variant_value',
		),
		'bing'      => array(
			'shipping_country',
			'shipping_service',
			'shipping_price',
		),
		'pinterest' => array(
			'shipping_country',
			'shipping_service',
			'shipping_price',
			'shipping_region',
			'shipping_postal_code',
			'tax_country',
			'tax_region',
			'tax_rate',
			'tax_ship',
		),
	);

	/**
	 * Already Processed merchant attributes by the attribute loop
	 * this will ensure unique merchant attribute.
	 *
	 * @see Woo_Feed_Products_v::exclude_current_attribute()
	 * @var array
	 */
	protected $processed_merchant_attributes = array();


	/**
	 * Post meta prefix for dropdown item
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const POST_META_PREFIX = 'wf_cattr_';

	/**
	 * Product Attribute (taxonomy & local) Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_ATTRIBUTE_PREFIX = 'wf_attr_';

	/**
	 * Product Taxonomy Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_TAXONOMY_PREFIX = 'wf_taxo_';

	/**
	 * Product Custom Field Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_CUSTOM_IDENTIFIER = 'woo_feed_';

	/**
	 * Product Category Mapping Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_CATEGORY_MAPPING_PREFIX = 'wf_cmapping_';

	/**
	 * WordPress Option Prefix
	 *
	 * @since  4.3.33
	 * @author Nazrul Islam Nayan
	 * @var string
	 */
	const WP_OPTION_PREFIX = 'wf_option_';

	/**
	 * Advance Custom Field (ACF) Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_ACF_FIELDS = 'acf_fields_';

	/**
	 * Woo_Feed_Products_v3 constructor.
	 *
	 * @param $config
	 *
	 * @return void
	 */
	public function __construct( $config ) {
		$this->config    = woo_feed_parse_feed_rules( $config );
		$this->queryType = woo_feed_get_options( 'product_query_type' );
		$this->process_xml_wrapper();
		woo_feed_log_feed_process( $this->config['filename'], sprintf( 'Current Query Type is %s', $this->queryType ) );
	}

	/**
	 * Generate Query Args For WP/WC query class
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	protected function get_query_args( $type = 'wc' ) {
		$args = [];

		// Include Product Variations with db query if configured
		$variation_query = woo_feed_get_options( 'variation_query_type' );

		if ( 'wc' === $type ) {
			$product_types = $this->product_types;
			if ( 'variable' === $variation_query ) {
				$variation = array_search( 'variation', $product_types, true );
				if ( $variation ) {
					unset( $product_types[ $variation ] );
				}
			}

			$args = array(
				'limit'            => - 1, // phpcs:ignore
				'status'           => $this->post_status,
				'type'             => $product_types,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'return'           => 'ids',
				'suppress_filters' => false,
			);
		}

		if ( 'wp' === $type ) {

			$post_type = 'product';
			// Include Product Variations with db query if configured
			$variation_query = woo_feed_get_options( 'variation_query_type' );
			if ( 'individual' === $variation_query ) {
				$post_type = [ 'product', 'product_variation' ];
			}

			$args = array(
				'posts_per_page'         => - 1, // phpcs:ignore
				'post_type'              => $post_type,
				'post_status'            => 'publish',
				'order'                  => 'DESC',
				'fields'                 => 'ids',
				'cache_results'          => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters'       => false,
			);
		}

		return $args;
	}

	/**
	 * Get Products using WC_Product_Query
	 *
	 * @return array
	 */
	public function get_wc_query_products() {
		$args = $this->get_query_args( 'wc' );
		if ( woo_feed_is_debugging_enabled() ) {
			woo_feed_log_feed_process( $this->config['filename'], 'WC_Product_Query Args::' . PHP_EOL . print_r( $args, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			woo_feed_log( $this->config['filename'], 'WC_Product_Query Args::' . PHP_EOL . print_r( $args, true ), 'info' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}
		$query = new WC_Product_Query( $args );
		if ( woo_feed_is_debugging_enabled() ) {
			woo_feed_log_feed_process( $this->config['filename'], sprintf( 'WC_Product_Query Args ::' . PHP_EOL . '%s', print_r( $args, true ) ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}

		return $query->get_products();
	}

	/**
	 * Get Products using WP_Query
	 *
	 *  return array
	 */
	public function get_wp_query_products() {
		$args  = $this->get_query_args( 'wp' );
		$query = new WP_Query( $args );
		if ( ! is_wp_error( $query ) && woo_feed_is_debugging_enabled() ) {
			woo_feed_log_feed_process( $this->config['filename'], 'WC_Product_Query Args::' . PHP_EOL . print_r( $args, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			woo_feed_log_feed_process( $this->config['filename'], sprintf( 'WP_Query Request ::' . PHP_EOL . '%s', $query->request ) );
			woo_feed_log( $this->config['filename'], 'WC_Product_Query Args::' . PHP_EOL . print_r( $args, true ), 'info' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			woo_feed_log( $this->config['filename'], sprintf( 'WP_Query Request ::' . PHP_EOL . '%s', $query->request ), 'info' );
		}

		return $query->get_posts();
	}

	/**
	 * Get products
	 *
	 * @return array
	 */
	public function query_products() {
		$products = [];
		if ( 'wc' == $this->queryType ) {
			$products = $this->get_wc_query_products();
		} elseif ( 'wp' == $this->queryType ) {
			$products = $this->get_wp_query_products();
		} elseif ( 'both' == $this->queryType ) {
			$wc       = $this->get_wc_query_products();
			$wp       = $this->get_wp_query_products();
			$products = array_unique( array_merge( $wc, $wp ) );
		}

		//set product ids
		$this->product_ids = $products;

		return $products;
	}

	/**
	 * Organize Feed Attribute config
	 *
	 * @return array|bool
	 */
	public function get_attribute_config() {
		if ( empty( $this->config ) ) {
			return false;
		}

		$attributeConfig    = array();
		$merchantAttributes = $this->config['mattributes'];
		if ( ! empty( $merchantAttributes ) ) {
			$i = 0;
			foreach ( $merchantAttributes as $key => $value ) {
				$attributeConfig[ $i ]['mattributes'] = $value;
				$attributeConfig[ $i ]['prefix']      = $this->config['prefix'][ $key ];
				$attributeConfig[ $i ]['type']        = $this->config['type'][ $key ];
				$attributeConfig[ $i ]['attributes']  = $this->config['attributes'][ $key ];
				$attributeConfig[ $i ]['default']     = $this->config['default'][ $key ];
				$attributeConfig[ $i ]['suffix']      = $this->config['suffix'][ $key ];
				$attributeConfig[ $i ]['output_type'] = $this->config['output_type'][ $key ];
				$attributeConfig[ $i ]['limit']       = $this->config['limit'][ $key ];
				$i ++;
			}
		}

		return $attributeConfig;
	}

	/**
	 * Get Product Information according to feed config
	 *
	 * @param int[] $productIds
	 *
	 * @return array
	 * @since 3.2.0
	 *
	 */
	public function get_products( $productIds ) {

		if ( empty( $productIds ) ) {
			return [];
		}

		//filter big data
		$this->data = apply_filters( "woo_feed_feed_big_data", $this->data, $productIds, $this->config );

		/**
		 * Fires before looping through request product for getting product data
		 *
		 * @param int[] $productIds
		 * @param array $feedConfig
		 *
		 * @since 3.2.10
		 */
		do_action( 'woo_feed_before_product_loop', $productIds,  $this->config, $this->config );

		foreach ( $productIds as $key => $pid ) {
			woo_feed_log_feed_process( $this->config['filename'], sprintf( 'Loading Product Data For %d.', $pid ) );
			$product = wc_get_product( $pid );

			if ( $this->exclude_from_loop( $product ) ) {
				continue;
			}

			if ( $this->process_variation( $product ) ) {
				continue;
			}

			if ( ! $this->filter_product( $product ) ) {
				woo_feed_log_feed_process( $this->config['filename'], 'Skipping Product :: Matched with filter conditions' );
				continue;
			}

			woo_feed_log_feed_process( $this->config['filename'], 'Formatting Feed Data...' );

			// Add Single item wrapper before product info loop start
			if ( 'xml' == $this->config['feedType'] ) {
				$this->feedBody .= "\n";
				$this->feedBody .= '<' . $this->config['itemWrapper'] . '>';
				$this->feedBody .= "\n";
			}

			// reset processed attribute list before loop
			$this->processed_merchant_attributes = [];

			// Process attribute values
			$this->process_attributes( $product );

			try {
				woo_feed_log_feed_process( $this->config['filename'], 'Processing Merchant Specific Fields' );
				// Process feed data for uncommon merchant feed like Google,Facebook,Pinterest
				$this->process_for_merchant( $product, $this->pi );
			} catch ( Exception $e ) {
				$message = 'Error Processing Merchant Specific Fields.' . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
				woo_feed_log( $this->config['filename'], $message, 'critical', $e, true );
				woo_feed_log_fatal_error( $message, $e );
			}

			if ( 'xml' == $this->config['feedType'] ) {
				if ( empty( $this->feedHeader ) ) {
					$this->feedHeader = $this->process_xml_feed_header();
					$this->feedFooter = $this->process_xml_feed_footer();

				}

				$this->feedBody .= '</' . $this->config['itemWrapper'] . '>';


			} elseif ( 'txt' == $this->config['feedType'] ) {
				if ( empty( $this->feedHeader ) ) {
					$this->process_txt_feed_header();
				}
				$this->process_txt_feed_body();
			} elseif ( 'csv' == $this->config['feedType'] || 'xls' == $this->config['feedType'] || 'xlsx' == $this->config['feedType'] ) {
				if ( empty( $this->feedHeader ) ) {
					$this->process_csv_feed_header();
				}
				$this->process_csv_feed_body();
			} elseif ( 'json' == $this->config['feedType'] ) {
				$this->process_json_feed_body();
			}
			woo_feed_log_feed_process( $this->config['filename'], 'Done Formatting...' );
			$this->pi ++;
		}

		/**
		 * Fires after looping through request product for getting product data
		 *
		 * @param int[] $productIds
		 * @param array $feedConfig
		 *
		 * @since 3.2.10
		 */
		do_action( 'woo_feed_after_product_loop', $productIds, $this->config, $this->config );

		return $this->products;
	}

	/**
	 * Process product variations
	 *
	 * @param WC_Abstract_Legacy_Product $product
	 *
	 * @return bool
	 * @since 3.3.9
	 */
	protected function process_variation( $product ) {
		// Apply variable and variation settings
		if ( $product->is_type( 'variable' ) && $product->has_child() ) {
			$this->pi ++;

			$variation_query = woo_feed_get_options( 'variation_query_type' );
			if ( 'variable' === $variation_query ) {
				$variations = $product->get_visible_children();
				if ( is_array( $variations ) && ( sizeof( $variations ) > 0 ) ) {
					if ( woo_feed_is_debugging_enabled() ) {
						woo_feed_log_feed_process( $this->config['filename'], sprintf( 'Getting Variation Product(s) :: %s', implode( ', ', $variations ) ) );
					}
					$this->get_products( $variations );
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Process The Attributes and assign value to merchant attribute
	 *
	 * @param WC_Abstract_Legacy_Product $product
	 *
	 * @return void
	 * @since 3.3.9
	 */
	protected function process_attributes( $product ) {
//        print_r($this->config['attributes']);
		// Get Product Attribute values by type and assign to product array

		$merchant = $this->config['provider'];
		if ( $merchant == 'custom' ) {
			$custom_same_attributes = array();
			$mattributes            = array_count_values( $this->config['mattributes'] );

			foreach ( $mattributes as $keys => $attributes ) {
				if ( $attributes > 1 ) {
					for ( $i = 1;$i <= $attributes;$i++ ) {
						if ( ( $key = array_search( $keys, $this->config['mattributes'] ) ) !== false ) {
							unset( $this->config['mattributes'][ $key ] );
							$this->config['mattributes'][ $key ]          = $keys . '___' . $i;
							$custom_same_attributes[ $keys . '___' . $i ] = $keys;
							update_option( 'woo_feed_custom_template_1', $custom_same_attributes, false );
						}
					}
				}
			}
		}
		$custom_same_attributes = get_option( 'woo_feed_custom_template_1' );

		foreach ( $this->config['attributes'] as $attr_key => $attribute ) {

			$merchant_attribute = isset( $this->config['mattributes'][ $attr_key ] ) ? $this->config['mattributes'][ $attr_key ] : '';

			if ( $this->exclude_current_attribute( $product, $merchant_attribute, $attribute ) ) {
				continue;
			}

			// Add Prefix and Suffix into Output
			$prefix   = $this->config['prefix'][ $attr_key ];
			$suffix   = $this->config['suffix'][ $attr_key ];
			$merchant = $this->config['provider'];
			$feedType = $this->config['feedType'];

			if ( 'pattern' == $this->config['type'][ $attr_key ] ) {
				$attributeValue = $this->config['default'][ $attr_key ];
			} else { // Get Pattern value
				$attributeValue = $this->getAttributeValueByType( $product, $attribute, $merchant_attribute );
			}

			// Format Output according to Output Type config.
			if ( isset( $this->config['output_type'][ $attr_key ] ) ) {
				$outputType     = $this->config['output_type'][ $attr_key ];
				$attributeValue = $this->format_output( $attributeValue, $this->config['output_type'][ $attr_key ], $product, $attribute, $merchant_attribute );
			}

			// Limit Output.
			if ( isset( $this->config['limit'][ $attr_key ] ) ) {
				$attributeValue = $this->crop_string( $attributeValue, 0, $this->config['limit'][ $attr_key ] );
			}

			// Process prefix and suffix.
			$attributeValue = $this->process_prefix_suffix( $attributeValue, $prefix, $suffix, $attribute );

			if ( 'xml' == $feedType ) {

				// Replace XML Nodes according to merchant requirement.
				$getReplacedAttribute = woo_feed_replace_to_merchant_attribute( $merchant_attribute, $merchant, $feedType );

				if ( $merchant == 'custom' && is_array($custom_same_attributes)) {
					foreach ( $custom_same_attributes as $key => $value ) {
						if ( $getReplacedAttribute == $key ) {
							$getReplacedAttribute = $value;
						}
					}
				}

				// XML does not support space in node. So replace Space with Underscore.
				$getReplacedAttribute = str_replace( ' ', '_', $getReplacedAttribute );

				// Trim XML Element text & Encode for UTF-8.
				if ( ! empty( $attributeValue ) ) {
					$attributeValue = trim( $attributeValue );

					if ( 'custom' === $this->config['provider'] && strpos( $attributeValue, '<![CDATA[' ) === false ) {
						$attributeValue = htmlentities( $attributeValue, ENT_XML1 | ENT_QUOTES, 'UTF-8' );
					}
				}

				// Add closing XML node if value is empty
				if ( '' !== $attributeValue ) {
					// Add CDATA wrapper for XML feed to prevent XML error.
					$attributeValue = woo_feed_add_cdata( $merchant_attribute, $attributeValue, $merchant, $this->config['feedType'] );
//                    print_r($attributeValue);echo"<br/>";
					// Strip slash from output.
					$attributeValue = stripslashes( $attributeValue );
					$this->feedBody .= '<' . $getReplacedAttribute . '>' . "$attributeValue" . '</' . $getReplacedAttribute . '>';
					$this->feedBody .= "\n";
				} else {
					$this->feedBody .= '<' . $getReplacedAttribute . '/>';
					$this->feedBody .= "\n";
				}

			} elseif ( in_array( $feedType, [ 'csv', 'tsv', 'xls', 'xlsx' ], true ) ) {
				$merchant_attribute = woo_feed_replace_to_merchant_attribute( $merchant_attribute, $merchant, $feedType );
				$merchant_attribute = $this->processStringForCSV( $merchant_attribute );

				if ( "shipping" === $merchant_attribute && 'bing' == $this->config['provider'] ) {
					$merchant_attribute = 'shipping(country:service:price)';
				} elseif ( "shipping" === $merchant_attribute ) {
					$merchant_attribute = 'shipping(country:region:service:price)';
				}

				if ( "tax" === $merchant_attribute ) {
					$merchant_attribute = 'tax(country:region:rate:tax_ship)';
				}

				$attributeValue = $this->processStringForCSV( $attributeValue );

			} elseif ( 'txt' == $feedType ) {
				$merchant_attribute = woo_feed_replace_to_merchant_attribute( $merchant_attribute, $merchant, $feedType );
				$merchant_attribute = $this->processStringForTXT( $merchant_attribute );

				if ( "shipping" === $merchant_attribute && 'bing' == $this->config['provider'] ) {
					$merchant_attribute = 'shipping(country:service:price)';
				} elseif ( "shipping" === $merchant_attribute ) {
					$merchant_attribute = 'shipping(country:region:service:price)';
				}

				if ( "tax" === $merchant_attribute ) {
					$merchant_attribute = 'tax(country:region:rate:tax_ship)';
				}

				$attributeValue = $this->processStringForTXT( $attributeValue );
			}

			$this->products[ $this->pi ][ $merchant_attribute ] = $attributeValue;
		}
	}

	/**
	 * Process Nested Attributes
	 *
	 * @return array
	 * @since 4.0.5
	 *
	 */
	protected function feed_nested_attributes() {
		return [
			'reviewer'    => [ 'reviewer', 'name' ],
			'ratings'     => [ 'ratings', 'overall' ],
			'product_url' => [ 'products', 'product', 'product_url' ],
		];
	}

	/**
	 * Process Nested Attributes
	 *
	 * @param string $attribute //product feed tag
	 * @param string $content   //product feed content
	 *
	 * @return string
	 * @since 4.0.5
	 *
	 */

	protected function nested_attributes_element( $attribute, $content ) {
		$starter  = '';
		$finisher = '';
		$element  = '';

		if ( empty( $attribute ) ) {
			return $attribute;
		}

		$attr_names = $this->feed_nested_attributes();

		foreach ( $attr_names as $key => $value ) {
			if ( $key === $attribute ) {
				//starter tags element
				foreach ( $value as $item_value ) {
					$starter .= '<' . strval( $item_value ) . '>';
				}

				//finishing tags element
				$rev_value = array_reverse( $value );
				foreach ( $rev_value as $item_value ) {
					$finisher .= '</' . strval( $item_value ) . '>';
				}
			}
		}

		$element = $starter . $content . $finisher;

		return $element;
	}

	/**
	 * Check if current product should be processed for feed
	 * This should be using by Woo_Feed_Products_v3::get_products()
	 *
	 * @param WC_Product $product
	 *
	 * @return bool
	 * @since 3.3.9
	 *
	 */
	protected function exclude_from_loop( $product ) {
		// Skip for invalid products
		if ( ! is_object( $product ) ) {
			woo_feed_log_feed_process( $this->config['filename'], 'Skipping Product :: Product data is not a valid WC_Product object.' );

			return true;
		}

		// For WP_Query check available product types
		if ( 'wp' == $this->queryType && ! in_array( $product->get_type(), $this->product_types, true ) ) {
			woo_feed_log_feed_process( $this->config['filename'], sprintf( 'Skipping Product :: Invalid Post/Product Type : %s.', $product->get_type() ) );

			return true;
		}

		// Skip orphaned variation
		if ( $product->is_type( 'variation' ) && ! $product->get_parent_id() ) {
			woo_feed_log_feed_process( $this->config['filename'], sprintf( 'Skipping Product :: Orphaned variation product [id: %s] skipped.', $product->get_id() ) );

			return true;
		}

		// Skip for invisible products
		if ( ! $product->is_visible() ) {
			woo_feed_log_feed_process( $this->config['filename'], 'Skipping Product :: Product is not visible.' );

			return true;
		}

		return false;
	}

	/**
	 * Check if current attribute/merchant attribute should be processed for feed
	 * This should be using by Woo_Feed_Products_v3::get_products()
	 *
	 * @param WC_Product $product
	 * @param string     $merchant_attribute
	 * @param string     $product_attribute
	 * @param string     $feedType
	 *
	 * @return bool
	 *
	 * @since 3.3.9
	 *
	 */
	protected function exclude_current_attribute( $product, $merchant_attribute, $product_attribute, $feedType = 'xml' ) {

		if ( empty( $merchant_attribute ) ) {
			return true;
		}

		if (
			in_array( $this->config['provider'], array_keys( $this->skipped_merchant_attributes ) ) &&
			in_array( $merchant_attribute, $this->skipped_merchant_attributes[ $this->config['provider'] ] )

		) {
			return true;
		}

		if ( 'shopping_ads_excluded_country' !== $merchant_attribute && in_array( $merchant_attribute, $this->processed_merchant_attributes ) ) {
			return true;
		}

		$this->processed_merchant_attributes[] = $merchant_attribute;

		return false;
	}

	/**
	 * Wrapper for substr with <![CDATA[string]]> support
	 *
	 * @param string $string
	 * @param int    $start
	 * @param int    $limit
	 *
	 * @return string
	 * @see substr
	 *
	 */
	protected function crop_string( $string, $start = 0, $limit = null ) {
		$limit = absint( $limit );
		if ( $limit > 0 ) {
			$start = absint( $start );
			if ( strpos( $string, '<![CDATA[' ) !== false ) {
				$string = str_replace( array( '<![CDATA[', ']]>' ), array( '', '' ), $string );
				$string = substr( $string, $start, $limit );
				$string = '<![CDATA[' . $string . ']]>';
			} else {
				$string = substr( $string, $start, $limit );
			}
		}

		return $string;
	}

	/**
	 * Process feed data according to merchant uncommon requirements like Google
	 *
	 * @param object $productObj WC_Product
	 * @param int    $index      Product Index
	 *
	 * @since 3.2.0
	 */
	protected function process_for_merchant( $productObj, $index ) {
		$product                      = $this->products[ $index ];
		$merchantAttributes           = $this->config['mattributes'];
		$s                            = 0; // Shipping Index
		$i                            = 0; // Installment Index
		$t                            = 0; // Tax Index
		$tax                          = '';
		$shipping                     = '';
		$sub                          = 0;
		$subscription                 = '';
		$ins                          = 0; // Installment Index
		$installment                  = "";
		$product_detail               = '';
		$unit_price                   = '';
		$additional_variant_attribute = '';
		$pd                           = 0;
		$up                           = 0; //Unit Price for facebook
		$av                           = 0; //Additional Variant Attribute for facebook


		// Format Shipping and Tax data for CSV and TXT feed only for google and facebook

		if ( 'xml' != $this->config['feedType'] && in_array( $this->config['provider'], array(
				'google',
				'facebook',
				'bing',
				'snapchat',
				'pinterest'
			) ) ) {
			foreach ( $merchantAttributes as $key => $value ) {

				if ( ! in_array( $value, $this->google_shipping_tax, true ) ) {
					continue;
				}

				# Get value by attribute type with prefix & suffix
				$output = $this->process_for_merchant_get_value( $productObj, $key );

				if ( 'shipping_country' == $value ) {
					$s ++;
					$shipping .= $output;
				}
				if ( 'shipping_region' == $value ) {
					$shipping .= ':' . $output;
				} elseif ( 'shipping_postal_code' == $value ) {
					$shipping .= ':' . $output;
				}
				if ( 'shipping_service' == $value ) {
					$shipping .= ':' . $output;
				}
				if ( 'shipping_price' == $value ) {
					$shipping .= ':' . $output;
				}

				if ( 'section_name' == $value ) {
					$pd ++;
					$product_detail .= $output;
				}
				if ( 'attribute_name' == $value ) {
					$product_detail .= ':' . $output;
				}
				if ( 'attribute_value' == $value ) {
					$product_detail .= ':' . $output;
				}

				//facebook nested attributes
				if ( isset( $this->config['provider'] ) && "facebook" === $this->config['provider'] ) {
					if ( 'unit_price_value' == $value ) {
						$up ++;
						$unit_price .= $output;
					}
					if ( 'unit_price_currency' == $value ) {
						$unit_price .= ':' . $output;
					}
					if ( 'unit_price_unit' == $value ) {
						$unit_price .= ':' . $output;
					}

					if ( 'additional_variant_label' == $value ) {
						$av ++;
						$additional_variant_attribute .= $output;
					}
					if ( 'additional_variant_value' == $value ) {
						$additional_variant_attribute .= ':' . $output;
					}

					if ( 0 < $up ) {
						$this->products[ $this->pi ]["unit_price"] = $unit_price;
					}

					if ( 0 < $av ) {
						$this->products[ $this->pi ]["additional_variant_attribute"] = $additional_variant_attribute;
					}
				}


				if ( 'installment_months' == $value ) {
					$ins ++;
					$installment .= $output;
				}
				if ( 'installment_amount' == $value ) {
					$installment .= ':' . $output;
				}

				if ( 'subscription_period' == $value ) {
					$sub ++;
					$subscription .= $output;
				}
				if ( 'subscription_period_length' == $value ) {
					$subscription .= ':' . $output;
				}
				if ( 'subscription_amount' == $value ) {
					$subscription .= ':' . $output;
				}
			}

			if ( 0 < $pd ) {
				$this->products[ $this->pi ]["product detail"] = $product_detail;
			}

			if ( 0 < $sub ) {
				$this->products[ $this->pi ]["subscription cost"] = $subscription;

			}

			if ( 0 < $ins ) {
				$this->products[ $this->pi ]["installment"] = $installment;
			}
		}


		if ( in_array( $this->config['provider'], array( 'google', 'facebook', 'snapchat', 'bing', 'pinterest' ) ) ) {


			// Reformat Shipping attributes for google, facebook
			if ( 'xml' == $this->config['feedType'] ) {
				foreach ( $merchantAttributes as $key => $value ) {

					if ( ! in_array( $value, $this->google_shipping_tax ) ) {
						continue;
					}


					# Get value by attribute type with prefix & suffix
					$output = $this->process_for_merchant_get_value( $productObj, $key );


					if ( 'shipping_country' == $value ) {
						if ( 0 == $s ) {
							$shipping .= '<g:shipping>';
							$s        = 1;
						} else {
							$shipping .= '</g:shipping>' . "\n";
							$shipping .= '<g:shipping>';
						}
					} elseif ( ! in_array( 'shipping_country', $merchantAttributes ) && 'shipping_price' == $value ) {
						if ( 0 == $s ) {
							$shipping .= '<g:shipping>';
							$s        = 1;
						} else {
							$shipping .= '</g:shipping>' . "\n";
							$shipping .= '<g:shipping>';
						}
					}

					if ( 'shipping_country' == $value ) {
						$shipping .= '<g:country>' . $output . '</g:country>' . "\n";
					} elseif ( 'shipping_region' == $value ) {
						$shipping .= '<g:region>' . $output . '</g:region>' . "\n";
					} elseif ( 'shipping_region' == $value ) {
						$shipping .= '<g:region>' . $output . '</g:region>' . "\n";
					} elseif ( 'shipping_service' == $value ) {
						$shipping .= '<g:service>' . $output . '</g:service>' . "\n";
					} elseif ( 'shipping_postal_code' == $value ) {
						$shipping .= '<g:postal_code>' . $output . '</g:postal_code>' . "\n";
					} elseif ( 'shipping_price' == $value ) {
						$shipping .= '<g:price>' . $output . '</g:price>' . "\n";
					} elseif ( 'tax_country' == $value ) {
						if ( 0 == $t ) {
							$tax .= '<g:tax>';
							$t   = 1;
						} else {
							$tax .= '</g:tax>' . "\n";
							$tax .= '<g:tax>';
						}
						$tax .= '<g:country>' . $output . '</g:country>' . "\n";
					} elseif ( 'tax_region' == $value ) {
						$tax .= '<g:region>' . $output . '</g:region>' . "\n";
					} elseif ( 'tax_rate' == $value ) {
						$tax .= '<g:rate>' . $output . '</g:rate>' . "\n";
					} elseif ( 'tax_ship' == $value ) {
						$tax .= '<g:tax_ship>' . $output . '</g:tax_ship>' . "\n";
					} elseif ( 'subscription_period' == $value ) {
						if ( 0 == $sub ) {
							$subscription .= '<g:subscription_cost>';
							$sub          = 1;
						} else {
							$subscription .= '</g:subscription_cost>' . "\n";
							$subscription .= '<g:subscription_cost>';
						}
						$subscription .= '<g:period>' . $output . '</g:period>' . "\n";
					} elseif ( 'subscription_period_length' == $value ) {
						$subscription .= '<g:period_length>' . $output . '</g:period_length>' . "\n";
					} elseif ( 'subscription_amount' == $value ) {
						$subscription .= '<g:amount>' . $output . '</g:amount>' . "\n";
					}

					if ( 'section_name' == $value ) {
						if ( 0 == $pd ) {
							$product_detail .= '<g:product_detail>';
							$pd             = 1;
						} else {
							$product_detail .= '</g:product_detail>' . "\n";
							$product_detail .= '<g:product_detail>';
						}
					} elseif ( ! in_array( 'section_name', $merchantAttributes ) && 'attribute_name' == $value ) {
						if ( 0 == $pd ) {
							$product_detail .= '<g:product_detail>';
							$pd             = 1;
						} else {
							$product_detail .= '</g:product_detail>' . "\n";
							$product_detail .= '<g:product_detail>';
						}
					}

					if ( 'section_name' == $value ) {
						$product_detail .= '<g:section_name>' . $output . '</g:section_name>' . "\n";
					} elseif ( 'attribute_name' == $value ) {
						$product_detail .= '<g:attribute_name>' . $output . '</g:attribute_name>' . "\n";
					} elseif ( 'attribute_value' == $value ) {
						$product_detail .= '<g:attribute_value>' . $output . '</g:attribute_value>' . "\n";
					}

					if ( isset( $this->config['provider'] ) && "facebook" === $this->config['provider'] ) {

						// unit_price configure
						if ( 'unit_price_value' == $value ) {
							if ( 0 == $up ) {
								$unit_price .= '<unit_price>';
								$up         = 1;
							} else {
								$unit_price .= '</unit_price>' . "\n";
								$unit_price .= '<unit_price>';
							}
						} elseif ( ! in_array( 'unit_price_value', $merchantAttributes ) && 'unit_price_currency' == $value ) {
							if ( 0 == $up ) {
								$unit_price .= '<unit_price>';
								$up         = 1;
							} else {
								$unit_price .= '</unit_price>' . "\n";
								$unit_price .= '<unit_price>';
							}
						}

						if ( 'unit_price_value' == $value ) {
							$unit_price .= '<value>' . $output . '</value>' . "\n";
						} elseif ( 'unit_price_currency' == $value ) {
							$unit_price .= '<currency>' . $output . '</currency>' . "\n";
						} elseif ( 'unit_price_unit' == $value ) {
							$unit_price .= '<unit>' . $output . '</unit>' . "\n";
						}

						// additional_variant_attribute configure
						if ( 'additional_variant_label' == $value ) {
							if ( 0 == $av ) {
								$additional_variant_attribute .= '<additional_variant_attribute>';
								$av                           = 1;
							} else {
								$additional_variant_attribute .= '</additional_variant_attribute>' . "\n";
								$additional_variant_attribute .= '<additional_variant_attribute>';
							}
						} elseif ( ! in_array( 'additional_variant_label', $merchantAttributes ) && 'additional_variant_value' == $value ) {
							if ( 0 == $av ) {
								$additional_variant_attribute .= '<additional_variant_attribute>';
								$av                           = 1;
							} else {
								$additional_variant_attribute .= '</additional_variant_attribute>' . "\n";
								$additional_variant_attribute .= '<additional_variant_attribute>';
							}
						}

						if ( 'additional_variant_label' == $value ) {
							$additional_variant_attribute .= '<label>' . $output . '</label>' . "\n";
						} elseif ( 'additional_variant_value' == $value ) {
							$additional_variant_attribute .= '<value>' . $output . '</value>' . "\n";
						}
					}


					if ( 'installment_months' === $value ) {
						if ( 0 == $ins ) {
							$installment .= '<g:installment>';
							$ins         = 1;
						} else {
							$installment .= '</g:installment>' . "\n";
							$installment .= '<g:installment>';
						}
						$installment .= '<g:months>' . $output . '</g:months>' . "\n";
					} elseif ( 'installment_amount' == $value ) {
						$installment .= '<g:amount>' . $output . '</g:amount>' . "\n";
					}
				}

				if ( 1 == $pd ) {
					$product_detail .= '</g:product_detail>';
				}

				if ( isset( $this->config['provider'] ) && "facebook" === $this->config['provider'] ) {
					if ( 1 == $up ) {
						$unit_price .= '</unit_price>';
					}

					if ( 1 == $av ) {
						$additional_variant_attribute .= '</additional_variant_attribute>';
					}

					$this->feedBody .= $unit_price;
					$this->feedBody .= $additional_variant_attribute;
				}

				if ( 1 == $s ) {
					$shipping .= '</g:shipping>';
				}
				if ( 1 == $t ) {
					$tax .= '</g:tax>';
				}

				if ( 1 == $sub ) {
					$subscription .= '</g:subscription_cost>';
				}

				if ( 1 == $ins ) {
					$installment .= '</g:installment>';
				}

				//$this->feedBody .= $shipping;
				//$this->feedBody .= $tax;
				$this->feedBody .= $product_detail;
				$this->feedBody .= $installment;

				if ( $productObj->is_type( 'subscription' ) ||
				     $productObj->is_type( 'variable-subscription' ) ||
				     $productObj->is_type( 'subscription_variation' ) ) {
					$this->feedBody .= $subscription;
				}
			}
			// ADD g:identifier_exists
			$identifier      = array( 'brand', 'upc', 'sku', 'mpn', 'gtin' );
			$countIdentifier = 0;
			if ( ! in_array( 'identifier_exists', $merchantAttributes ) ) {
				if ( count( array_intersect_key( array_flip( $identifier ), $product ) ) >= 2 ) {
					// Any 2 required keys exist!
					// @TODO Refactor with OR
					if ( array_key_exists( 'brand', $product ) && ! empty( $product['brand'] ) ) {
						$countIdentifier ++;
					}
					if ( array_key_exists( 'upc', $product ) && ! empty( $product['upc'] ) ) {
						$countIdentifier ++;
					}
					if ( array_key_exists( 'sku', $product ) && ! empty( $product['sku'] ) ) {
						$countIdentifier ++;
					}
					if ( array_key_exists( 'mpn', $product ) && ! empty( $product['mpn'] ) ) {
						$countIdentifier ++;
					}
					if ( array_key_exists( 'gtin', $product ) && ! empty( $product['gtin'] ) ) {
						$countIdentifier ++;
					}
				}

				if ( 'xml' == $this->config['feedType'] ) {
					if ( $countIdentifier >= 2 ) {
						$this->feedBody .= '<g:identifier_exists>yes</g:identifier_exists>';
					} else {
						$this->feedBody .= '<g:identifier_exists>no</g:identifier_exists>';
					}
				} else {
					$identifier_exists = 'identifier exists';
					if ( in_array( $this->config['provider'], array( 'bing', 'pinterest' ) ) ) {
						$identifier_exists = 'identifier_exists';
					}

					if ( $countIdentifier >= 2 ) {
						$this->products[ $this->pi ][ $identifier_exists ] = 'yes';
					} else {
						$this->products[ $this->pi ][ $identifier_exists ] = 'no';
					}
				}
			}
		}
	}

	private function process_for_merchant_get_value( $productObj, $key ) {
		$prefix             = $this->config['prefix'][ $key ];
		$suffix             = $this->config['suffix'][ $key ];
		$attribute          = $this->config['attributes'][ $key ];
		$merchant_attribute = $this->config['mattributes'][ $key ];

		if ( 'pattern' == $this->config['type'][ $key ] ) {// Get Pattern value.
			$output = $this->config['default'][ $key ];
			$output = $this->apply_filters_to_attribute_value( $output, $productObj, $attribute, $merchant_attribute );
		} else {
			$output = $this->getAttributeValueByType( $productObj, $attribute, $merchant_attribute );
		}

		$output = $this->format_output( $output, $this->config['output_type'], $productObj, $attribute, $merchant_attribute );

		$output = $this->process_prefix_suffix( $output, $prefix, $suffix, $attribute = '' );

		return $output;
	}

	/**
	 * Get Query Type Settings
	 *
	 * @return string
	 */
	public function get_query_type() {
		return $this->queryType;
	}

	public function get_product_types() {
		return $this->product_types;
	}

	/**
	 * Generate TXT Feed Header
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function process_txt_feed_header() {
		// Set Delimiter
		if ( 'tab' == $this->config['delimiter'] ) {
			$this->delimiter = "\t";
		} else {
			$this->delimiter = $this->config['delimiter'];
		}

		// Set Enclosure
		if ( ! empty( $this->config['enclosure'] ) ) {
			$this->enclosure = $this->config['enclosure'];
			if ( 'double' == $this->enclosure ) {
				$this->enclosure = '"';
			} elseif ( 'single' == $this->enclosure ) {
				$this->enclosure = "'";
			} else {
				$this->enclosure = '';
			}
		} else {
			$this->enclosure = '';
		}

		$eol = PHP_EOL;
		if ( 'trovaprezzi' === $this->config['provider'] ) {
			$eol = '<endrecord>' . PHP_EOL;
		}

		$product = $this->products[ $this->pi ];

		$headers = array_keys( $product );

		$this->feedHeader .= $this->enclosure . implode( "$this->enclosure$this->delimiter$this->enclosure", $headers ) . $this->enclosure . $eol;

		if ( 'google' === $this->config['provider'] ) {
			$this->feedHeader = str_replace( $this->google_product_highlights, 'product highlight', $this->feedHeader );
			$this->feedHeader = str_replace( $this->google_additional_image, 'additional image link', $this->feedHeader );
		}

		return $this->feedHeader;
	}

	/**
	 * Generate TXT Feed Body
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function process_txt_feed_body() {
		$productInfo = array_values( $this->products[ $this->pi ] );
		$eol         = PHP_EOL;
		if ( 'trovaprezzi' === $this->config['provider'] ) {
			$eol = '<endrecord>' . PHP_EOL;
		}
		$this->feedBody .= $this->enclosure . implode( "$this->enclosure$this->delimiter$this->enclosure", $productInfo ) . $this->enclosure . $eol;

		return $this->feedBody;
	}

	/**
	 * Generate CSV Feed Header
	 *
	 * @return array
	 * @since 3.2.0
	 *
	 */
	protected function process_csv_feed_header() {
		// Set Delimiter
		if ( 'tab' == $this->config['delimiter'] ) {
			$this->delimiter = "\t";
		} else {
			$this->delimiter = $this->config['delimiter'];
		}

		// Set Enclosure
		if ( ! empty( $this->config['enclosure'] ) ) {
			$this->enclosure = $this->config['enclosure'];
			if ( 'double' == $this->enclosure ) {
				$this->enclosure = '"';
			} elseif ( 'single' == $this->enclosure ) {
				$this->enclosure = "'";
			} else {
				$this->enclosure = '';
			}
		} else {
			$this->enclosure = '';
		}

		$product = $this->products[ $this->pi ];

		$this->feedHeader = array_keys( $product );

		if ( 'google' === $this->config['provider'] ) {
			$this->feedHeader = str_replace( $this->google_product_highlights, 'product highlight', $this->feedHeader );
			$this->feedHeader = str_replace( $this->google_additional_image, 'additional image link', $this->feedHeader );
		}

		return $this->feedHeader;
	}

	/**
	 * Generate CSV Feed Body
	 *
	 * @return array
	 * @since 3.2.0
	 */
	protected function process_csv_feed_body() {
		$product          = $this->products[ $this->pi ];
		$this->feedBody[] = array_values( $product );

		return $this->feedBody;
	}

	protected function process_json_feed_body() {
		$product          = $this->products[ $this->pi ];
		$this->feedBody[] = $product;

		return $this->feedBody;
	}

	protected function process_xml_wrapper() {
		$itemsWrapperClose            = explode( ' ', $this->config['itemsWrapper'] );
		$itemsWrapperClose            = $itemsWrapperClose[0];
		$this->xml_wrapper            = [
			'header' => '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL . '<' . wp_unslash( $this->config['itemsWrapper'] ) . '>',
			'footer' => PHP_EOL . '</' . $itemsWrapperClose . '>',
		];
		$this->config['itemWrapper']  = str_replace( ' ', '_', $this->config['itemWrapper'] );
		$this->config['itemsWrapper'] = str_replace( ' ', '_', $this->config['itemsWrapper'] );

		if ( file_exists( WOO_FEED_FREE_ADMIN_PATH . 'partials/templates/' . $this->config['provider'] . '.txt' ) ) {
			$txt = file_get_contents( WOO_FEED_FREE_ADMIN_PATH . 'partials/templates/' . $this->config['provider'] . '.txt' );
			$txt = trim( $txt );
			$txt = explode( '{separator}', $txt );
			if ( 2 === count( $txt ) ) {
				$this->xml_wrapper['header'] = trim( $txt[0] );
				$this->xml_wrapper['footer'] = "\n" . trim( $txt[1] );
			}
		}

		if ( ! empty( $this->config['extraHeader'] ) ) {
			$this->xml_wrapper['header'] .= PHP_EOL . $this->config['extraHeader'];
		}

		// replace template variables.
		$datetime_now = date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql' ) ) ); // {DateTimeNow}
		if ( 'pinterest_rss' === $this->config['provider'] ) { // DATE_RFC822 date format for pinterest rss feed
			$datetime_now = date( 'r', strtotime( current_time( 'mysql' ) ) ); // {DateTimeNow}
		}
		$blog_name = get_bloginfo( 'name' ); // {BlogName}
		$blog_url  = get_bloginfo( 'url' ); // {BlogURL}
		//$blog_desc = get_bloginfo('description'); // {BlogDescription}
		$blog_desc  = "CTX Feed - This product feed is generated with the CTX Feed - WooCommerce Product Feed Manager plugin by WebAppick.com. For all your support questions check out our plugin Docs on https://webappick.com/docs or e-mail to: support@webappick.com"; // {BlogDescription}
		$blog_email = get_bloginfo( 'admin_email' ); // {BlogEmail}

		$this->xml_wrapper['header'] = str_replace(
			[ '{DateTimeNow}', '{BlogName}', '{BlogURL}', '{BlogDescription}', '{BlogEmail}' ],
			[ $datetime_now, $blog_name, $blog_url, $blog_desc, $blog_email ],
			$this->xml_wrapper['header']
		);
	}

	/**
	 * Make XML feed header
	 *
	 * @return string
	 * @since 3.2.0
	 */
	protected function process_xml_feed_header() {
		return $this->xml_wrapper['header'];
	}

	/**
	 * Make XML feed header
	 *
	 * @return string
	 * @since 3.2.0
	 */
	protected function process_xml_feed_footer() {
		return $this->xml_wrapper['footer'];
	}

	/**
	 * Process string for TXT CSV Feed
	 *
	 * @param $string
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	protected function processStringForTXT( $string ) {
		if ( ! empty( $string ) ) {
			$string = html_entity_decode( $string, ENT_HTML401 | ENT_QUOTES ); // Convert any HTML entities

			if ( stristr( $string, '"' ) ) {
				$string = str_replace( '"', '""', $string );
			}
			$string = str_replace( "\n", ' ', $string );
			$string = str_replace( "\r", ' ', $string );
			$string = str_replace( "\t", ' ', $string );
			$string = trim( $string );
			$string = stripslashes( $string );

			return $string;
		} elseif ( '0' == $string ) {
			return '0';
		} else {
			return '';
		}
	}

	/**
	 * Process string for CSV
	 *
	 * @param $string
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	protected function processStringForCSV( $string ) {
		if ( ! empty( $string ) ) {
			$string = str_replace( "\n", ' ', $string );
			$string = str_replace( "\r", ' ', $string );
			$string = trim( $string );
			$string = stripslashes( $string );

			return $string;
		} elseif ( '0' == $string ) {
			return '0';
		} else {
			return '';
		}
	}

	/**
	 * Get Product Attribute Value by Type
	 *
	 * @param $product  WC_Product
	 * @param $attribute
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	public function getAttributeValueByType( $product, $attribute, $merchant_attribute = '' ) {

		if ( method_exists( $this, $attribute ) ) {
			$output = $this->$attribute( $product );
		} elseif ( false !== strpos( $attribute, self::PRODUCT_ACF_FIELDS ) ) {
			$output = $this->getACFField( $product, $attribute );
		} elseif ( false !== strpos( $attribute, self::PRODUCT_CUSTOM_IDENTIFIER ) || woo_feed_strpos_array( array(
				'_identifier_gtin',
				'_identifier_ean',
				'_identifier_mpn'
			), $attribute ) ) {
			$output = $this->getCustomField( $product, $attribute );
		} elseif ( false !== strpos( $attribute, self::PRODUCT_ATTRIBUTE_PREFIX ) ) {
			$attribute = str_replace( self::PRODUCT_ATTRIBUTE_PREFIX, '', $attribute );
			$output    = $this->getProductAttribute( $product, $attribute );
		} elseif ( false !== strpos( $attribute, self::POST_META_PREFIX ) ) {
			$attribute = str_replace( self::POST_META_PREFIX, '', $attribute );
			$output    = $this->getProductMeta( $product, $attribute );
		} elseif ( false !== strpos( $attribute, self::PRODUCT_TAXONOMY_PREFIX ) ) {
			$attribute = str_replace( self::PRODUCT_TAXONOMY_PREFIX, '', $attribute );
			$output    = $this->getProductTaxonomy( $product, $attribute );
		} elseif ( false !== strpos( $attribute, self::PRODUCT_CATEGORY_MAPPING_PREFIX ) ) {
			$id     = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$output = woo_feed_get_category_mapping_value( $attribute, $id );
		} elseif ( false !== strpos( $attribute, self::WP_OPTION_PREFIX ) ) {
			$optionName = str_replace( self::WP_OPTION_PREFIX, '', $attribute );
			$output     = get_option( $optionName );
		} elseif ( 'image_' == substr( $attribute, 0, 6 ) ) {
			// For additional image method images() will be used with extra parameter - image number
			$imageKey = explode( '_', $attribute );
			if ( ! isset( $imageKey[1] ) || ( isset( $imageKey[1] ) && ( empty( $imageKey[1] ) || ! is_numeric( $imageKey[1] ) ) ) ) {
				$imageKey[1] = '';
			}
			$output = call_user_func_array( array( $this, 'images' ), array( $product, $imageKey[1] ) );
		} else {
			// return the attribute so multiple attribute can be join with separator to make custom attribute.
			$output = $attribute;
		}

		// Json encode if value is an array
		if ( is_array( $output ) ) {
			$output = wp_json_encode( $output );
		}

		$output = $this->apply_filters_to_attribute_value( $output, $product, $attribute, $merchant_attribute );

		return $output;
	}

	/**
	 *  Apply Filter to Attribute value
	 *
	 * @param $output
	 * @param $product
	 * @param $attribute
	 * @param $merchant_attribute
	 *
	 * @return mixed|void
	 */
	protected function apply_filters_to_attribute_value( $output, $product, $attribute, $merchant_attribute ) {
		/**
		 * Filter attribute value
		 *
		 * @param string                     $output  the output
		 * @param WC_Abstract_Legacy_Product $product Product Object.
		 * @param array feed config/rule
		 *
		 * @since 3.4.3
		 *
		 */
		$output = apply_filters( 'woo_feed_get_attribute', $output, $product, $this->config, $merchant_attribute );

		/**
		 * Filter attribute value before return based on attribute name
		 *
		 * @param string                     $output  the output
		 * @param WC_Abstract_Legacy_Product $product Product Object.
		 * @param array feed config/rule
		 *
		 * @since 3.3.5
		 *
		 */
		$output = apply_filters( "woo_feed_get_{$attribute}_attribute", $output, $product, $this->config );

		/**
		 * Filter attribute value before return based on merchant and attribute name
		 *
		 * @param string                     $output  the output
		 * @param WC_Abstract_Legacy_Product $product Product Object.
		 * @param array feed config/rule
		 *
		 * @since 3.3.7
		 *
		 */
		$output = apply_filters( "woo_feed_get_{$this->config['provider']}_{$attribute}_attribute", $output, $product, $this->config );

		return $output;
	}


	/**
	 * Get Product Id
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product Product
	 *                                                                                                                                 Object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 */
	protected function id( $product ) {
		$id = $product->get_id();

		return apply_filters( 'woo_feed_filter_product_id', $id, $product, $this->config );
	}

	/**
	 * Get Product Title
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function title( $product ) {
		$title = woo_feed_strip_all_tags( $this->remove_short_codes( $product->get_name() ) );
		// Add all available variation attributes to variation title.
		if ( $product->is_type( 'variation' ) && ! empty( $product->get_attributes() ) ) {
			$title      = $this->parent_title( $product );
			$attributes = [];
			foreach ( $product->get_attributes() as $slug => $value ) {
				$attribute = $product->get_attribute( $slug );
				if ( empty( $attribute ) ) {
					continue;
				}
				$attributes[ $slug ] = $attribute;
			}

			// set variation attributes with separator
			if ( isset( $this->config['provider'] ) && 'google' === $this->config['provider'] ) {
				$variation_attributes = implode( ' - ', $attributes );
			} else {
				$variation_attributes = implode( ', ', $attributes );
			}

			//get product title with variation attribute
			$get_with_var_attributes = apply_filters( "woo_feed_get_product_title_with_variation_attribute", true, $product, $this->config );

			if ( $get_with_var_attributes ) {
				$title .= " - " . $variation_attributes;
			}
		}

        if( $this->config['provider'] == "admarkt" && $this->config['feedType'] == 'xml' ) {
            $title = str_replace('&', '&amp;', $title);
        }

		return apply_filters( 'woo_feed_filter_product_title', $title, $product, $this->config );
	}

	/**
	 * Get Parent Product Title
	 *
	 * @param WC_Product $product Product Object.
	 *
	 * @return mixed
	 * @since  5.1.8
	 * @author Nazrul Islam Nayan
	 */
	protected function parent_title( $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$product = wc_get_product( $product->get_parent_id() );
			$title   = woo_feed_strip_all_tags( $this->remove_short_codes( $product->get_name() ) );
		} else {
			$title = $this->title( $product );
		}

		return apply_filters( 'woo_feed_filter_product_parent_title', $title, $product, $this->config );
	}

	/**
	 * Get All in One SEO Canonical URL
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.12
	 */
	protected function _aioseop_canonical_url( $product ) {
		$aioseop_canonical_url = '';
		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && class_exists( 'AIOSEO\Plugin\Common\Models\Post' ) ) {
			$post                  = AIOSEO\Plugin\Common\Models\Post::getPost( $product->get_id() );
			$aioseop_canonical_url = $post->canonical_url;
		}

		return apply_filters( 'woo_feed_filter_product_aioseop_canonical_url', $aioseop_canonical_url, $product, $this->config );
	}

	/**
	 * Get Yoast Product Title
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function yoast_wpseo_title( $product ) {
		$product_id  = woo_feed_parent_product_id( $product );
		$yoast_title = get_post_meta( $product_id, '_yoast_wpseo_title', true );
		if ( strpos( $yoast_title, '%%' ) !== false ) {
			$title = strstr( $yoast_title, '%%', true );
			if ( empty( $title ) ) {
				$title = get_the_title( $product_id );
			}
			$wpseo_titles = get_option( 'wpseo_titles' );

			$sep_options = WPSEO_Option_Titles::get_instance()->get_separator_options();
			if ( isset( $wpseo_titles['separator'] ) && isset( $sep_options[ $wpseo_titles['separator'] ] ) ) {
				$sep = $sep_options[ $wpseo_titles['separator'] ];
			} else {
				$sep = '-'; //setting default separator if Admin didn't set it from backed
			}

			$site_title = get_bloginfo( 'name' );

			$meta_title = $title . ' ' . $sep . ' ' . $site_title;

			if ( ! empty( $meta_title ) ) {
				$title = $meta_title;
			}
		} elseif ( ! empty( $yoast_title ) ) {
			$title = $yoast_title;
		} else {
			$title = $this->title( $product );
		}

		return apply_filters( 'woo_feed_filter_product_yoast_wpseo_title', $title, $product, $this->config );
	}

	/**
	 * Get Yoast Canonical URL
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.3.77
	 */
	protected function yoast_canonical_url( $product ) {
		$product_id          = woo_feed_parent_product_id( $product );
		$yoast_canonical_url = get_post_meta( $product_id, '_yoast_wpseo_canonical', true );

		return apply_filters( 'woo_feed_filter_product_yoast_canonical_url', $yoast_canonical_url, $product, $this->config );
	}

	/**
	 * Get Rank Math Product Title
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 5.1.3
	 */
	protected function rank_math_title( $product ) {
		$rank_title = '';
		$post_title = '';
		$page       = '';
		$sep        = '';
		$sitemap    = '';
		if ( class_exists( 'RankMath' ) ) {
			$title = get_post_meta( $product->get_id(), 'rank_math_title', true );
			if ( empty( $title ) ) {
				$title_format = Helper::get_settings( "titles.pt_product_title" );
				$title_format = $title_format ? $title_format : '%title%';
				$sep          = Helper::get_settings( 'titles.title_separator' );

				$rank_title = str_replace( '%title%', $product->get_title(), $title_format );
				$rank_title = str_replace( '%sep%', $sep, $rank_title );
				$rank_title = str_replace( '%page%', '', $rank_title );
				$rank_title = str_replace( '%sitename%', get_bloginfo( 'name' ), $rank_title );
			} else {
				$rank_title = $title;
			}
		}

		return apply_filters( 'woo_feed_filter_product_rank_math_title', $rank_title, $product, $this->config );
	}

	/**
	 * Get Rank Math Product Description
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 5.1.3
	 */
	protected function rank_math_description( $product ) {
		$description = '';
		if ( class_exists( 'RankMath' ) ) {
			$description = get_post_meta( $product->get_id(), 'rank_math_description' );
			$desc_format = Helper::get_settings( "titles.pt_post_description" );

			if ( empty( $description ) ) {
				if ( ! empty( $desc_format ) && strpos( (string) $desc_format, 'excerpt' ) !== false ) {
					$description = str_replace( '%excerpt%', get_the_excerpt( $product->get_id() ), $desc_format );
				}

				// Get Variation Description
				if ( $product->is_type( 'variation' ) && empty( $description ) ) {
					$parent      = wc_get_product( $product->get_parent_id() );
					$description = $parent->get_description();
				}
			}

			if ( is_array( $description ) ) {
				$description = reset( $description );
			}

			$description = $this->remove_short_codes( $description );

			//strip tags and spacial characters
			$strip_description = woo_feed_strip_all_tags( wp_specialchars_decode( $description ) );

			$description = ! empty( strlen( $strip_description ) ) && 0 < strlen( $strip_description ) ? $strip_description : $description;
		}

		return apply_filters( 'woo_feed_filter_product_rank_math_description', $description, $product, $this->config );
	}

	/**
	 * Get Rank Math Canonical URL
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 5.1.6
	 */
	protected function rank_math_canonical_url( $product ) {
		$canonical_url = '';

		if ( class_exists( 'RankMath' ) ) {
			$post_canonical_url = get_post_meta( $product->get_id(), 'rank_math_canonical_url' );

			if ( empty( $post_canonical_url ) ) {
				$canonical_url = get_the_permalink( $product->get_id() );
			} else {
				$canonical_url = $post_canonical_url;
			}

			if ( is_array( $canonical_url ) ) {
				$canonical_url = reset( $canonical_url );
			}
		}

		return apply_filters( 'woo_feed_filter_product_rank_math_canonical_url', $canonical_url, $product, $this->config );
	}


	/**
	 * Get All In One Product Title
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function _aioseop_title( $product ) {
		$title = '';
		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && class_exists( 'AIOSEO\Plugin\Common\Models\Post' ) ) {

			$post  = AIOSEO\Plugin\Common\Models\Post::getPost( $product->get_id() );
			$title = ! empty( $post->title ) ? $post->title : aioseo()->meta->title->getPostTypeTitle( 'product' );
		}

		$title = ! empty( $title ) ? $title : $this->title( $product );

		return apply_filters( 'woo_feed_filter_product_aioseop_title', $title, $product, $this->config );
	}

	/**
	 * Get Product Description
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product Product
	 *                                                                                                                                 Object.
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	protected function description( $product ) {
		$description = $product->get_description();

		// Get Variation Description
		if ( $product->is_type( 'variation' ) && empty( $description ) ) {
			$parent = wc_get_product( $product->get_parent_id() );

			if ( is_object( $parent ) ) {
				$description = $parent->get_description();
			} else {
				$description = '';
			}
		}
		$description = $this->remove_short_codes( $description );



		// Add variations attributes after description to prevent Facebook error
		if ( 'facebook' == $this->config['provider'] && $product->is_type( 'variation' ) ) {
			$variationInfo = explode( '-', $product->get_name() );
			if ( isset( $variationInfo[1] ) ) {
				$extension = $variationInfo[1];
			} else {
				$extension = $product->get_id();
			}
			$description .= ' ' . $extension;
		}

		//strip tags and spacial characters
		$strip_description = woo_feed_strip_all_tags( wp_specialchars_decode( $description ) );

		$description = ! empty( strlen( $strip_description ) ) && 0 < strlen( $strip_description ) ? $strip_description : $description;
        if( $this->config['provider'] == "admarkt" && $this->config['feedType'] == 'xml' ) {
            $description = str_replace('&', '&amp;', $description);
        }

		return apply_filters( 'woo_feed_filter_product_description', $description, $product, $this->config );
	}

	/**
	 * Get Product Description (with HTML)
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product Product
	 *                                                                                                                                 Object.
	 *
	 * @return mixed|string
	 * @since 5.2.41
	 */
	protected function description_with_html( $product ) {
		$description = $product->get_description();

		// Get Variation Description
		if ( $product->is_type( 'variation' ) && empty( $description ) ) {
			$parent      = wc_get_product( $product->get_parent_id() );
			$description = $parent->get_description();
		}
		$description = $this->remove_short_codes( $description );

		// Add variations attributes after description to prevent Facebook error
		if ( 'facebook' === $this->config['provider'] && $product->is_type( 'variation' ) ) {
			$variationInfo = explode( '-', $product->get_name() );
			if ( isset( $variationInfo[1] ) ) {
				$extension = $variationInfo[1];
			} else {
				$extension = $product->get_id();
			}
			$description .= ' ' . $extension;
		}

		//remove spacial characters
		$strip_description = wp_check_invalid_utf8( wp_specialchars_decode( $description ), true );

		return apply_filters( 'woo_feed_filter_product_description_with_html', $description, $product, $this->config );
	}

	/**
	 * Get Yoast Product Description
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function yoast_wpseo_metadesc( $product ) {
		$product_id  = woo_feed_parent_product_id( $product );
		$description = '';
		if ( class_exists( 'WPSEO_Frontend' ) ) {
			$description = wpseo_replace_vars( WPSEO_Meta::get_value( 'metadesc', $product_id ),
				get_post( $product->get_id() ) );
		} else {
			$description = YoastSEO()->meta->for_post( $product_id )->description;
		}

		if ( empty( $description ) ) {
			$description = $this->description( $product );
		}

		return apply_filters( 'woo_feed_filter_product_yoast_wpseo_metadesc', $description, $product, $this->config );
	}

	/**
	 * Get All In One Product Description
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function _aioseop_description( $product ) {
		$description = '';

		if ( is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) && class_exists( 'AIOSEO\Plugin\Common\Models\Post' ) ) {

			$post        = AIOSEO\Plugin\Common\Models\Post::getPost( $product->get_id() );
			$description = ! empty( $post->description ) ? $post->description : aioseo()->meta->description->getPostTypeDescription( 'product' );
		}

		if ( empty( $description ) ) {
			$description = $this->description( $product );
		}

		return apply_filters( 'woo_feed_filter_product_aioseop_description', $description, $product, $this->config );
	}

	/**
	 * Get Product Short Description
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	protected function short_description( $product ) {
		$short_description = $product->get_short_description();

		// Get Variation Short Description
		if ( $product->is_type( 'variation' ) && empty( $short_description ) ) {
			$parent            = wc_get_product( $product->get_parent_id() );
			$short_description = $parent->get_short_description();
		}


		$short_description = $this->remove_short_codes( $short_description );

		//strip tags and spacial characters
		$short_description = woo_feed_strip_all_tags( wp_specialchars_decode( $short_description ) );

		return apply_filters( 'woo_feed_filter_product_short_description', $short_description, $product, $this->config );
	}


	/**
	 * At First convert Short Codes and then Remove failed Short Codes from String
	 *
	 * @param $content
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	protected function remove_short_codes( $content ) {
		if ( $content == '' ) {
			return '';
		}

		$content = do_shortcode( $content );

		$content = woo_feed_stripInvalidXml( $content );

		// Covers all kinds of shortcodes

//        $expression = '/\[\/*[a-zA-Z1-90_| -=\'"\{\}]*\/*\]/m';
        $expression = '/\[\/*[č?a-zA-Z1-90_| -=\'"\{\}]*\/*\]/m';

		$content = preg_replace( $expression, '', $content );

		return strip_shortcodes( $content );
	}

	/*
     * Retrieves product's parent category
     *
     * @param $product A Post/Product object
     *
     * @return WP_Term
     * @since 4.4.19
     * */
	protected function product_parent_category_object( $product ) {
		global $parent_category_memo;
		if ( empty( $parent_category_memo ) ) {
			$parent_category_memo = [];
		}

		$id = $product->get_id();
		if ( $product->is_type( 'variation' ) ) {
			$id = $product->get_parent_id();
		}

		$taxonomy       = 'product_cat';
		$categories     = wp_get_post_terms( $id, $taxonomy );
		$child_category = $categories[ count( $categories ) - 1 ]; // Last selected category

		$parent_category = '';

		// Memoization for faster parent retrieval
		if (
			isset( $parent_category_memo[ $child_category->term_id ] )
			&& ! empty( $parent_category_memo[ $child_category->term_id ] )
		) {
			$parent_category = get_term_by( 'term_id', $parent_category_memo[ $child_category->term_id ], $taxonomy );
		} else {
			$parent_category                                  = woo_feed_parent_category( $child_category, $taxonomy );
			$parent_category_memo[ $child_category->term_id ] = $parent_category->term_id;
		}

		// Yoast SEO primary term fetching
		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $id );
			$parent_category_id = $wpseo_primary_term->get_primary_term();
			if ( ! empty( $parent_category_id ) ) {
				$parent_category = get_term_by( 'term_id', $parent_category_id, $taxonomy );
			}
		}

		return $parent_category;
	}

	/**
	 * Get Product Parent/Main Category
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 *
	 */
	protected function primary_category( $product ) {
		$parent_category = "";
		$separator       = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $product );
		$full_category   = $this->product_type( $product );
		if ( ! empty( $full_category ) ) {
			$full_category_array = explode( $separator, $full_category );
			$parent_category     = $full_category_array[0];
		}

		return apply_filters( 'woo_feed_filter_product_primary_category', $parent_category, $product, $this->config );
	}

	/**
	 * Get Product Parent/Main Category ID
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 *
	 */
	protected function primary_category_id( $product ) {
		$parent_category_id = "";
		$separator          = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $product );
		$full_category      = $this->product_type( $product );
		if ( ! empty( $full_category ) ) {
			$full_category_array = explode( $separator, $full_category );
			$parent_category_obj = get_term_by( 'name', $full_category_array[0], 'product_cat' );
			$parent_category_id  = isset( $parent_category_obj->term_id ) ? $parent_category_obj->term_id : "";
		}

		return apply_filters( 'woo_feed_filter_product_primary_category_id', $parent_category_id, $product, $this->config );
	}

	/**
	 * Get Product Child Category
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	protected function child_category( $product ) {
		$child_category = "";
		$separator      = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $product );
		$full_category  = $this->product_type( $product );
		if ( ! empty( $full_category ) ) {
			$full_category_array = explode( $separator, $full_category );
			$child_category      = end( $full_category_array );
		}

		return apply_filters( 'woo_feed_filter_product_child_category', $child_category, $product, $this->config );
	}

	/**
	 * Get Product Child Category ID
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	protected function child_category_id( $product ) {
		$child_category_id = "";
		$separator         = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $product );
		$full_category     = $this->product_type( $product );
		if ( ! empty( $full_category ) ) {
			$full_category_array = explode( $separator, $full_category );
			$child_category_obj  = get_term_by( 'name', end( $full_category_array ), 'product_cat' );
			$child_category_id   = isset( $child_category_obj->term_id ) ? $child_category_obj->term_id : "";
		}

		return apply_filters( 'woo_feed_filter_product_child_category_id', $child_category_id, $product, $this->config );
	}

	/**
	 * Get Product Categories
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function product_type( $product ) {
		$id = $product->get_id();
		if ( $product->is_type( 'variation' ) ) {
			$id = $product->get_parent_id();
		}

		$separator = apply_filters( 'woo_feed_product_type_separator', ' > ', $this->config, $product );

		$product_type = woo_feed_strip_all_tags( wc_get_product_category_list( $id, $separator ) );

		$product_categories = '';
		$term_list          = get_the_terms( $id, 'product_cat' );

		if ( is_array( $term_list ) ) {
			$col = array_column( $term_list, "term_id" );
			array_multisort( $col, SORT_ASC, $term_list );
			$term_list          = array_column( $term_list, "name" );
			$product_categories = implode( $separator, $term_list );
		}


		return apply_filters( 'woo_feed_filter_product_local_category', $product_categories, $product, $this->config );
	}

	/**
	 * @param \WC_Product $product
	 *
	 * @return void
	 */
	public function product_status( $product ) {
		$status = $product->get_status();

		return apply_filters( 'woo_feed_filter_product_status', $status, $product, $this->config );
	}

	/**
	 * @param \WC_Product $product
	 *
	 * @return void
	 */
	public function featured_status( $product ) {
		$status = $product->is_featured() ? 'yes' : 'no';

		return apply_filters( 'woo_feed_filter_featured_status', $status, $product, $this->config );
	}

	/**
	 * Get Product Full Categories with unselected category parent
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since 4.3.47
	 */
	protected function product_full_cat( $product ) {
        $id = $product->get_id();
        if ($product->is_type('variation')) {
            $id = $product->get_parent_id();
        }
//
//        $separator = apply_filters('woo_feed_product_type_separator', '>', $this->config, $product);
//
//        $product_type = woo_feed_get_terms_list_hierarchical_order($id);

//		$product_type = $this->product_type( $product );

		$product_categories = wp_get_post_terms($id, 'product_cat');

		// Check if categories exist
		if (!empty($product_categories)) {
			$category_names = array();

			// Loop through the categories and store their names
			foreach ($product_categories as $category) {
				$category_names[] = $category->name;

				// Check if the category has a parent
				if ($category->parent > 0) {
					$parent_category = get_term($category->parent, 'product_cat');
					$category_names[] = $parent_category->name;
				}
			}

			// Reverse the category names array to get the correct order
			$category_names = array_unique(array_reverse($category_names));

			// Get the full category path
			$full_category = implode(' > ', $category_names);

			// Output the full category
			return $full_category;
		}

		//return apply_filters( 'woo_feed_filter_product_local_category', $product_type, $product, $this->config );
	}

	/**
	 * Get Product URL
	 *
	 * @param WC_Product|WC_Product_Variation $product
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function link( $product ) {

		$link = $product->get_permalink();

		$link = $this->add_utm_tracker( $link );

		return apply_filters( 'woo_feed_filter_product_link', $link, $product, $this->config );
	}

	/**
	 * Adding UTM Tracker Query Arguments to URL
	 *
	 * @param string $url
	 *
	 * @return string $url
	 * @since 4.4.26
	 */
	protected function add_utm_tracker( $url ) {

		$utm = $this->config['campaign_parameters'];

		if ( ! empty( $utm['utm_source'] ) && ! empty( $utm['utm_medium'] ) && ! empty( $utm['utm_campaign'] ) ) {

			$utm = [
				'utm_source'   => str_replace( ' ', '+', $utm['utm_source'] ),
				'utm_medium'   => str_replace( ' ', '+', $utm['utm_medium'] ),
				'utm_campaign' => str_replace( ' ', '+', $utm['utm_campaign'] ),
				'utm_term'     => str_replace( ' ', '+', $utm['utm_term'] ),
				'utm_content'  => str_replace( ' ', '+', $utm['utm_content'] ),
			];

			$url = add_query_arg( array_filter( $utm ), $url );

		}

		return $url;
	}

	/**
	 * Get Parent Product URL
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since  5.1.8
	 * @author Nazrul Islam Nayan
	 */
	protected function parent_link( $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$product = wc_get_product( $product->get_parent_id() );
			$link    = $this->link( $product );
		} else {
			$link = $this->link( $product );
		}

		return apply_filters( 'woo_feed_filter_product_parent_link', $link, $product, $this->config );
	}

	/**
	 * Get Canonical Link
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 */
	protected function canonical_link( $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$product        = wc_get_product( $product->get_parent_id() );
			$canonical_link = $product->get_permalink();
		} else {
			$canonical_link = $product->get_permalink();
		}

		return apply_filters( 'woo_feed_filter_product_canonical_link', $canonical_link, $product, $this->config );
	}

	/**
	 * Get External Product URL
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function ex_link( $product ) {
		if ( $product->is_type( 'external' ) ) {
			$utm = $this->config['campaign_parameters'];
			if ( ! empty( $utm['utm_source'] ) && ! empty( $utm['utm_medium'] ) && ! empty( $utm['utm_campaign'] ) ) {
				$utm = [
					'utm_source'   => str_replace( ' ', '+', $utm['utm_source'] ),
					'utm_medium'   => str_replace( ' ', '+', $utm['utm_medium'] ),
					'utm_campaign' => str_replace( ' ', '+', $utm['utm_campaign'] ),
					'utm_term'     => str_replace( ' ', '+', $utm['utm_term'] ),
					'utm_content'  => str_replace( ' ', '+', $utm['utm_content'] ),
				];

				return add_query_arg( array_filter( $utm ), $product->get_product_url() );
			} else {
				$ex_link = $product->get_product_url();
			}
		} else {
			$ex_link = '';
		}

		return apply_filters( 'woo_feed_filter_product_ex_link', $ex_link, $product, $this->config );
	}

	/**
	 * Get Product Image
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product Product
	 *                                                                                                                                 Object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function image( $product ) {
		$image = '';
		if ( $product->is_type( 'variation' ) ) {
			// Variation product type
			if ( has_post_thumbnail( $product->get_id() ) ) {
				$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
				$image    = woo_feed_get_formatted_url( $getImage[0] );
			} elseif ( has_post_thumbnail( $product->get_parent_id() ) ) {
				$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_parent_id() ), 'single-post-thumbnail' );
				$image    = woo_feed_get_formatted_url( $getImage[0] );
			}
		} elseif ( has_post_thumbnail( $product->get_id() ) ) { // All product type except variation
			$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
			$image    = isset( $getImage[0] ) ? woo_feed_get_formatted_url( $getImage[0] ) : '';
		}

		return apply_filters( 'woo_feed_filter_product_image', $image, $product, $this->config );
	}

	/**
	 * Get Product Featured Image. For product variations it will return the variable product image.
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product Product
	 *                                                                                                                                 Object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function feature_image( $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$id = $product->get_parent_id();
		} else {
			$id = $product->get_id();
		}

		$getImage = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
		$image    = isset( $getImage[0] ) ? woo_feed_get_formatted_url( $getImage[0] ) : '';

		return apply_filters( 'woo_feed_filter_product_feature_image', $image, $product, $this->config );
	}

	/**
	 * Get Comma Separated Product Images
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product       Product
	 *                                                                                                                                       Object.
	 * @param string                                                                                                          $additionalImg Specific
	 *                                                                                                                                       Additional
	 *                                                                                                                                       Image.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function images( $product, $additionalImg = '' ) {
		$imgUrls   = $this->get_product_gallery( $product );
		$separator = ',';

		// Return Specific Additional Image URL
		if ( '' !== $additionalImg ) {
			if ( array_key_exists( $additionalImg, $imgUrls ) ) {
				$images = $imgUrls[ $additionalImg ];
			} else {
				$images = '';
			}
		} else {
			if ( isset( $this->config['provider'] ) && "idealo" === $this->config['provider'] ) {
				$separator = ';';
			}

			$images = implode( $separator, array_filter( $imgUrls ) );
		}

		return apply_filters( 'woo_feed_filter_product_images', $images, $product, $this->config );
	}

	/**
	 * Get Product Gallery Items (URL) array.
	 * This can contains empty array values
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product
	 *
	 * @return string[]
	 * @since 3.2.6
	 */
	protected function get_product_gallery( $product ) {
		$imgUrls       = [];
		$attachmentIds = [];

		if ( $product->is_type( 'variation' ) ) {
			if ( class_exists( 'Woo_Variation_Gallery' ) ) {
				/**
				 * Get Variation Additional Images for "Additional Variation Images Gallery for WooCommerce"
				 *
				 * @plugin Additional Variation Images Gallery for WooCommerce
				 * @link   https://wordpress.org/plugins/woo-variation-gallery/
				 */
				$attachmentIds = get_post_meta( $product->get_id(), 'woo_variation_gallery_images', true );
			} elseif ( class_exists( 'WooProductVariationGallery' ) ) {
				/**
				 * Get Variation Additional Images for "Variation Images Gallery for WooCommerce"
				 *
				 * @plugin Variation Images Gallery for WooCommerce
				 * @link   https://wordpress.org/plugins/woo-product-variation-gallery/
				 */
				$attachmentIds = get_post_meta( $product->get_id(), 'rtwpvg_images', true );
			} elseif ( class_exists( 'WC_Additional_Variation_Images' ) ) {
				/**
				 * Get Variation Additional Images for "WooCommerce Additional Variation Images"
				 *
				 * @plugin WooCommerce Additional Variation Images
				 * @link   https://woocommerce.com/products/woocommerce-additional-variation-images/
				 */
				$attachmentIds = explode( ',', get_post_meta( $product->get_id(), '_wc_additional_variation_images', true ) );
			} elseif ( class_exists( 'WOODMART_Theme' ) ) {
				/**
				 * Get Variation Additional Images for "WOODMART Theme -> Variation Gallery Images Feature"
				 *
				 * @theme WOODMART
				 * @link  https://themeforest.net/item/woodmart-woocommerce-wordpress-theme/20264492
				 */
				$var_id    = $product->get_id();
				$parent_id = $product->get_parent_id();

				$variation_obj = get_post_meta( $parent_id, 'woodmart_variation_gallery_data', true );
				if ( isset( $variation_obj ) && isset( $variation_obj[ $var_id ] ) ) {
					$attachmentIds = explode( ',', $variation_obj[ $var_id ] );
				} else {
					$attachmentIds = explode( ',', get_post_meta( $var_id, 'wd_additional_variation_images_data', true ) );
				}
			} else {
				/**
				 * If any Variation Gallery Image plugin not installed then get Variable Product Additional Image Ids .
				 */
				$attachmentIds = wc_get_product( $product->get_parent_id() )->get_gallery_image_ids();
			}
		}

		/**
		 * Get Variable Product Gallery Image ids if Product is not a variation
		 * or variation does not have any gallery images
		 */
		if ( empty( $attachmentIds ) ) {
			$attachmentIds = $product->get_gallery_image_ids();
		}

		if ( $attachmentIds && is_array( $attachmentIds ) ) {
			$mKey = 1;
			foreach ( $attachmentIds as $attachmentId ) {
				$imgUrls[ $mKey ] = woo_feed_get_formatted_url( wp_get_attachment_url( $attachmentId ) );
				$mKey ++;
			}
		}

		return $imgUrls;
	}

	/**
	 * Get Product Condition
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function condition( $product ) {
		return apply_filters( 'woo_feed_product_condition', 'new', $product );
	}

	/**
	 *  Get Product Type
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function type( $product ) {
		return apply_filters( 'woo_feed_filter_product_type', $product->get_type(), $product, $this->config );
	}

	/**
	 *  Get Product is a bundle product or not
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function is_bundle( $product ) {
		if ( $product->is_type( 'bundle' ) || $product->is_type( 'yith_bundle' ) ) {
			$is_bundle = 'yes';
		} else {
			$is_bundle = 'no';
		}

		return apply_filters( 'woo_feed_filter_product_is_bundle', $is_bundle, $product, $this->config );
	}

	/**
	 *  Get Product is a multi-pack product or not
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function multipack( $product ) {
		$multi_pack = '';
		if ( $product->is_type( 'grouped' ) ) {
			$multi_pack = ( ! empty( $product->get_children() ) ) ? count( $product->get_children() ) : '';
		}

		return $multi_pack;
	}

	/**
	 *  Get Product visibility status
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function visibility( $product ) {
		return apply_filters( 'woo_feed_filter_product_visibility', $product->get_catalog_visibility(), $product, $this->config );
	}

	/**
	 *  Get Product Total Rating
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function rating_total( $product ) {
		return apply_filters( 'woo_feed_filter_product_rating_total', $product->get_rating_count(), $product, $this->config );
	}

	/**
	 * Get Product average rating
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function rating_average( $product ) {
		return apply_filters( 'woo_feed_filter_product_rating_average', $product->get_average_rating(), $product, $this->config );
	}

	/**
	 * Get Product tags
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function tags( $product ) {
		$id = $product->get_id();
		if ( $product->is_type( 'variation' ) ) {
			$id = $product->get_parent_id();
		}

		/**
		 * Separator for multiple tags
		 *
		 * @param string                     $separator
		 * @param array                      $config
		 * @param WC_Abstract_Legacy_Product $product
		 *
		 * @since 3.4.3
		 */
		$separator = apply_filters( 'woo_feed_tags_separator', ',', $this->config, $product );

		$tags = woo_feed_strip_all_tags( get_the_term_list( $id, 'product_tag', '', $separator, '' ) );

		return apply_filters( 'woo_feed_filter_product_tags', $tags, $product, $this->config );
	}

	/**
	 * Get Product Parent Id
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function item_group_id( $product ) {
		$id = $product->get_id();
		if ( $product->is_type( 'variation' ) ) {
			$id = $product->get_parent_id();
		}

		return apply_filters( 'woo_feed_filter_product_item_group_id', $id, $product, $this->config );
	}

	/**
	 * Get Product SKU
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function sku( $product ) {
		return apply_filters( 'woo_feed_filter_product_sku', $product->get_sku(), $product, $this->config );
	}

	/**
	 * Get Product SKU ID. It should come with after merging of sku and product id with '_' sign.
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since  4.3.13
	 * @author Nazrul Islam Nayan
	 */
	protected function sku_id( $product ) {
		$sku    = ! empty( $product->get_sku() ) ? $product->get_sku() . '_' : '';
		$sku_id = $sku . $product->get_id();

		return apply_filters( 'woo_feed_filter_product_sku_id', $sku_id, $product, $this->config );
	}

	/**
	 * Get Product Parent SKU
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function parent_sku( $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$id     = $product->get_parent_id();
			$parent = wc_get_product( $id );

			$parent_sku = $parent->get_sku();
		} else {
			$parent_sku = $product->get_sku();
		}

		return apply_filters( 'woo_feed_filter_product_parent_sku', $parent_sku, $product, $this->config );
	}

	/**
	 * Get Product Availability Status
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function availability( $product ) {
		$status = $product->get_stock_status();
		if ( 'instock' == $status ) {
			$status = 'in stock';
		} elseif ( 'outofstock' == $status ) {
			$status = 'out of stock';
		} elseif ( 'onbackorder' == $status ) {
			$status = 'on backorder';
		}

		// set (_) as separator for google merchant
		if ( isset( $this->config['provider'] ) && 'google' === $this->config['provider'] ) {
			$status = explode( ' ', $status );
			$status = implode( '_', $status );
		}

		return apply_filters( 'woo_feed_filter_product_availability', $status, $product, $this->config );
	}

	/**
	 * Get Product Availability Date
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since  3.2.0
	 *
	 * @author Ohidul Islam
	 */
	protected function availability_date( $product ) {

		$feed_settings = get_option( 'woo_feed_settings' );

		$availability_date_settings = isset( $feed_settings['woo_feed_identifier']['availability_date'] )
			? $feed_settings['woo_feed_identifier']['availability_date']
			: 'enable';

		if ( $product->get_stock_status() !== 'onbackorder' || $availability_date_settings === 'disable' ) {
			return '';
		}

		$meta_field_name = 'woo_feed_availability_date';

		if ( $product->is_type( 'variation' ) ) {
			$meta_field_name .= '_var';
		}

		$availability_date = get_post_meta( $product->get_id(), $meta_field_name, true );

		if ( '' !== $availability_date && in_array( $this->config['provider'], [
				'google',
				'facebook',
				'pinterest',
				'bing',
				'snapchat',
			], true ) ) {
			$availability_date = gmdate( 'c', strtotime( $availability_date ) );
		}

		return apply_filters( 'woo_feed_filter_product_availability_date', $availability_date, $product, $this->config );
	}

	/**
	 * Get Product Add to Cart Link
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since  5.1.8
	 * @author Nazrul Islam Nayan
	 */
	protected function add_to_cart_link( $product ) {
		$url    = $this->link( $product );
		$suffix = 'add-to-cart=' . $product->get_id();

		$add_to_cart_link = woo_feed_make_url_with_parameter( $url, $suffix );

		return apply_filters( 'woo_feed_filter_product_add_to_cart_link', $add_to_cart_link, $product, $this->config );
	}

	/**
	 * Get Product Quantity
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function quantity( $product ) {
		$quantity = $product->get_stock_quantity();
		$status   = $product->get_stock_status();

		//when product is outofstock and it's quantity is empty, set quantity to 0
		if ( 'outofstock' == $status && empty( $quantity ) ) {
			$quantity = 0;
		}

		if ( $product->is_type( 'variable' ) && $product->has_child() ) {
			$visible_children = $product->get_visible_children();
			$qty              = array();
			foreach ( $visible_children as $key => $child ) {
				$childQty = get_post_meta( $child, '_stock', true );
				$qty[]    = (int) $childQty + 0;
			}

			if ( isset( $this->config['variable_quantity'] ) ) {
				$vaQty = $this->config['variable_quantity'];
				if ( 'max' == $vaQty ) {
					$quantity = max( $qty );
				} elseif ( 'min' == $vaQty ) {
					$quantity = min( $qty );
				} elseif ( 'sum' == $vaQty ) {
					$quantity = array_sum( $qty );
				} elseif ( 'first' == $vaQty ) {
					$quantity = ( (int) $qty[0] );
				}

				$quantity = array_sum( $qty );
			}
		}

		return apply_filters( 'woo_feed_filter_product_quantity', $quantity, $product, $this->config );
	}

	/**
	 * Get Product Currency
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.39
	 *
	 */
	protected function currency( $product ) {
		$quantity = get_option( 'woocommerce_currency' );

		return apply_filters( 'woo_feed_filter_product_currency', $quantity, $product, $this->config );
	}

	/**
	 * Get Store currency code.
	 *
	 * @param WC_Product $product Product Object
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 */
	protected function store_currency( $product ) {
		return get_woocommerce_currency();
	}

	/**
	 * Get Product Sale Price Start Date
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function sale_price_sdate( $product ) {
		$startDate = $product->get_date_on_sale_from();
		if ( is_object( $startDate ) ) {
			$sale_price_sdate = $startDate->date_i18n();
		} else {
			$sale_price_sdate = '';
		}

		return apply_filters( 'woo_feed_filter_product_sale_price_sdate', $sale_price_sdate, $product, $this->config );
	}

	/**
	 * Get Product Sale Price End Date
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function sale_price_edate( $product ) {
		$endDate = $product->get_date_on_sale_to();
		if ( is_object( $endDate ) ) {
			$sale_price_edate = $endDate->date_i18n();
		} else {
			$sale_price_edate = "";
		}

		return apply_filters( 'woo_feed_filter_product_sale_price_edate', $sale_price_edate, $product, $this->config );
	}

	/**
	 * Get feed currency
	 *
	 * @return mixed|string
	 */
	protected function get_feed_currency() {
		$currency = get_woocommerce_currency();
		if ( isset( $this->config['feedCurrency'] ) ) {
			$currency = $this->config['feedCurrency'];
		}

		return $currency;
	}

	/**
	 * Get Product Price by default WooCommerce Price Type (regular_price|price|sale_price).
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product    Product Object
	 * @param string                                            $price_type Price Type (regular_price|price|sale_price)
	 * @param bool                                              $tax        Add tax with price (true|false)
	 *
	 * @return float|mixed|string|void
	 * @since  4.4.14
	 *
	 * @author Ohidul Islam
	 */
	protected function get_price_by_price_type( $product, $price_type, $tax ) {

		if ( 'regular_price' === $price_type ) {
			$price = $product->get_regular_price();
		} elseif ( 'price' === $price_type ) {
			$price = $product->get_price();
		} else {
			$price = $product->get_sale_price();
		}

		// Get WooCommerce Multi language Price by Currency.
		$price = apply_filters( 'woo_feed_wcml_price',
			$price, $product->get_id(), $this->get_feed_currency(), '_' . $price_type
		);

		// Get Price with tax
		if ( true === $tax ) {
			$price = woo_feed_get_price_with_tax( $price, $product );
		}

		return $price;
	}

	/**
	 * Get Product Price by Product Type.
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product    Product Object
	 * @param string                                            $price_type Price Type (regular_price|price|sale_price)
	 * @param bool                                              $tax        Add tax with price (true|false)
	 *
	 * @return float|int|mixed|string
	 * @since  4.4.14
	 *
	 * @author Ohidul Islam
	 */
	protected function get_price_by_product_type( $product, $price_type, $tax = false ) {
		if ( $product->is_type( 'variable' ) ) {
			$price = $this->getVariableProductPrice( $product, $price_type, $tax );
		} elseif ( $product->is_type( 'grouped' ) ) {
			$price = $this->getGroupProductPrice( $product, $price_type, $tax );
		} elseif ( $product->is_type( 'bundle' ) ) {
			//TODO Diff taxation
			$price = $this->getBundleProductPrice( $product, $price_type, $tax );
		} elseif ( $product->is_type( 'composite' ) ) {
			//TODO Diff taxation
			$price = $this->getCompositeProductPrice( $product, $price_type, $tax );
		} elseif ( $product->is_type( 'bundled' ) ) {
			// iconic woocommerce product bundled plugin
			$price = $this->iconic_bundle_product_price( $product, $price_type, $tax );
		} else {
			$price = $this->get_price_by_price_type( $product, $price_type, $tax );
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get Product Regular Price
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product Product Object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function price( $product ) {

		$regular_price = $this->get_price_by_product_type( $product, 'regular_price' );

		return apply_filters( 'woo_feed_filter_product_price', $regular_price, $product, $this->config, false, 'regular_price' );
	}

	/**
	 * Get Product Price
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product Product object.
	 *
	 * @return int|float|mixed
	 * @since 3.2.0
	 *
	 */
	protected function current_price( $product ) {

		$price = $this->get_price_by_product_type( $product, 'price' );
		return apply_filters( 'woo_feed_filter_product_regular_price', $price, $product, $this->config, false , 'price' );
	}

	/**
	 * Get Product Sale Price
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product Product object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function sale_price( $product ) {

		$sale_price = $this->get_price_by_product_type( $product, 'sale_price' );

		return apply_filters( 'woo_feed_filter_product_sale_price', $sale_price, $product, $this->config, false, 'sale_price' );
	}

	/**
	 * Get Product Regular Price with Tax
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product Product Object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function price_with_tax( $product ) {

		$regular_price_with_tax = $this->get_price_by_product_type( $product, 'regular_price', true );

		return apply_filters( 'woo_feed_filter_product_price_with_tax', $regular_price_with_tax, $product, $this->config, true, 'regular_price' );
	}

	/**
	 * Get Product Regular Price with Tax
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product Product object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function current_price_with_tax( $product ) {

		$price_with_tax = $this->get_price_by_product_type( $product, 'price', true );

		return apply_filters( 'woo_feed_filter_product_regular_price_with_tax', $price_with_tax, $product, $this->config, true, 'price' );
	}

	/**
	 * Get Product Regular Price with Tax
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Grouped $product Product object.
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function sale_price_with_tax( $product ) {

		$sale_price_with_tax = $this->get_price_by_product_type( $product, 'sale_price', true );

		return apply_filters( 'woo_feed_filter_product_sale_price_with_tax', $sale_price_with_tax, $product, $this->config, true, 'sale_price' );
	}

	/**
	 * Get Composite Product Price
	 *
	 * @param WC_Product|WC_Product_Composite|WC_Composite_Products $product Composite Product object.
	 * @param string                                                $type    Price Type (regular_price|price|sale_price)
	 * @param bool                                                  $tax     Add tax with price (true|false)
	 *
	 * @return int|float|double
	 * @since 3.2.6
	 */
	protected function getCompositeProductPrice( $product, $type, $tax = false ) {
		if ( class_exists( 'WC_Product_Composite' ) && class_exists( 'WPCleverWooco' ) ) {
			// WPC Composite Product
			return $this->get_wpc_composite_price( $product, $type, $tax );
		} elseif ( class_exists( 'WC_Composite_Products' ) ) {
			// WooCommerce Composite Product
			return $this->get_wc_composite_product_price( $product, $type, $tax );
		} else {
			// Get Base Price for Others Composite Products.
			// Note*: YITH does not auto select components. So no need to calculate component price.
			return $this->get_price_by_price_type( $product, $type, $tax );
		}
	}

	/**
	 * Get WooCommerce Composite Product Price
	 * Plugin URL: https://wordpress.org/plugins/wpc-composite-products/
	 * Product Type: composite
	 *
	 * @param WC_Product|WC_Product_Composite $product
	 * @param string                          $type Price Type (regular_price|price|sale_price)
	 * @param bool                            $tax  Add tax with price (true|false)
	 *
	 * @return float|int|mixed|string|void
	 */
	protected function get_wc_composite_product_price( $product, $type, $tax ) {
		$price = 0;
		// Parent Component Price
		$base_price = $this->get_price_by_price_type( $product, $type, $tax );
		if ( isset( $this->config['composite_price'] ) && 'all_product_price' == $this->config['composite_price'] ) {
			$composite = new WC_Product_Composite( $product );

			if ( 'price' === $type || 'sale_price' === $type ) {
				$price = $composite->get_composite_price();
			} else {
				$price = $composite->get_composite_regular_price();
			}

			// Get WooCommerce Multi language Price by Currency.
			$price = apply_filters( 'woo_feed_wcml_price',
				$price, $product->get_id(), $this->get_feed_currency(), '_' . $type
			);

			// Get Price with tax
			if ( true === $tax ) {
				$price = woo_feed_get_price_with_tax( $price, $composite );
			}
		} else {
			$price = $base_price;
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get WPC Composite Product Price.
	 * Plugin URL: https://wordpress.org/plugins/wpc-composite-products/
	 * Product Type: composite
	 *
	 * @param WC_Product|WC_Product_Composite $product
	 * @param string                          $type Price Type (regular_price|price|sale_price)
	 * @param bool                            $tax  Add tax with price (true|false)
	 *
	 * @return float|int|mixed|string|void
	 */
	protected function get_wpc_composite_price( $product, $type, $tax ) {
		$price = 0;
		// Parent Component Price
		$base_price = $this->get_price_by_price_type( $product, $type, $tax );
		if ( isset( $this->config['composite_price'] ) && 'all_product_price' == $this->config['composite_price'] ) {
			$components_price = 0;
			$components       = $product->get_components();
			if ( ! empty( $components ) && is_array( $components ) ) {
				foreach ( $components as $component ) {
					$products = explode( ',', $component['products'] );
					foreach ( $products as $key => $product_id ) {
						$default_product = wc_get_product( $product_id );
						if ( is_object( $default_product ) && $default_product->is_in_stock() ) {
							$quantity = ( isset( $component['qty'] ) && $component['qty'] > 0 ) ? $component['qty'] : 1;
							if ( 'products' == $component['type'] && empty( $component['price'] ) ) {
								$components_price += $this->get_price_by_price_type( $default_product, 'price', $tax );
								$components_price *= $quantity;
							} elseif ( 'products' == $component['type'] && ! empty( $component['price'] ) ) {
								$clever           = new WPCleverWooco();
								$old_price        = $this->get_price_by_price_type( $default_product, 'price', $tax );
								$new_price        = $component['price'];
								$components_price += $clever::wooco_new_price( $old_price, $new_price );
								$components_price *= $quantity;
							}
							break; // Get first in stock product from component options.
						}
					}
				}

				// Apply discount to components price.
				$discount = $product->get_discount();
				if ( $discount > 0 ) {
					$components_price -= ( ( $discount / 100 ) * $components_price );
				}
			}

			if ( 'exclude' === $product->get_pricing() ) {
				$price = $components_price;
			} elseif ( 'include' === $product->get_pricing() ) {
				$price = $components_price + $base_price;
			} elseif ( 'only' === $product->get_pricing() ) {
				$price = $base_price;
			}
		} else {
			$price = $base_price;
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get total price of grouped product
	 *
	 * @param WC_Product_Grouped $grouped Grouped Product Object
	 * @param string             $type    Price Type (regular_price|price|sale_price)
	 * @param bool               $tax     Add tax with price (true|false)
	 *
	 * @return int|string
	 * @since 3.2.0
	 *
	 */
	protected function getGroupProductPrice( $grouped, $type, $tax = false ) {
		$groupProductIds = $grouped->get_children();
		$price           = 0;
		if ( ! empty( $groupProductIds ) ) {
			foreach ( $groupProductIds as $id ) {
				$product = wc_get_product( $id );
				if ( ! is_object( $product ) ) {
					continue; // make sure that the product exists..
				}

				$get_price = $this->get_price_by_product_type( $product, $type, $tax );
				if ( ! empty( $get_price ) ) {
					$price += $get_price;
				}
			}
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get Variable Product Price
	 *
	 * @param WC_Product_Variable $variable Variable Product Object
	 * @param string              $type     Price Type (regular_price|price|sale_price)
	 * @param bool                $tax      Add tax with price (true|false)
	 *
	 * @return int|string
	 * @since 3.2.0
	 *
	 */
	protected function getVariableProductPrice( $variable, $type, $tax = false ) {
		$min_max_first_default = apply_filters( 'woo_feed_variable_product_price_range', 'min' );
		if ( isset( $this->config['variable_price'] ) ) {
			$min_max_first_default = $this->config['variable_price'];
		}

		$price = 0;
		if ( 'first' == $min_max_first_default ) {
			$children = $variable->get_visible_children();
			if ( isset( $children[0] ) && ! empty( $children[0] ) ) {
				$variation = wc_get_product( $children[0] );
				$price     = $this->get_price_by_product_type( $variation, $type, $tax );
			}
		} else {
			if ( 'regular_price' == $type ) {
				$price = $variable->get_variation_regular_price( $min_max_first_default );
			} elseif ( 'sale_price' == $type ) {
				$price = $variable->get_variation_sale_price( $min_max_first_default );
			} else {
				$price = $variable->get_variation_price( $min_max_first_default );
			}

			// Get WooCommerce Multi language Price by Currency.
			$price = apply_filters( 'woo_feed_wcml_price',
				$price, $variable->get_id(), $this->get_feed_currency(), '_' . $type
			);

			// Get Price with tax
			if ( true === $tax ) {
				$price = woo_feed_get_price_with_tax( $price, $variable );
			}
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get Bundle Product Price
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $type    Price Type (regular_price|price|sale_price)
	 * @param bool       $tax     Add tax with price (true|false)
	 *
	 * @return int|float|string
	 * @since 4.3.24
	 */
	protected function getBundleProductPrice( $product, $type, $tax = false ) {
		if ( class_exists( 'WC_Product_Bundle' ) ) {//WC_Product_Bundle
			$bundle = new WC_Product_Bundle( $product );
			if ( 'price' === $type || 'sale_price' === $type ) {
				$price = $bundle->get_bundle_price();
			} else {
				$price = $bundle->get_bundle_regular_price();
			}

			// Get WooCommerce Multi language Price by Currency.
			$price = apply_filters( 'woo_feed_wcml_price',
				$price, $product->get_id(), $this->get_feed_currency(), '_' . $type
			);

			// Get Price with tax
			if ( true === $tax ) {
				$price = woo_feed_get_price_with_tax( $price, $bundle );
			}
		} else {
			// Get Default price for others bundle products.
			$price = $this->get_price_by_price_type( $product, $type, $tax );
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get price for Iconic woocommerce-bundled-products
	 *
	 * @param WC_Product $product Product object
	 * @param string     $type    Price Type (regular_price|price|sale_price)
	 * @param bool       $tax     Add tax with price (true|false)
	 *
	 * @return mixed $bundle_price
	 */
	protected function iconic_bundle_product_price( $product, $type, $tax = false ) {
		if ( ! class_exists( 'WC_Product_Bundled' ) ) {
			return $product->get_price();
		}

		$is_discounted              = false;
		$price                      = $product->get_price();
		$bundle                     = new WC_Product_Bundled( $product->get_id() );
		$iconic_bundle_product_type = ( ! is_null( $bundle->options['price_display'] ) ) ? $bundle->options['price_display'] : '';
		$product_ids                = $bundle->options['product_ids'];

		//set discount
		if ( ! empty( $bundle->options['fixed_discount'] ) ) {
			$is_discounted = true;
			$discount      = $bundle->options['fixed_discount'];
		} else {
			$is_discounted = false;
			$discount      = 0;
		}

		// Get price
		if ( is_array( $product_ids ) ) {
			$product_prices = array_map( function ( $id ) use ( $tax, $type, $is_discounted, $discount ) {
				$product = wc_get_product( $id );

				return $this->get_price_by_price_type( $product, $type, $tax );

			}, $product_ids );

			if ( 'range' === $iconic_bundle_product_type ) {
				$price = min( $product_prices );
			} else {
				$price = array_sum( $product_prices );
			}
		}

		// Get sale price if discount enabled
		if ( $is_discounted && ( 'sale_price' === $type || 'price' === $type ) ) {
			$price -= $discount;
		}

		// Get WooCommerce Multi language Price by Currency.
		$price = apply_filters( 'woo_feed_wcml_price',
			$price, $product->get_id(), $this->get_feed_currency(), '_' . $type
		);

		// Get Price with tax
		if ( true === $tax ) {
			$price = woo_feed_get_price_with_tax( $price, $bundle );
		}

		return $price > 0 ? $price : '';
	}

	/**
	 * Get Product Weight
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function weight( $product ) {
		return apply_filters( 'woo_feed_filter_product_weight', $product->get_weight(), $product, $this->config );
	}

	/**
	 * Get Product Weight Unit
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 5.2.7
	 *
	 */
	protected function weight_unit( $product ) {
		return apply_filters( 'woo_feed_filter_product_weight_unit', get_option( 'woocommerce_weight_unit' ), $product, $this->config );
	}

	/**
	 * Get Product Width
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function width( $product ) {
		return apply_filters( 'woo_feed_filter_product_width', $product->get_width(), $product, $this->config );
	}

	/**
	 * Get Product Height
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function height( $product ) {
		return apply_filters( 'woo_feed_filter_product_height', $product->get_height(), $product, $this->config );
	}

	/**
	 * Get Product Length
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function length( $product ) {
		return apply_filters( 'woo_feed_filter_product_length', $product->get_length(), $product, $this->config );
	}

	/**
	 * Get Product Shipping
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since  4.3.16
	 * @author Nazrul Islam Nayan
	 */
	protected function shipping( $product ) {
		$feedBody = '';
		$data     = $this->data;

		if ( isset( $data['shipping_zones'] ) && ! empty( $data['shipping_zones'] ) ) {
			$zones = $data['shipping_zones'];

			if ( in_array( $this->config['provider'], [ 'google', 'facebook', 'pinterest', 'bing', 'snapchat' ] ) ) {
				$get_shipping = new Woo_Feed_Shipping( $this->config );
				$feedBody     .= $get_shipping->set_product( $product )->set_shipping_zone( $zones )->get_google_shipping();
			}
		}

		return apply_filters( 'woo_feed_filter_product_shipping', $feedBody, $product, $this->config );

	}

	/**
	 * Get Product Shipping Cost
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since  5.1.20
	 * @author Nazrul Islam Nayan
	 */
	protected function shipping_cost( $product ) {
		$shipping_obj = new Woo_Feed_Shipping( $this->config );
		$shipping_obj = $shipping_obj->set_product( $product );

		return apply_filters( 'woo_feed_filter_product_shipping_cost', $shipping_obj->get_lowest_shipping_price(), $product, $this->config );
	}

	/**
	 * Get Product Shipping Class
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function shipping_class( $product ) {
		return apply_filters( 'woo_feed_filter_product_shipping_class', $product->get_shipping_class(), $product, $this->config );
	}

	/**
	 * Get Product Author Name
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function author_name( $product ) {
		$post = get_post( $product->get_id() );

		return get_the_author_meta( 'user_login', $post->post_author );
	}

	/**
	 * Get Product Author Email
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function author_email( $product ) {
		$post = get_post( $product->get_id() );

		return get_the_author_meta( 'user_email', $post->post_author );
	}

	/**
	 * Get Product Created Date
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function date_created( $product ) {
		$date_created = gmdate( 'Y-m-d', strtotime( $product->get_date_created() ) );

		return apply_filters( 'woo_feed_filter_product_date_created', $date_created, $product, $this->config );
	}

	/**
	 * Get Product Last Updated Date
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function date_updated( $product ) {
		$date_updated = gmdate( 'Y-m-d', strtotime( $product->get_date_modified() ) );

		return apply_filters( 'woo_feed_filter_product_date_updated', $date_updated, $product, $this->config );
	}

	/**
	 * Get Product Tax
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return mixed
	 * @since  5.1.20
	 * @author Nazrul Islam Nayan
	 */
	protected function tax( $product ) {
		$feedBody = '';
		if ( in_array( $this->config['provider'], [ 'google', 'facebook', 'pinterest', 'bing', 'snapchat' ] ) ) {
			$shipping_obj = new Woo_Feed_Shipping( $this->config );
			$shipping_obj = $shipping_obj->set_product( $product );
			$feedBody     .= $shipping_obj->get_google_tax();
		}

		return apply_filters( 'woo_feed_filter_product_tax', $feedBody, $product, $this->config );
	}

	/**
	 * Get Product Tax class
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return string
	 */
	protected function tax_class( $product ) {
		return apply_filters( 'woo_feed_filter_product_tax_class', $product->get_tax_class(), $product, $this->config );
	}

	/**
	 * Get Product Tax Status
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return string
	 */
	protected function tax_status( $product ) {
		return apply_filters( 'woo_feed_filter_product_tax_status', $product->get_tax_status(), $product, $this->config );
	}

	/**
	 * Get CTX Feed Custom Field value
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $field   custom field name.
	 *
	 * @return string
	 */
	protected function getCustomField( $product, $field ) {
		$id   = $product->get_id();
		$meta = $field;
		if ( $product->is_type( 'variation' ) ) {
			$meta = $field . '_var';
		}

		$meta = apply_filters( 'woo_feed_custom_field_meta', $meta, $product, $field );

		$new_meta_key = '';
		$old_meta_key = '';

		if ( strpos( $meta, '_identifier' ) !== false ) {
			$old_meta_key = $meta;
			$new_meta_key = str_replace( '_identifier', '', $meta );
		} else {
			$new_meta_key = $meta;
			$old_meta_key = str_replace( 'woo_feed_', 'woo_feed_identifier_', $meta );
		}

		$new_meta_value = $this->getProductMeta( $product, $new_meta_key );
		$old_meta_value = $this->getProductMeta( $product, $old_meta_key );

		if ( empty( $new_meta_value ) ) {
			return $old_meta_value;
		}

		return $new_meta_value;

	}

	/**
	 * Get Product Sale Price Effected Date for Google Shopping
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	protected function sale_price_effective_date( $product ) {
		$effective_date = '';
		$from           = $this->sale_price_sdate( $product );
		$to             = $this->sale_price_edate( $product );
		if ( ! empty( $from ) && ! empty( $to ) ) {
			$from = gmdate( 'c', strtotime( $from ) );
			$to   = gmdate( 'c', strtotime( $to ) );

			$effective_date = $from . '/' . $to;
		}

		return $effective_date;
	}

	/**
	 * Ger Product Attribute
	 *
	 * @param WC_Product $product
	 * @param            $attr
	 *
	 * @return string
	 * @since 2.2.3
	 *
	 */
	protected function getProductAttribute( $product, $attr ) {
		$id = $product->get_id();

		if ( woo_feed_wc_version_check( 3.2 ) ) {
			if ( woo_feed_wc_version_check( 3.6 ) ) {
				$attr = str_replace( 'pa_', '', $attr );
			}
			$value = $product->get_attribute( $attr );

			// if empty get attribute of parent post
			if ( '' === $value && $product->is_type( 'variation' ) ) {
				$product = wc_get_product( $product->get_parent_id() );
				$value   = $product->get_attribute( $attr );
			}

			$getproductattribute = $value;
		} else {
			$getproductattribute = implode( ',', wc_get_product_terms( $id, $attr, array( 'fields' => 'names' ) ) );
		}

		return apply_filters( 'woo_feed_filter_product_attribute', $getproductattribute, $attr, $product, $this->config );
	}

	/**
	 * Get Meta
	 *
	 * @param WC_Product $product
	 * @param string     $meta post meta key
	 *
	 * @return mixed|string
	 * @since 2.2.3
	 *
	 */
	protected function getProductMeta( $product, $meta ) {
		$value = get_post_meta( $product->get_id(), $meta, true );
		// if empty get meta value of parent post
		if ( '' === $value && $product->is_type( 'variation' ) ) {
			$value = get_post_meta( $product->get_parent_id(), $meta, true );
		}

		return apply_filters( 'woo_feed_filter_product_meta', $value, $product, $this->config );
	}

	/**
	 * Filter Products by Conditions
	 *
	 * @param WC_Product $product
	 *
	 * @return bool|array
	 * @since 3.2.0
	 *
	 */
	public function filter_product( $product ) {
		return true;
	}

	/**
	 * Get Taxonomy
	 *
	 * @param WC_Product $product
	 * @param            $taxonomy
	 *
	 * @return string
	 * @since 2.2.3
	 *
	 */
	protected function getProductTaxonomy( $product, $taxonomy ) {
		$id = $product->get_id();
		if ( $product->is_type( 'variation' ) ) {
			$id = $product->get_parent_id();
		}

		$separator = apply_filters( 'woo_feed_product_taxonomy_term_list_separator', ',', $this->config, $product );

		$term_list = get_the_term_list( $id, $taxonomy, '', $separator, '' );

		if ( is_object( $term_list ) && get_class( $term_list ) === 'WP_Error' ) {
			$term_list = '';
		}

		$getproducttaxonomy = woo_feed_strip_all_tags( $term_list );

		return apply_filters( 'woo_feed_filter_product_taxonomy', $getproducttaxonomy, $product, $this->config );
	}

	/**
	 * Format price value
	 *
	 * @param string $name          Attribute Name
	 * @param int    $conditionName condition
	 * @param int    $result        price
	 *
	 * @return mixed
	 * @since 3.2.0
	 *
	 */
	protected function price_format( $name, $conditionName, $result ) {
		$plus    = '+';
		$minus   = '-';
		$percent = '%';

		if ( strpos( $name, 'price' ) !== false ) {
			if ( strpos( $result, $plus ) !== false && strpos( $result, $percent ) !== false ) {
				$result = str_replace( '+', '', $result );
				$result = str_replace( '%', '', $result );
				if ( is_numeric( $result ) ) {
					$result = $conditionName + ( ( $conditionName * $result ) / 100 );
				}
			} elseif ( strpos( $result, $minus ) !== false && strpos( $result, $percent ) !== false ) {
				$result = str_replace( '-', '', $result );
				$result = str_replace( '%', '', $result );
				if ( is_numeric( $result ) ) {
					$result = $conditionName - ( ( $conditionName * $result ) / 100 );
				}
			} elseif ( strpos( $result, $plus ) !== false ) {
				$result = str_replace( '+', '', $result );
				if ( is_numeric( $result ) ) {
					$result = ( $conditionName + $result );
				}
			} elseif ( strpos( $result, $minus ) !== false ) {
				$result = str_replace( '-', '', $result );
				if ( is_numeric( $result ) ) {
					$result = $conditionName - $result;
				}
			}
		}

		return $result;
	}

	/**
	 * Format output According to Output Type config
	 *
	 * @param string     $output
	 * @param array      $outputTypes
	 * @param WC_Product $product
	 * @param string     $productAttribute
	 *
	 * @return float|int|string
	 * @since 3.2.0
	 *
	 */
	protected function format_output( $output, $outputTypes, $product, $productAttribute, $merchant_attribute ) {
		if ( ! empty( $outputTypes ) && is_array( $outputTypes ) ) {

			// Format Output According to output type
			if ( in_array( 2, $outputTypes ) ) { // Strip Tags
				$output = woo_feed_strip_all_tags( html_entity_decode( $output ) );
			}

			if ( in_array( 3, $outputTypes ) ) { // UTF-8 Encode
				$output = utf8_encode( $output );
			}

			if ( in_array( 4, $outputTypes ) ) { // htmlentities
				$output = htmlentities( $output, ENT_QUOTES, 'UTF-8' );
			}

			if ( in_array( 5, $outputTypes ) ) { // Integer
				$output = intval( $output );
			}

			if ( in_array( 6, $outputTypes ) ) { // Format Price
				if ( ! empty( $output ) && $output > 0 ) {
					$decimals           = wc_get_price_decimals();
					$decimal_separator  = wc_get_price_decimal_separator();
					$thousand_separator = wc_get_price_thousand_separator();
					$output             = (float) $output;

					if ( "idealo" === $this->config['provider'] ) {
						$output = number_format( $output, $decimals, wp_specialchars_decode( stripslashes( $decimal_separator ) ), wp_specialchars_decode( stripslashes( $thousand_separator ) ) );
					} else {
						$output = number_format( $output, 2, '.', '' );
					}

				}
			}

			if ( in_array( 7, $outputTypes ) ) { // Rounded Price
				if ( ! empty( $output ) && $output > 0 ) {
					$output = round( $output );
					$output = number_format( $output, 2, '.', '' );
				}
			}

			if ( in_array( 8, $outputTypes ) ) { // Delete Space
				$output = htmlentities( $output, null, 'utf-8' );
				$output = str_replace( "&nbsp;", " ", $output );
				$output = html_entity_decode( $output );
				$output = preg_replace( "/\\s+/", ' ', $output );
			}

			if ( in_array( 10, $outputTypes ) ) { // Remove Invalid Character
				$output = woo_feed_stripInvalidXml( $output );
			}

			if ( in_array( 11, $outputTypes ) ) {  // Remove ShortCodes
				$output = $this->remove_short_codes( $output );
			}

			if ( in_array( 12, $outputTypes ) ) {
				//$output = ucwords(mb_strtolower($output));
				$output = mb_convert_case( $output, MB_CASE_TITLE );
			}

			if ( in_array( 13, $outputTypes ) ) {
				//$output = ucfirst(strtolower($output));
				$output = mb_strtoupper( mb_substr( $output, 0, 1 ) ) . mb_substr( $output, 1 );
			}

			if ( in_array( 14, $outputTypes ) ) {
				$output = mb_strtoupper( mb_strtolower( $output ) );
			}

			if ( in_array( 15, $outputTypes ) ) {
				$output = mb_strtolower( $output );
			}

			if ( in_array( 16, $outputTypes ) ) {
				if ( 'http' == substr( $output, 0, 4 ) ) {
					$output = str_replace( 'http://', 'https://', $output );
				}
			}

			if ( in_array( 17, $outputTypes ) ) {
				if ( 'http' == substr( $output, 0, 4 ) ) {
					$output = str_replace( 'https://', 'http://', $output );
				}
			}

			if ( in_array( 18, $outputTypes ) ) { // only parent
				if ( $product->is_type( 'variation' ) ) {
					$id            = $product->get_parent_id();
					$parentProduct = wc_get_product( $id );
					$output        = $this->getAttributeValueByType( $parentProduct, $productAttribute, $merchant_attribute );
				}
			}

			if ( in_array( 19, $outputTypes ) ) { // child if parent empty
				if ( $product->is_type( 'variation' ) ) {
					$id            = $product->get_parent_id();
					$parentProduct = wc_get_product( $id );
					$output        = $this->getAttributeValueByType( $parentProduct, $productAttribute, $merchant_attribute );
					if ( empty( $output ) ) {
						$output = $this->getAttributeValueByType( $product, $productAttribute, $merchant_attribute );
					}
				}
			}

			if ( in_array( 20, $outputTypes ) ) { // parent if child empty
				if ( $product->is_type( 'variation' ) ) {
					$output = $this->getAttributeValueByType( $product, $productAttribute, $merchant_attribute );
					if ( empty( $output ) ) {
						$id            = $product->get_parent_id();
						$parentProduct = wc_get_product( $id );
						$output        = $this->getAttributeValueByType( $parentProduct, $productAttribute, $merchant_attribute );
					}
				}
			}

			if ( in_array( 9, $outputTypes ) && ! empty( $output ) && 'xml' === $this->config['feedType'] ) { // Add CDATA
				$output = '<![CDATA[' . $output . ']]>';
			}
		}

		return $output;
	}

	/**
	 * Add Prefix and Suffix with attribute value
	 *
	 * @param $output
	 * @param $prefix
	 * @param $suffix
	 * @param $attribute
	 *
	 * @return string
	 * @since 3.2.0
	 *
	 */
	public function process_prefix_suffix( $output, $prefix, $suffix, $attribute = '' ) {
		$cdata = false;
		if ( '' === $output ) {
			return $output;
		}

		if ( strpos( $output, '<![CDATA[' ) !== false ) {
			$cdata  = true;
			$output = str_replace( array( '<![CDATA[', ']]>' ), array( '', '' ), $output );
		}

		// Add Prefix before Output
		if ( '' !== $prefix ) {
			$output = "$prefix" . $output;
		}

		// Add Suffix after Output
		if ( '' !== $suffix ) {
			if ( array_key_exists( trim( $suffix ), get_woocommerce_currencies() ) ) { // Add space before suffix if attribute contain price.
				$output .= ' ' . $suffix;
			} elseif ( substr( $output, 0, 4 ) === 'http' ) {
				// Parse URL Parameters if available into suffix field
				$output = woo_feed_make_url_with_parameter( $output, $suffix );
			} else {
				$output .= (string) $suffix;
			}
		}

		if ( $cdata ) {
			$output = '<![CDATA[' . $output . ']]>';
		}

		return "$output";
	}

	/**
	 * Get Subscription period
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 *
	 */
	protected function subscription_period( $product ) {
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return $this->getProductMeta( $product, '_subscription_period' );
		}

		return '';
	}

	/**
	 * Get Subscription period interval
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 *
	 */
	protected function subscription_period_interval( $product ) {
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return $this->getProductMeta( $product, '_subscription_period_interval' );
		}

		return '';
	}

	/**
	 * Get Subscription period interval
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 *
	 */
	protected function subscription_amount( $product ) {
		return $this->price( $product );
	}

	/**
	 * Get Installment Amount
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.3.101
	 */
	protected function installment_amount( $product ) {
		return $this->price( $product );
	}

	/**
	 * Get Installment Months
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.3.101
	 */
	protected function installment_months( $product ) {
		if ( class_exists( 'WC_Subscriptions' ) ) {
			return $this->getProductMeta( $product, '_subscription_length' );
		}

		return '';
	}


	/**
	 * Calculate unit_price_measure. If Unit custom fields by CTX feed are enabled then it will take value
	 * from custom fields or it will take values for WooCommerce Germanized Plugin if enabled.
	 *
	 * If variation values are empty then it will take value from variable product.
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 */
	protected function unit_price_measure( $product ) {
		$unit_price_measure = '';
		$identifiers        = woo_feed_get_options( 'woo_feed_identifier' );
		if ( 'enable' === $identifiers['unit_pricing_base_measure']
		     && 'enable' === $identifiers['unit_pricing_measure']
		     && 'enable' === $identifiers['unit']
		) {
			$unit               = $this->getCustomField( $product, 'woo_feed_unit' );
			$unit_price_measure = $this->getCustomField( $product, 'woo_feed_unit_pricing_measure' );

			$unit_price_measure .= " " . $unit;
		}

		if ( empty( $unit_price_measure ) && class_exists( 'WooCommerce_Germanized' ) ) { // For WooCommerce Germanized Plugin
			$unit               = $this->getProductMeta( $product, '_unit' );
			$unit_price_measure = $this->getProductMeta( $product, '_unit_product' );

			$unit_price_measure .= " " . $unit;
		}

		return apply_filters( 'woo_feed_filter_unit_price_measure', $unit_price_measure, $product, $this->config );
	}

	/**
	 * Calculate unit_price_base_measure. If Unit custom fields by CTX feed are enabled then it will take value
	 * from custom fields or it will take values for WooCommerce Germanized Plugin if enabled.
	 *
	 * If variation values are empty then it will take value from variable product.
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 */
	protected function unit_price_base_measure( $product ) {
		$unit_price_base_measure = '';
		$identifiers             = woo_feed_get_options( 'woo_feed_identifier' );
		if ( 'enable' === $identifiers['unit_pricing_base_measure']
		     && 'enable' === $identifiers['unit_pricing_measure']
		     && 'enable' === $identifiers['unit']
		) {
			$unit                    = $this->getCustomField( $product, 'woo_feed_unit' );
			$unit_price_base_measure = $this->getCustomField( $product, 'woo_feed_unit_pricing_base_measure' );

			$unit_price_base_measure .= " " . $unit;
		}

		if ( empty( $unit_price_base_measure ) && class_exists( 'WooCommerce_Germanized' ) ) { // For WooCommerce Germanized Plugin
			$unit                    = $this->getProductMeta( $product, '_unit' );
			$unit_price_base_measure = $this->getProductMeta( $product, '_unit_base' );

			$unit_price_base_measure .= " " . $unit;
		}

		return apply_filters( 'woo_feed_filter_unit_price_base_measure', $unit_price_base_measure, $product, $this->config );
	}

	/**
	 * Get GTIN value of WooCommerce Germanized Plugin
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 */
	protected function wc_germanized_gtin( $product ) {
		$wc_germanized_gtin = '';
		if ( class_exists( 'WooCommerce_Germanized' ) ) { // For WooCommerce Germanized Plugin
			$wc_germanized_gtin = $this->getProductMeta( $product, '_ts_gtin' );
		}

		return apply_filters( 'woo_feed_filter_wc_germanized_gtin', $wc_germanized_gtin, $product, $this->config );
	}

	/**
	 * Get MPN value of WooCommerce Germanized Plugin
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 3.6.3
	 */
	protected function wc_germanized_mpn( $product ) {
		$wc_germanized_mpn = '';
		if ( class_exists( 'WooCommerce_Germanized' ) ) { // For WooCommerce Germanized Plugin
			$wc_germanized_mpn = $this->getProductMeta( $product, '_ts_mpn' );
		}

		return apply_filters( 'woo_feed_filter_wc_germanized_mpn', $wc_germanized_mpn, $product, $this->config );
	}


	/**
	 * Yoast Identifier attribute for gtin8
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.4
	 */
	protected function yoast_gtin8( $product ) {
		$yoast_gtin8_value = woo_feed_get_yoast_identifiers_value( 'gtin8', $product );

		return apply_filters( 'yoast_gtin8_attribute_value', $yoast_gtin8_value, $product );
	}

	/**
	 * Yoast Identifier attribute for gtin12
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.4
	 */
	protected function yoast_gtin12( $product ) {
		$yoast_gtin12_value = woo_feed_get_yoast_identifiers_value( 'gtin12', $product );

		return apply_filters( 'yoast_gtin12_attribute_value', $yoast_gtin12_value, $product );
	}

	/**
	 * Yoast Identifier attribute for gtin13
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.4
	 */
	protected function yoast_gtin13( $product ) {
		$yoast_gtin13_value = woo_feed_get_yoast_identifiers_value( 'gtin13', $product );

		return apply_filters( 'yoast_gtin13_attribute_value', $yoast_gtin13_value, $product );
	}

	/**
	 * Yoast Identifier attribute for gtin12
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.4
	 */
	protected function yoast_gtin14( $product ) {
		$yoast_gtin14_value = woo_feed_get_yoast_identifiers_value( 'gtin14', $product );

		return apply_filters( 'yoast_gtin14_attribute_value', $yoast_gtin14_value, $product );
	}

	/**
	 * Yoast Identifier attribute for isbn
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.4
	 */
	protected function yoast_isbn( $product ) {
		$yoast_isbn_value = woo_feed_get_yoast_identifiers_value( 'isbn', $product );

		return apply_filters( 'yoast_isbn_attribute_value', $yoast_isbn_value, $product );
	}

	/**
	 * Yoast Identifier attribute for mpn
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 4.4.4
	 */
	protected function yoast_mpn( $product ) {
		$yoast_mpn_value = woo_feed_get_yoast_identifiers_value( 'mpn', $product );

		return apply_filters( 'yoast_mpn_attribute_value', $yoast_mpn_value, $product );
	}

	/**
	 * Rank Math GTIN Identifier attribute
	 *
	 * @param WC_Product $product
	 *
	 * @return mixed
	 * @since 5.2.66
	 */
	protected function rank_math_gtin( $product ) {
		$product_id          = woo_feed_parent_product_id( $product );
		$rankmath_gtin_value = get_post_meta( $product_id, '_rank_math_gtin_code' );
		$rankmath_gtin_value = ! empty( $rankmath_gtin_value ) && is_array( $rankmath_gtin_value ) ? $rankmath_gtin_value[0] : '';

		return apply_filters( 'rankmath_gtin_attribute_value', $rankmath_gtin_value, $product );
	}

	/**
	 * Get ACF Field values
	 *
	 * @param WC_Product $product
	 * @param string     $field_key ACF Filed Key with prefix "acf_fields_"
	 *
	 * @return mixed|string
	 */
	private function getACFField( $product, $field_key ) {
		$field = str_replace( 'acf_fields_', '', $field_key );
		if ( class_exists( 'ACF' ) ) {
			return get_field( $field, $product->get_id() );
		}

		return '';
	}
}

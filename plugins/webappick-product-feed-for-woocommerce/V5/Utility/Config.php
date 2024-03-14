<?php // phpcs:disable

/**
 * This class contains feed information.
 *
 * @package CTXFeed\V5\Utility
 */

namespace CTXFeed\V5\Utility;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Merchant\MerchantAttributeReplaceFactory;
use CTXFeed\V5\Product\AttributeValueByType;

/**
 * Class Config
 *
 * @package CTXFeed\V5\Utility
 */
class Config {// phpcs:ignore

	/**
	 * @var array|bool $feed_info Feed Info.
	 */
	public $feed_info;

	/**
	 * @var array|bool $config Feed Configuration.
	 */
	private $config;

	/**
	 * @var mixed $context Feed Configuration context.
	 */
	private $context;

	/**
	 * Config constructor.
	 *
	 * @param array $feed_info Feed Info.
	 * @param string $context Feed Configuration context.
	 */

	public function __construct( $feed_info, $context = 'view' ) {
		if ( isset( $feed_info['option_value']['feedrules'] ) ) {
			$feed_info = self::process_old_version_feed_created_products_id( $feed_info );
			$feedrules    = $feed_info['option_value']['feedrules'];
		} else {
			$feedrules =  [];
			$feed_info =  [];
		}

		$this->feed_info = $feed_info;
		$this->context   = $context;

		$this->set_config( $feedrules );
	}

	/**
	 * @return array
	 */
	public function get_feed_rules() {
		if ( isset( $this->config['feedrules'] ) ) {
			return $this->config['feedrules'];
		}

		return $this->feed_info;
	}

	/**
	 *  Get Feed name.
	 *
	 * @return string
	 */
	public function get_feed_id() {
		if ( isset( $this->config['feed_id'] ) && ! empty( $this->config['feed_id'] ) ) {
			return $this->config['feed_id'];
		}

		return false;
	}

	/**
	 *  Get Feed name.
	 *
	 * @return string
	 */
	public function get_feed_name() {
		if ( isset( $this->config['filename'] ) && ! empty( $this->config['filename'] ) ) {
			return $this->config['filename'];
		}

		return false;
	}

	/**
	 *  Get Feed name.
	 *
	 * @param bool $full Full name with prefix.
	 *
	 * @return string
	 */
	public function get_feed_option_name( $full = false ) {
		$option_name = '';

		if ( ! empty( $this->config['feed_option_name'] ) && isset( $this->config['feed_option_name'] ) ) {
			$option_name = $this->config['feed_option_name'];
		}

		if ( ! $option_name && isset( $this->feed_info['option_name'] ) ) {
			$option_name = FeedHelper::get_feed_option_name( $this->feed_info['option_name'] );
		}

		if ( $full && ! empty( $option_name ) ) {
			$option_name = AttributeValueByType::FEED_RULES_OPTION_PREFIX . $this->config['feed_option_name'];
		}

		return $option_name;
	}

	/**
	 *  Get Feed file name.
	 *
	 * @param bool $infos Get file info.
	 *
	 * @return string
	 */
	public function get_feed_file_name( $infos = false ) {
		$url = $this->get_feed_url();

		if ( $url ) {
			$file_info = pathinfo( $url );

			if ( $infos ) {
				return $file_info;
			}

			return $file_info['basename'];
		}

		return false;
	}

	/**
	 *  Get Feed Template.
	 *
	 * @return string
	 */
	public function get_feed_template() {
		if ( isset( $this->config['provider'] ) ) {
			return $this->config['provider'];
		}

		return false;
	}

	/**
	 *  Get Feed Language.
	 *
	 * @return string
	 */
	public function get_feed_language() {
		if ( isset( $this->config['feedLanguage'] ) && ! empty( $this->config['feedLanguage'] ) ) {
			return $this->config['feedLanguage'];
		}

		return false;
	}

	/**
	 *  Get Feed Currency.
	 *
	 * @return string
	 */
	public function get_feed_currency() {
		if ( isset( $this->config['feedCurrency'] ) ) {
			return $this->config['feedCurrency'];
		}

		$attributes  = $this->config['attributes'];
		$price_attrs = array( 'price', 'current_price', 'price_with_tax', 'current_price_with_tax' );

		foreach ( $price_attrs as $price_attr ) {
			$key = array_search( $price_attr, $attributes, true );

			if ( $key ) {
				break;
			}
		}

		if ( isset( $this->config['suffix'][ $key ] ) ) {
			return $this->config['suffix'][ $key ];
		}

		return get_woocommerce_currency();
	}

	/**
	 *  Get Feed Country.
	 *
	 * @return string
	 */
	public function get_feed_country() {
		if ( isset( $this->config['feed_country'] ) && ! empty( $this->config['feed_country'] ) ) {
			return $this->config['feed_country'];
		}

		return false;
	}

	/**
	 *  Get Feed File Type.
	 *
	 * @return string
	 */
	public function get_feed_file_type() {
		if ( isset( $this->config['feedType'] ) && ! empty( $this->config['feedType'] ) ) {
			return $this->config['feedType'];
		}

		return false;
	}

	/**
	 *  Get Feed File Type.
	 *
	 * @return string
	 */
	public function get_delimiter() {
		if ( isset( $this->config['delimiter'] ) && $this->config['delimiter'] !== '' ) {
			if ( 'tsv' === $this->get_feed_file_type() ) {
				$this->config['delimiter'] = "\t";

				return $this->config['delimiter'];
			}

			if ( ' ' === $this->config['delimiter'] ) {
				$this->config['delimiter'] = '\s';
			}

			return $this->config['delimiter'];
		}

		return false;
	}

	/**
	 *  Get Feed File Type.
	 *
	 * @return string
	 */
	public function get_enclosure() {
		if ( 'double' === $this->config['enclosure'] ) {
			return '"';
		}

		if ( 'single' === $this->config['enclosure'] ) {
			return "'";
		}

		return false;
	}

	/**
	 *  Get Feed items wrapper.
	 *
	 * @return string
	 */
	public function get_feed_items_wrapper() {
		if ( ! empty( $this->config['itemsWrapper'] ) ) {
			return $this->config['itemsWrapper'];
		}

		return false;
	}

	/**
	 *  Get Feed item wrapper.
	 *
	 * @return string
	 */
	public function get_feed_item_wrapper() {
		if ( ! empty( $this->config['itemWrapper'] ) ) {
			return $this->config['itemWrapper'];
		}

		return false;
	}

	/**
	 *  Get Feed Extra Header.
	 *
	 * @return string
	 */
	public function get_feed_extra_header() {
		if ( ! empty( $this->config['extraHeader'] ) ) {
			return $this->config['extraHeader'];
		}

		return false;
	}

	/**
	 *  Get Feed Shipping Country.
	 *
	 * @return string
	 */
	public function get_shipping_country() {
		if ( ! empty( $this->config['shipping_country'] ) ) {
			return $this->config['shipping_country'];
		}

		return false;
	}

	/**
	 *  Get Feed Tax Country.
	 *
	 * @return string
	 */
	public function get_tax_country() {
		if ( ! empty( $this->config['tax_country'] ) ) {
			return $this->config['tax_country'];
		}

		return false;
	}

	/**
	 *  Get String Replace config
	 *
	 * @return array|bool
	 */
	public function get_string_replace() {
		if ( ! empty( $this->config['str_replace'] ) ) {
			return $this->config['str_replace'];
		}

		return false;
	}

	/**
	 *  Get URL campaign parameter.
	 *
	 * @return array|bool
	 */
	public function get_campaign_parameters() {
		if ( ! empty( $this->config['campaign_parameters'] ) ) {
			return wp_parse_args(
				$this->config['campaign_parameters'],
				array(
					'utm_source'   => '',
					'utm_medium'   => '',
					'utm_campaign' => '',
					'utm_term'     => '',
					'utm_content'  => '',
				)
			);
		}

		return false;
	}

	/**
	 *  Status to remove backorder products.
	 *
	 * @return bool
	 */
	public function remove_backorder_product() {
		return isset( $this->config['is_backorder'] ) && $this->config['is_backorder'];
	}

	/**
	 *  Status to remove outofstock products.
	 *
	 * @return bool
	 */
	public function remove_outofstock_product() {
		return isset( $this->config['is_outOfStock'] ) && $this->config['is_outOfStock'];
	}

	/**
	 *  Status to remove empty description products.
	 *
	 * @return bool
	 */
	public function remove_empty_title() {
		return isset( $this->config['is_emptyTitle'] ) && $this->config['is_emptyTitle'];
	}

	/**
	 *  Status to remove empty description products.
	 *
	 * @return bool
	 */
	public function remove_empty_description() {
		return isset( $this->config['is_emptyDescription'] ) && $this->config['is_emptyDescription'];
	}

	/**
	 *  Status to remove empty image products.
	 *
	 * @return bool
	 */
	public function remove_empty_image() {
		return isset( $this->config['is_emptyImage'] ) && $this->config['is_emptyImage'];
	}

	/**
	 *  Status to remove empty price products.
	 *
	 * @return bool
	 */
	public function remove_empty_price() {
		return isset( $this->config['is_emptyPrice'] ) && $this->config['is_emptyPrice'];
	}

	/**
	 *  Status to remove hidden products
	 *
	 * @return bool
	 */
	public function remove_hidden_products() {
		if ( isset( $this->config['product_visibility'] ) ) {
			return $this->config['product_visibility'];
		}

		return false;
	}

	/**
	 *  Status Out of Stock visibility override
	 *
	 * @return bool
	 */
	public function get_outofstock_visibility() {
		if ( isset( $this->config['outofstock_visibility'] ) ) {
			return $this->config['outofstock_visibility'];
		}

		return false;
	}

	/**
	 *  Get Number Format.
	 *
	 * @return bool|array
	 */
	public function get_number_format() {
		if ( isset( $this->config['decimal_separator'] ) ) {
            if( Helper::is_pro() ){
                $number_format = array(
                    'decimal_separator'  => apply_filters( 'ctx_feed_number_format_decimal_separator', $this->config['decimal_separator'], $this->config ),
                    'thousand_separator' => apply_filters( 'ctx_feed_number_format_thousand_separator', $this->config['thousand_separator'], $this->config ),
                    'decimals'           => apply_filters( 'ctx_feed_number_format_decimals', $this->config['decimals'], $this->config ),
                );
            }else{
                $number_format = array(
                    'decimal_separator'     => apply_filters( 'wc_get_price_decimal_separator', get_option( 'woocommerce_price_decimal_sep' ) ),
			        'thousand_separator'    => stripslashes( apply_filters( 'wc_get_price_thousand_separator', get_option( 'woocommerce_price_thousand_sep' ) ) ),
			         'decimals'              => absint( apply_filters( 'wc_get_price_decimals', get_option( 'woocommerce_price_num_decimals', 2 ) ) ),
                );
            }


			return apply_filters( 'ctx_feed_number_format', $number_format, $this->config );
		}

		return false;
	}

	/**
	 * Get product Ids to exclude.
	 *
	 * @return array|bool
	 */
	public function get_products_to_exclude() {
		if ( isset( $this->config['filter_mode'] ) ) {
			$mode = $this->config['filter_mode'];

			if ( 'exclude' === $mode['product_ids'] && ! empty( $this->config['product_ids'] ) ) {
				return $this->config['product_ids'];
			}
		}

		return false;
	}

	/**
	 * Get product Ids to include.
	 *
	 * @return array|bool
	 */
	public function get_products_to_include() {
		if ( isset( $this->config['filter_mode'] ) ) {
			$mode = $this->config['filter_mode'];

			if ( 'include' === $mode['product_ids'] && ! empty( $this->config['product_ids'] ) ) {
				return $this->config['product_ids'];
			}
		}

		return false;
	}

	/**
	 * Get categories to exclude.
	 *
	 * @return mixed
	 */
	public function get_categories_to_exclude() {
		if ( isset( $this->config['filter_mode'] ) ) {
			$mode = $this->config['filter_mode'];

			if ( 'exclude' === $mode['categories'] && ! empty( $this->config['categories'] ) ) {
				return $this->config['categories'];
			}
		}

		return false;
	}

	/**
	 * Get categories to include.
	 *
	 * @return mixed
	 */
	public function get_categories_to_include() {
		if ( isset( $this->config['filter_mode'] ) ) {
			$mode = $this->config['filter_mode'];

			if ( 'include' === $mode['categories'] && ! empty( $this->config['categories'] ) ) {
				return $this->config['categories'];
			}
		}

		return false;
	}

	/**
	 * Get post-statuses to include.
	 *
	 * @return mixed
	 */
	public function get_post_status_to_include() {
		$status = array( 'draft', 'pending', 'private', 'publish' );

		if (
			isset( $this->config['filter_mode'], $this->config['post_status'] )
			&& ! empty( $this->config['post_status'] )
		) {
			$mode = $this->config['filter_mode'];

			if ( 'include' === $mode['post_status'] ) {
				return $this->config['post_status'];
			}

			if ( 'exclude' === $mode['post_status'] ) {
				return array_unique( array_merge( array_diff( $status, $this->config['post_status'] ), array_diff( $status, $this->config['post_status'] ) ) );
			}
		}

		return false;
	}

	/**
	 * Get post statuses to include.
	 *
	 * @return array|bool
	 */
	public function get_vendors_to_include() {
		if ( ! empty( $this->config['vendors'] ) ) {
			if ( is_array( $this->config['vendors'] ) ) {
				return $this->config['vendors'];
			}

			return explode( ',', $this->config['vendors'] );
		}

		return false;
	}

	/**
	 * Get post-statuses to include.
	 *
	 * @return bool
	 */
	public function get_variations_to_include() {
		return isset( $this->config['is_variations'] ) && in_array(
				$this->config['is_variations'],
				array(
					'y',
					'both',
				),
				true
			);
	}

	/**
	 * Get Advance Filter Config.
	 *
	 * @return array|bool
	 */
	public function get_advance_filters() {
		if ( isset( $this->config['fattribute'] ) ) {
			return array(
				'fattribute'    => $this->config['fattribute'],
				'condition'     => $this->config['condition'],
				'filterCompare' => $this->config['filterCompare'],
				'concatType'    => $this->config['concatType'],
			);
		}

		return false;
	}

	/**
	 * Get FTP Config.
	 *
	 * @return array|bool
	 */
	public function get_ftp_config() {
		if ( $this->is_ftp_enabled() ) {
			return array(
				'type'       => $this->config['ftporsftp'],
				'host'       => $this->config['ftphost'],
				'port'       => $this->config['ftpport'],
				'username'   => $this->config['ftpuser'],
				'password'   => $this->config['ftppassword'],
				'path'       => $this->config['ftppath'],
				'mode'       => $this->config['ftpmode'],
				'ftpenabled' => $this->config['ftpenabled'],
			);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function is_ftp_enabled() {
		return isset( $this->config['ftpenabled'] ) && $this->config['ftpenabled'];
	}

	/**
	 * Get variable product config.
	 *
	 * @return array|bool
	 */
	public function get_variable_config() {
		if ( isset( $this->config['is_variations'] ) ) {
			return [
				"is_variations"     => $this->config['is_variations'],
				"variable_price"    => isset( $this->config['variable_price'] ) ? $this->config['variable_price'] : 'min',
				"variable_quantity" => isset( $this->config['variable_quantity'] ) ? $this->config['variable_quantity'] : 'sum',
			];
		}

		return false;
	}

	/**
	 * Get composite product price settings.
	 *
	 * @return mixed
	 */
	public function get_composite_price_type() {
		if ( isset( $this->config['composite_price'] ) ) {
			return $this->config['composite_price'];
		}

		return false;
	}

	/**
	 * Get Feed Info
	 *
	 * @return array
	 */
	public function get_feed_info() {
		return $this->feed_info;
	}

	/**
	 * Get Feed Configuration.
	 *
	 * @return array
	 */
	public function get_config() {
		return $this->config;
	}

	/**
	 * Add or Update Feed Configuration
	 *
	 * @param array $feedrules Feed Rules.
	 * @param string|null $feed_option_name Feed Option Name.
	 *
	 * @return bool|string
	 */
	public function save_config( $feedrules, $feed_option_name = null ) {
		$prepared_data    = FeedHelper::prepare_feed_rules_to_save( $feedrules, $feed_option_name );
		$update           = $prepared_data['is_update'];
		$feed_option_name = $prepared_data['feed_option_name'];

		$this->set_config( $feedrules );

		FeedHelper::call_action_before_update_feed_config( $update, $feedrules, $feed_option_name );

		if ( update_option( $feed_option_name, $prepared_data['feedrules_to_save'] ) ) {
			FeedHelper::call_action_after_update_feed_config( $update, $feedrules, $feed_option_name );

			return $feed_option_name;
		}

		return false;
	}

	/**
	 * Get Feed URL.
	 *
	 * @return array|bool
	 */
	public function get_feed_url() {
		if ( ! empty( $this->feed_info['option_value']['url'] ) ) {
			return $this->feed_info['option_value']['url'];
		}

		return false;
	}

	/**
	 * Get Feed File Path.
	 *
	 * @return string|bool
	 */
	public function get_feed_path() {
		$upload_dir = wp_get_upload_dir();

		if ( ! isset( $this->config['provider'] ) && ! isset( $this->config['feedType'] ) ) {
			return false;
		}

		return sprintf( '%s/woo-feed/%s/%s/%s', $upload_dir['basedir'], $this->config['provider'], $this->config['feedType'], $this->get_feed_file_name() );
	}

	/**
	 * Get Feed Status.
	 *
	 * @return array|bool
	 */
	public function get_feed_status() {
		if ( isset( $this->feed_info['status'] ) ) {
			return $this->feed_info['status'];
		}

		return false;
	}

	/**
	 * Get Output Types of an attribute.
	 *
	 * @param string $attribute Product Attribute.
	 * @param string $merchant_attribute Merchant Attribute.
	 *
	 * @return array|mixed
	 */
	public function get_attribute_output_types( $attribute, $merchant_attribute ) {
		$output_types    = $this->config['output_type'];
		$attribute_index = $this->get_attribute_index( $attribute, $merchant_attribute );

		if ( ! empty( $output_types[ $attribute_index ] ) ) {
			return $output_types[ $attribute_index ];
		}

		return array();
	}

	/**
	 * Get Limit Commands of an attribute.
	 *
	 * @param string $attribute Product Attribute.
	 * @param string $merchant_attribute Merchant Attribute.
	 *
	 * @return array|mixed
	 */
	public function get_attribute_commands( $attribute, $merchant_attribute ) {
		$commands        = $this->config['limit'];
		$attribute_index = $this->get_attribute_index( $attribute, $merchant_attribute );

		if ( ! empty( $commands[ $attribute_index ] ) ) {
			return $commands[ $attribute_index ];
		}

		return array();
	}

	/**
	 * Get Prefix and Suffix of an attribute.
	 *
	 * @param string $attribute Product Attribute.
	 * @param string $merchant_attribute Merchant Attribute.
	 *
	 * @return array
	 */
	public function get_prefix_suffix( $attribute, $merchant_attribute ) {
		$prefixes        = $this->config['prefix'];
		$suffixes        = $this->config['suffix'];
		$attribute_index = $this->get_attribute_index( $attribute, $merchant_attribute );

		$prefix = '';

		if ( ! empty( $prefixes[ $attribute_index ] ) ) {
			$prefix = $prefixes[ $attribute_index ];
		}

		$suffix = '';

		if ( ! empty( $suffixes[ $attribute_index ] ) ) {
			$suffix = $suffixes[ $attribute_index ];
		}

		return array(
			'prefix' => $prefix,
			'suffix' => $suffix,
		);
	}

	/**
	 * Get index of an attribute.
	 *
	 * @param string $attribute Product Attribute.
	 * @param string $merchant_attribute Merchant Attribute.
	 *
	 * @return int|string
	 */
	public function get_attribute_index( $attribute, $merchant_attribute ) {
		$value_attributes    = $this->config['attributes'];
		$merchant_attributes = $this->config['mattributes'];
		$attributes_type     = $this->config['type'];
		$attribute_index     = - 1;


		$special_templates = FeedHelper::get_special_templates();

		if ( in_array( $this->provider, $special_templates, true ) ) {
			return array_search( $attribute, $value_attributes, true );
		}

		/**
		 * Array_search will work only for special templates because
		 * if multiple merchant attributes have the same attribute as value,
		 * then array search will always return the first index
		 */

		foreach ( $value_attributes as $index => $value_attribute ) {
			$replaced_attribute = MerchantAttributeReplaceFactory::replace_attribute( $merchant_attributes[ $index ], $this );

			if (
				$value_attribute === $attribute
				&& $replaced_attribute === $merchant_attribute
				&& $attributes_type[ $index ] === 'attribute'
			) {
				$attribute_index = $index;

				break;
			}

			if (
				$attributes_type[ $index ] === 'pattern'
				&& $value_attributes[ $index ] === '0'
				&& $merchant_attribute === $replaced_attribute
			) {
				$attribute_index = $index;

				break;
			}
		}

		return $attribute_index;
	}

	/**
	 * Process Product Ids.
	 *
	 * @param array $feed_info Feed Config.
	 *
	 * @return array
	 */
	public static function process_old_version_feed_created_products_id( $feed_info ) {
		if ( isset( $feed_info['option_value']['feedrules']['product_ids'] ) && ! is_array( $feed_info['option_value']['feedrules']['product_ids'] ) ) {
			$included_ids_str = $feed_info['option_value']['feedrules']['product_ids'];

			$included_ids = array();

			if ( $included_ids_str !== '' ) {
				$included_ids_array = explode( ',', $included_ids_str );

				foreach ( $included_ids_array as $id ) {
					$included_ids[] = trim( $id );
				}
			}

			$feed_info['option_value']['feedrules']['product_ids'] = $included_ids;
		}

		return $feed_info;
	}

	/**
	 * Set Feed Configuration.
	 *
	 * @param array $config Feed Config.
	 *
	 * @return void
	 */
	private function set_config( $config ) {// phpcs:ignore
		$defaults = array(
			'provider'              => '',
			'feed_country'          => '',
			'filename'              => '',
			'feedType'              => '',
			'ftpenabled'            => 0,
			'ftporsftp'             => 'ftp',
			'ftphost'               => '',
			'ftpport'               => '21',
			'ftpuser'               => '',
			'ftppassword'           => '',
			'ftppath'               => '',
			'ftpmode'               => 'active',
			'is_variations'         => 'y', // Only Variations (All Variations)
			'variable_price'        => 'first',
			'variable_quantity'     => 'first',
			'feedLanguage'          => apply_filters( 'wpml_current_language', null ),
			'feedCurrency'          => apply_filters( 'woocommerce_currency', get_option( 'woocommerce_currency' ) ),
			'itemsWrapper'          => 'products',
			'itemWrapper'           => 'product',
			'delimiter'             => ',',
			'enclosure'             => 'double',
			'extraHeader'           => '',
			'vendors'               => array(),
			// Feed Config
			'mattributes'           => array(), // merchant attributes
			'prefix'                => array(), // prefixes
			'type'                  => array(), // value (attribute) types
			'attributes'            => array(), // product attribute mappings
			'default'               => array(), // default values (patterns) if value type set to pattern
			'suffix'                => array(), // suffixes
			'output_type'           => array(), // output type (output filter)
			'limit'                 => array(), // limit or command
			// filters tab
			'composite_price'       => 'all_product_price',
			'product_ids'           => array(),
			'categories'            => array(),
			'post_status'           => array( 'publish' ),
			'filter_mode'           => array(),
			'campaign_parameters'   => array(),
			'is_outOfStock'         => false,
			'is_backorder'          => false,
			'is_emptyDescription'   => false,
			'is_emptyTitle'         => 'n',
			'is_emptyImage'         => false,
			'is_emptyPrice'         => false,
			'product_visibility'    => 0,
			'shipping_country'      => '',
			'tax_country'           => '',
			// include hidden? 1 yes 0 no
			'outofstock_visibility' => false,
			// override wc global option for out-of-stock product hidden from catalog? 1 yes 0 no
			'ptitle_show'           => '',
			'decimal_separator'     => apply_filters( 'wc_get_price_decimal_separator', get_option( 'woocommerce_price_decimal_sep' ) ),
			'thousand_separator'    => stripslashes( apply_filters( 'wc_get_price_thousand_separator', get_option( 'woocommerce_price_thousand_sep' ) ) ),
			'decimals'              => absint( apply_filters( 'wc_get_price_decimals', get_option( 'woocommerce_price_num_decimals', 2 ) ) ),
		);

		$this->config                = wp_parse_args( $config, $defaults );
		$this->config['filter_mode'] = wp_parse_args(
			$this->config['filter_mode'],
			array(
				'product_ids' => 'include',
				'categories'  => 'include',
				'post_status' => 'include',
			)
		);

		if ( ! empty( $this->config['provider'] ) && is_string( $this->config['provider'] ) ) {
			/**
			 * Filter parsed rules for provider.
			 *
			 * @param array $rules
			 * @param string $context
			 *
			 * @since 3.3.7
			 */
			$this->config = apply_filters( "woo_feed_{$this->config['provider']}_parsed_rules", $this->config, $this->context );
		}

		$this->config = FeedHelper::validate_config( $this->config );

		/**
		 * Filter parsed rules.
		 *
		 * @param array $rules
		 * @param string $context
		 *
		 * @since 3.3.7 $provider parameter removed
		 */
		$this->config = apply_filters( 'woo_feed_parsed_rules', $this->config, $this->context );

	}

	/**
	 * Isset Feed Config.
	 *
	 * @param string $name Feed Config.
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return isset( $this->config[ $name ] );
	}

	/**
	 * Get Feed Config.
	 *
	 * @param string $name Feed Config.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->config[ $name ];
	}

	/**
	 * Set Feed Config.
	 *
	 * @param string $name Feed Config.
	 * @param string $value Feed Config.
	 *
	 * @return string
	 */
	public function __set( $name, $value ) {
		return $this->config[ $name ] = $value;
	}

	/**
	 * Unset Feed Config.
	 *
	 * @param string $name Feed Config.
	 *
	 * @return void
	 */
	public function __unset( $name ) {
		unset( $this->config[ $name ] );
	}

	/**
	 * Get pro version feed default rules.
	 *
	 * @param $rules
	 *
	 * @return mixed|null
	 */
	public static function free_default_feed_rules( $rules = [] ) {
		$defaults = array(
			'provider'            => '',
			'filename'            => '',
			'feedType'            => '',
			'feed_country'        => '',
			'ftpenabled'          => 0,
			'ftporsftp'           => 'ftp',
			'ftphost'             => '',
			'ftpport'             => '21',
			'ftpuser'             => '',
			'ftppassword'         => '',
			'ftppath'             => '',
			'ftpmode'             => 'active',
			'is_variations'       => 'y',
			'variable_price'      => 'first',
			'variable_quantity'   => 'first',
			'feedLanguage'        => apply_filters( 'wpml_current_language', null ),
			'feedCurrency'        => get_woocommerce_currency(),
			'itemsWrapper'        => 'products',
			'itemWrapper'         => 'product',
			'delimiter'           => ',',
			'enclosure'           => 'double',
			'extraHeader'         => '',
			'vendors'             => array(),
			// Feed Config
			'mattributes'         => array(), // merchant attributes
			'prefix'              => array(), // prefixes
			'type'                => array(), // value (attribute) types
			'attributes'          => array(), // product attribute mappings
			'default'             => array(), // default values (patterns) if value type set to pattern
			'suffix'              => array(), // suffixes
			'output_type'         => array(), // output type (output filter)
			'limit'               => array(), // limit or command
			// filters tab
			'composite_price'     => '',
			'shipping_country'    => '',
			'tax_country'         => '',
			'product_ids'         => '',
			'categories'          => array(),
			'post_status'         => array( 'publish' ),
			'filter_mode'         => array(),
			'campaign_parameters' => array(),

			'ptitle_show'        => '',
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimals'           => wc_get_price_decimals(),
		);
		$rules    = wp_parse_args( $rules, $defaults );

		return apply_filters( 'woo_feed_free_default_feed_rules', $rules );
	}

	/**
	 * Get pro version feed default rules.
	 *
	 * @param $rules
	 *
	 * @return mixed|null
	 */
	public static function pro_default_feed_rules( $rules = [] ) {
		$defaults = array(
			'provider'              => '',
			'feed_country'          => '',
			'filename'              => '',
			'feedType'              => '',
			'ftpenabled'            => 0,
			'ftporsftp'             => 'ftp',
			'ftphost'               => '',
			'ftpport'               => '21',
			'ftpuser'               => '',
			'ftppassword'           => '',
			'ftppath'               => '',
			'ftpmode'               => 'active',
			'is_variations'         => 'y', // Only Variations (All Variations)
			'variable_price'        => 'first',
			'variable_quantity'     => 'first',
			'feedLanguage'          => apply_filters( 'wpml_current_language', null ),
			'feedCurrency'          => get_woocommerce_currency(),
			'itemsWrapper'          => 'products',
			'itemWrapper'           => 'product',
			'delimiter'             => ',',
			'enclosure'             => 'double',
			'extraHeader'           => '',
			'vendors'               => array(),
			// Feed Config
			'mattributes'           => array(), // merchant attributes
			'prefix'                => array(), // prefixes
			'type'                  => array(), // value (attribute) types
			'attributes'            => array(), // product attribute mappings
			'default'               => array(), // default values (patterns) if value type set to pattern
			'suffix'                => array(), // suffixes
			'output_type'           => array(), // output type (output filter)
			'limit'                 => array(), // limit or command
			// filters tab
			'composite_price'       => 'all_product_price',
			'product_ids'           => '',
			'categories'            => array(),
			'post_status'           => array( 'publish' ),
			'filter_mode'           => array(),
			'campaign_parameters'   => array(),
			'is_outOfStock'         => 'n',
			'is_backorder'          => 'n',
			'is_emptyDescription'   => 'n',
			'is_emptyImage'         => 'n',
			'is_emptyPrice'         => 'n',
			'product_visibility'    => 0,
			// include hidden ? 1 yes 0 no
			'outofstock_visibility' => 0,
			// override wc global option for out-of-stock product hidden from catalog? 1 yes 0 no
			'ptitle_show'           => '',
			'decimal_separator'     => wc_get_price_decimal_separator(),
			'thousand_separator'    => wc_get_price_thousand_separator(),
			'decimals'              => wc_get_price_decimals(),
		);
		$rules    = wp_parse_args( $rules, $defaults );

		return apply_filters( 'woo_feed_pro_default_feed_rules', $rules );
	}

}

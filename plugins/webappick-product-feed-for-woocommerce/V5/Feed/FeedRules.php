<?php


namespace CTXFeed\V5\Feed;


use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Helper\FeedHelper;
use \WP_Error;

/**
 * Class Feed
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Feed
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Feed
 */
class FeedRules {
	/**
	 * Parse Feed Config/Rules to make sure that necessary array keys are exists
	 * this will reduce the uses of isset() checking
	 *
	 * @param array $rules rules to parse.
	 * @param string $context parsing context. useful for filtering, view, save, db, create etc.
	 *
	 * @return array
	 * @since 3.3.5 $context parameter added.
	 *
	 * @uses wp_parse_args
	 *
	 */
	public static function Parse( $rules = [], $context = 'view' ) {
		if ( empty( $rules ) ) {
			$rules = array();
		}
		// TODO validate all rules by checking all rules value type
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
			// Price Number Format
			'decimal_separator'     => wc_get_price_decimal_separator(),
			'thousand_separator'    => wc_get_price_thousand_separator(),
			'decimals'              => wc_get_price_decimals(),
		);

		/**
		 * Some previous feed is saving an error on this string.
		 * That is why this value should be array
		 */
		if( ! is_array($rules['product_ids']) ){
			$rules['product_ids'] = [];
		}

		$rules    = wp_parse_args( $rules, $defaults );
		$rules    = wp_parse_args( $rules, $defaults );
		// Product Filter Mode (Include or Exclude)
		$rules['filter_mode'] = wp_parse_args(
			$rules['filter_mode'],
			array(
				'product_ids' => 'include',
				'categories'  => 'include',
				'post_status' => 'include',
			)
		);
		// UTM Campaign parameter with GA4 Support
		$rules['campaign_parameters'] = wp_parse_args(
			$rules['campaign_parameters'],
			array(
				'utm_source'          => '',
				'utm_medium'          => '',
				'utm_campaign'        => '',
				'utm_term'            => '',
				'utm_id'              => '',
				'utm_source_platform' => '',
				'utm_content'         => '',
			)
		);

		if ( isset( $rules['provider'], $rules['feed_config_custom2'] ) && in_array( $rules['provider'], FeedHelper::get_custom2_merchant(), true ) ) {
			$rules['feed_config_custom2'] = trim( preg_replace( '/\\\\/', '', $rules['feed_config_custom2'] ) );
		}

		$str_replace = array(
			'subject' => '',
			'search'  => '',
			'replace' => '',
		);
		if ( empty( $rules['str_replace'] ) ) {
			$rules['str_replace'] = array( $str_replace );
		} else {
			foreach ( $rules['str_replace'] as $i => $iValue ) {
				$rules['str_replace'][ $i ] = wp_parse_args( $iValue, $str_replace );
			}
		}

		if ( ! empty( $rules['provider'] ) && is_string( $rules['provider'] ) ) {
			/**
			 * filter parsed rules for provider
			 *
			 * @param array $rules
			 * @param string $context
			 *
			 * @since 3.3.7
			 *
			 */
			$rules = apply_filters( "woo_feed_{$rules['provider']}_parsed_rules", $rules, $context );
		}

		/**
		 * filter parsed rules
		 *
		 * @param array $rules
		 * @param string $context
		 *
		 * @since 3.3.7 $provider parameter removed
		 *
		 */
		$rules = apply_filters( 'woo_feed_parsed_rules', $rules, $context );

		return $rules;
	}
}

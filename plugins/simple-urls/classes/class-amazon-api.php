<?php
/**
 * Declare class Config
 *
 * @package Config
 */

namespace LassoLite\Classes;

use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Cache_Per_Process;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;

use LassoLite\Libs\Amazon_Api_V5\AwsV5;

/**
 * Config
 */
class Amazon_Api {

	const OBJECT_KEY                                        = 'lasso_amazon_api';
	const FUNCTION_NAME_GET_LASSO_ID_BY_PRODUCT_ID_AND_TYPE = 'get_lasso_id_by_product_id_and_type';
	const PRODUCT_TYPE                                      = 'amazon';
	const SHORT_LINK_DOMAINS                                = array( 'amzn.com', 'amzn.to' );
	const FILTER_AMAZON_PRODUCT                             = 'filter_amazon_product';
	const TRACKING_ID_REGEX                                 = '^[a-zA-Z0-9-]+-\d{2,3}$';
	const CURRENCY_ISO                                      = array( 'USD', 'AUD', 'CAD', 'EUR', 'MXN', 'CNY', 'JPY', 'INR', 'SEK', 'BRL', 'TRY', 'GBP', 'PLN', 'EGP', 'SGD', 'AED' );
	const VARIATION_PAGE_LIMIT                              = 2;

	/**
	 * Get amazon API countries
	 */
	public static function get_amazon_api_countries() {
		return array(
			'us'  => array(
				'name'          => 'United States',
				'amazon_domain' => 'www.amazon.com',
				'pa_endpoint'   => 'webservices.amazon.com',
				'region'        => 'us-east-1',
			),
			'usa' => array(
				'name'          => 'United States',
				'amazon_domain' => 'www.amazon.com',
				'pa_endpoint'   => 'webservices.amazon.com',
				'region'        => 'us-east-1',
			),
			'au'  => array(
				'name'          => 'Australia',
				'amazon_domain' => 'www.amazon.com.au',
				'pa_endpoint'   => 'webservices.amazon.com.au',
				'region'        => 'us-west-2',
			),
			'aus' => array(
				'name'          => 'Australia',
				'amazon_domain' => 'www.amazon.com.au',
				'pa_endpoint'   => 'webservices.amazon.com.au',
				'region'        => 'us-west-2',
			),
			'br'  => array(
				'name'          => 'Brazil',
				'amazon_domain' => 'www.amazon.com.br',
				'pa_endpoint'   => 'webservices.amazon.com.br',
				'region'        => 'us-east-1',
			),
			'bra' => array(
				'name'          => 'Brazil',
				'amazon_domain' => 'www.amazon.com.br',
				'pa_endpoint'   => 'webservices.amazon.com.br',
				'region'        => 'us-east-1',
			),
			'ca'  => array(
				'name'          => 'Canada',
				'amazon_domain' => 'www.amazon.ca',
				'pa_endpoint'   => 'webservices.amazon.ca',
				'region'        => 'us-east-1',
			),
			'can' => array(
				'name'          => 'Canada',
				'amazon_domain' => 'www.amazon.ca',
				'pa_endpoint'   => 'webservices.amazon.ca',
				'region'        => 'us-east-1',
			),
			'cn'  => array(
				'name'          => 'China',
				'amazon_domain' => 'www.amazon.cn',
				'pa_endpoint'   => 'webservices.amazon.cn',
				'region'        => 'us-east-1',
			),
			'chn' => array(
				'name'          => 'China',
				'amazon_domain' => 'www.amazon.cn',
				'pa_endpoint'   => 'webservices.amazon.cn',
				'region'        => 'us-east-1',
			),
			'fr'  => array(
				'name'          => 'France',
				'amazon_domain' => 'www.amazon.fr',
				'pa_endpoint'   => 'webservices.amazon.fr',
				'region'        => 'eu-west-1',
			),
			'fra' => array(
				'name'          => 'France',
				'amazon_domain' => 'www.amazon.fr',
				'pa_endpoint'   => 'webservices.amazon.fr',
				'region'        => 'eu-west-1',
			),
			'de'  => array(
				'name'          => 'Germany',
				'amazon_domain' => 'www.amazon.de',
				'pa_endpoint'   => 'webservices.amazon.de',
				'region'        => 'eu-west-1',
			),
			'deu' => array(
				'name'          => 'Germany',
				'amazon_domain' => 'www.amazon.de',
				'pa_endpoint'   => 'webservices.amazon.de',
				'region'        => 'eu-west-1',
			),
			'in'  => array(
				'name'          => 'India',
				'amazon_domain' => 'www.amazon.in',
				'pa_endpoint'   => 'webservices.amazon.in',
				'region'        => 'eu-west-1',
			),
			'ind' => array(
				'name'          => 'India',
				'amazon_domain' => 'www.amazon.in',
				'pa_endpoint'   => 'webservices.amazon.in',
				'region'        => 'eu-west-1',
			),
			'it'  => array(
				'name'          => 'Italy',
				'amazon_domain' => 'www.amazon.it',
				'pa_endpoint'   => 'webservices.amazon.it',
				'region'        => 'eu-west-1',
			),
			'ita' => array(
				'name'          => 'Italy',
				'amazon_domain' => 'www.amazon.it',
				'pa_endpoint'   => 'webservices.amazon.it',
				'region'        => 'eu-west-1',
			),
			'jp'  => array(
				'name'          => 'Japan',
				'amazon_domain' => 'www.amazon.co.jp',
				'pa_endpoint'   => 'webservices.amazon.co.jp',
				'region'        => 'us-west-2',
			),
			'jpn' => array(
				'name'          => 'Japan',
				'amazon_domain' => 'www.amazon.co.jp',
				'pa_endpoint'   => 'webservices.amazon.co.jp',
				'region'        => 'us-west-2',
			),
			'mx'  => array(
				'name'          => 'Mexico',
				'amazon_domain' => 'www.amazon.com.mx',
				'pa_endpoint'   => 'webservices.amazon.com.mx',
				'region'        => 'us-east-1',
			),
			'mex' => array(
				'name'          => 'Mexico',
				'amazon_domain' => 'www.amazon.com.mx',
				'pa_endpoint'   => 'webservices.amazon.com.mx',
				'region'        => 'us-east-1',
			),
			'nl'  => array(
				'name'          => 'Netherlands',
				'amazon_domain' => 'www.amazon.nl',
				'pa_endpoint'   => 'webservices.amazon.nl',
				'region'        => 'eu-west-1',
			),
			'nld' => array(
				'name'          => 'Netherlands',
				'amazon_domain' => 'www.amazon.nl',
				'pa_endpoint'   => 'webservices.amazon.nl',
				'region'        => 'eu-west-1',
			),
			'se'  => array(
				'name'          => 'Sweden',
				'amazon_domain' => 'www.amazon.se',
				'pa_endpoint'   => 'webservices.amazon.se',
				'region'        => 'us-west-1',
			),
			'sek' => array(
				'name'          => 'Sweden',
				'amazon_domain' => 'www.amazon.se',
				'pa_endpoint'   => 'webservices.amazon.se',
				'region'        => 'us-west-1',
			),
			'sg'  => array(
				'name'          => 'Singapore',
				'amazon_domain' => 'www.amazon.sg',
				'pa_endpoint'   => 'webservices.amazon.sg',
				'region'        => 'us-west-2',
			),
			'sgp' => array(
				'name'          => 'Singapore',
				'amazon_domain' => 'www.amazon.sg',
				'pa_endpoint'   => 'webservices.amazon.sg',
				'region'        => 'us-west-2',
			),
			'es'  => array(
				'name'          => 'Spain',
				'amazon_domain' => 'www.amazon.es',
				'pa_endpoint'   => 'webservices.amazon.es',
				'region'        => 'eu-west-1',
			),
			'esp' => array(
				'name'          => 'Spain',
				'amazon_domain' => 'www.amazon.es',
				'pa_endpoint'   => 'webservices.amazon.es',
				'region'        => 'eu-west-1',
			),
			'tr'  => array(
				'name'          => 'Turkey',
				'amazon_domain' => 'www.amazon.com.tr',
				'pa_endpoint'   => 'webservices.amazon.com.tr',
				'region'        => 'eu-west-1',
			),
			'tur' => array(
				'name'          => 'Turkey',
				'amazon_domain' => 'www.amazon.com.tr',
				'pa_endpoint'   => 'webservices.amazon.com.tr',
				'region'        => 'eu-west-1',
			),
			'ae'  => array(
				'name'          => 'United Arab Emirates',
				'amazon_domain' => 'www.amazon.ae',
				'pa_endpoint'   => 'webservices.amazon.ae',
				'region'        => 'eu-west-1',
			),
			'are' => array(
				'name'          => 'United Arab Emirates',
				'amazon_domain' => 'www.amazon.ae',
				'pa_endpoint'   => 'webservices.amazon.ae',
				'region'        => 'eu-west-1',
			),
			'gb'  => array(
				'name'          => 'United Kingdom',
				'amazon_domain' => 'www.amazon.co.uk',
				'pa_endpoint'   => 'webservices.amazon.co.uk',
				'region'        => 'eu-west-1',
			),
			'gbr' => array(
				'name'          => 'United Kingdom',
				'amazon_domain' => 'www.amazon.co.uk',
				'pa_endpoint'   => 'webservices.amazon.co.uk',
				'region'        => 'eu-west-1',
			),
		);
	}

	/**
	 * Get ignore errors list
	 */
	public static function get_ignore_error_codes() {
		return array(
			'ItemNotAccessible',
			'InvalidParameterValue',
			'AccessDeniedException',
			'AccessDenied',
			'TooManyRequestsException',
			'TooManyRequests',
			'ThrottlingException',
			'RequestThrottled',
			'AWS.ThrottlingException',
			'AWS.RequestThrottled',
			'AWS.AccessDeniedException',
			'UnrecognizedClient',
		);
	}

	/**
	 * Get amazon domains
	 */
	public static function get_domains() {
		return array(
			'amazon.com',           // ? US
			'amazon.ca',            // ? Canada
			'amazon.co.uk',         // ? UK
			'amazon.com.au',        // ? Australia
			'amazon.com.br',        // ? Brazil
			'amazon.com.mx',        // ? Mexico
			'amazon.fr',            // ? France
			'amazon.de',            // ? Germany
			'amazon.it',            // ? Italy
			'amazon.in',            // ? India
			'amazon.es',            // ? Spain
			'amazon.cn',            // ? China
			'amazon.co.jp',         // ? Japan
			'amazon.nl',            // ? Netherlands
			'amazon.se',            // ? Sweden
			'amazon.sg',            // ? Singapore
			'amazon.com.tr',        // ? Turkey
			'amazon.ae',            // ? United Arab Emirates
			'amzn.com',             // ? Short URL
			'amzn.to',              // ? Short URL
			'amazon-adsystem.com',  // ? Amazon Embed
			'smile.amazon.com',      // ? Amazon Smile
		);
	}

	/**
	 * Get amazon link and flag
	 */
	public static function get_aff_link_and_flag() {
		return array(
			'www.amazon.com'    => array(
				'flag'     => '🇺🇸',
				'aff_link' => 'https://affiliate-program.amazon.com/',
			),
			'www.amazon.ca'     => array(
				'flag'     => '🇨🇦',
				'aff_link' => 'https://associates.amazon.ca/',
			),
			'www.amazon.com.br' => array(
				'flag'     => '🇧🇷',
				'aff_link' => 'https://associados.amazon.com.br/',
			),
			'www.amazon.com.mx' => array(
				'flag'     => '🇲🇽',
				'aff_link' => 'https://afiliados.amazon.com.mx/',
			),
			'www.amazon.fr'     => array(
				'flag'     => '🇫🇷',
				'aff_link' => 'https://partenaires.amazon.fr/',
			),
			'www.amazon.de'     => array(
				'flag'     => '🇩🇪',
				'aff_link' => 'https://partnernet.amazon.de/',
			),
			'www.amazon.it'     => array(
				'flag'     => '🇮🇹',
				'aff_link' => 'https://programma-affiliazione.amazon.it/',
			),
			'www.amazon.es'     => array(
				'flag'     => '🇪🇸',
				'aff_link' => 'https://afiliados.amazon.es/',
			),
			'www.amazon.co.uk'  => array(
				'flag'     => '🇬🇧',
				'aff_link' => 'https://affiliate-program.amazon.co.uk/',
			),
			'www.amazon.cn'     => array(
				'flag'     => '🇨🇳',
				'aff_link' => 'https://associates.amazon.cn/',
			),
			'www.amazon.co.jp'  => array(
				'flag'     => '🇯🇵',
				'aff_link' => 'https://affiliate.amazon.co.jp/',
			),
			'www.amazon.in'     => array(
				'flag'     => '🇮🇳',
				'aff_link' => 'https://affiliate-program.amazon.in/',
			),
			'www.amazon.com.au' => array(
				'flag'     => '🇦🇺',
				'aff_link' => 'https://affiliate-program.amazon.com.au/',
			),
		);
	}

	/**
	 * Check whether a url is amazon link or not
	 *
	 * @param string $url Url.
	 */
	public static function is_amazon_url( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		$domains = self::get_domains();
		$url     = Helper::add_https( $url );

		if ( ! Helper::validate_url( $url ) ) {
			return false;
		}

		$parse_url = wp_parse_url( $url );
		if ( ! isset( $parse_url['host'] ) ) {
			return false;
		}

		$domain = ltrim( $parse_url['host'], 'www.' );

		if ( in_array( $domain, $domains, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check whether a url is amazon link or not
	 *
	 * @param string $url Url.
	 */
	public static function is_amazon_shortented_url( $url ) {
		$is_amazon_url     = self::is_amazon_url( $url );
		$is_shortented_url = strpos( $url, 'amzn.to' ) || strpos( $url, 'amzn.com' );

		return $is_amazon_url && $is_shortented_url;
	}

	/**
	 * Search amazon product
	 *
	 * @param string $keyword Keyword: product name,...
	 */
	public function search_product( $keyword ) {
		$result = $this->get_product_by_keyword_v5( $keyword, 'All' );
		if ( isset( $result->SearchResult->Items ) ) { // phpcs:ignore
			$items    = $result->SearchResult->Items; // phpcs:ignore
			$products = array();

			foreach ( $items as $item ) {
				$product = $this->extract_search_result_v5( $item );
				array_push( $products, $product );
			}

			return $products;

		} elseif ( isset( $result->Errors ) ) { // phpcs:ignore
			return array(
				'error' => $result->Errors, // phpcs:ignore
			);
		}
	}

	/**
	 * Check whether product url has the same domain with Amazon settings
	 *
	 * @param string $product_url Amazon link.
	 */
	public function is_same_domain( $product_url ) {
		if ( '' === $product_url ) {
			return false;
		}

		$amazon_default_tracking_country = Setting::get_setting( 'amazon_default_tracking_country', 'usa' );
		$all_countries                   = self::get_amazon_api_countries();
		$domain                          = $all_countries[ $amazon_default_tracking_country ]['amazon_domain'] ?? 'www.amazon.com';
		$domain                          = str_replace( 'www.', '', $domain );

		return strpos( $product_url, $domain ) !== false;
	}

	/**
	 * Fetch amazon product from Amazon API v5
	 *
	 * @param string      $product_id    Amazon product id.
	 * @param bool        $store_product Store product into DB or not. Default to false.
	 * @param bool|string $updated_at    Set date time or not. Default to false.
	 * @param string      $amz_link      Amazon link. Default to empty.
	 * @return mixed
	 */
	public function fetch_product_info( $product_id, $store_product = false, $updated_at = false, $amz_link = '' ) {
		$lasso_db = new Lasso_DB();

		$lasso_settings       = Setting::get_settings();
		$is_amazon_configured = $lasso_settings['amazon_access_key_id'] && $lasso_settings['amazon_secret_key'] && $lasso_settings['amazon_tracking_id'];
		$result               = $is_amazon_configured && $this->is_same_domain( $amz_link ) ? $this->get_product_by_id_v5( $product_id ) : false;
		// phpcs:ignore
		if ( isset( $result->Errors[0] ) && (
				"The ItemId $product_id provided in the request is invalid." === $result->Errors[0]->Message // phpcs:ignore
				|| "The value [$product_id] provided in the request for ItemIds is invalid." === $result->Errors[0]->Message // phpcs:ignore
			)
		) {
			return array(
				'product'    => array(),
				'api'        => 'yes',
				'full_item'  => array(),
				'status'     => 'failed',
				'error_code' => 'NotFound',
			);
		} elseif ( isset( $result->ItemsResult->Items[0] ) ) { // phpcs:ignore
			$item = $result->ItemsResult->Items[0]; // phpcs:ignore

			$product                = $this->extract_search_result_v5( $item, true );
			$product_url            = $amz_link ? $amz_link : $product['url'];
			$product['status_code'] = 200;
			$product['url']         = self::get_amazon_product_url( $product_url );

			// ? If $item->Offers is missing, we try to get the quantity from variation data
			if ( ! isset( $item->Offers ) ) { // phpcs:ignore
				sleep( 1 ); // ? Delay for a while before call the next request
				$variation_product = $this->get_product_variation( $product_id, $product_url );
				if ( $variation_product && isset( $variation_product['quantity'] ) && $variation_product['quantity'] ) {
					$product = $variation_product;
				}
			}

			// ? Get Lasso ID
			$query               = '
                SELECT post_id 
                FROM ' . $lasso_db->postmeta . "
                WHERE meta_key = 'amazon_product_id' AND meta_value = '" . $product['product_id'] . "'
            ";
			$lasso_id            = $lasso_db->get_var( $query );
			$product['lasso_id'] = ( isset( $lasso_id ) ) ? $lasso_id : 0;

			if ( $store_product ) {
				$amazon_tracking_id     = Setting::get_setting( 'amazon_tracking_id', '' );
				$product['default_url'] = '' === $amazon_tracking_id ? $amz_link : $product['default_url'];
				$this->update_amazon_product_in_db( $product, $updated_at );
			}

			return array(
				'product'    => $product,
				'api'        => 'yes',
				'full_item'  => $item,
				'status'     => 'success',
				'error_code' => '',
			);
		} else {
			return array();
		}
	}

	/**
	 * Insert or Update Amazon Product Data
	 *
	 * @param array       $product    Amazon product.
	 * @param bool|string $updated_at Set update date time. Default to false.
	 */
	public function update_amazon_product_in_db( $product, $updated_at = false ) {
		global $wpdb;

		$lasso_db = new Lasso_DB();

		$amazon_id            = $product['product_id'] ?? '';
		$default_product_name = $product['title'] ?? '';
		$latest_price         = $product['price'] ?? '';
		$latest_price         = '0' === $latest_price || ( is_int( $latest_price ) && 0 === $latest_price ) ? '' : $latest_price;
		$base_url             = self::get_amazon_product_url( $product['default_url'] ?? '', false, false );
		$monetized_url        = self::get_amazon_product_url( $product['url'] ?? '', true, false );
		$default_image        = trim( $product['image'] ?? '' );
		$last_updated         = gmdate( 'Y-m-d H:i:s', time() );
		$last_updated         = $updated_at ? $updated_at : $last_updated;
		$is_prime             = $product['is_prime'] ?? '';
		$currency             = $product['currency'] ?? '';
		$features             = wp_json_encode( $product['features'] ?? array() );
		$savings_amount       = $product['savings_amount'] ?? '';
		$savings_percent      = $product['savings_percent'] ?? '';
		$savings_basis        = $product['savings_basis'] ?? '';
		$is_manual            = $product['is_manual'] ?? 0;
		$quantity             = intval( $product['quantity'] ?? 200 );
		$out_of_stock         = 0 === $quantity ? 1 : 0;

		if ( '' === $amazon_id || '' === $default_product_name || '' === $default_image
			|| ( '' !== $default_image && Helper::validate_url( $default_image ) === false && strpos( $default_image, 'data:image' ) !== 0 )
		) {
			return false;
		}

		$base_url      = trim( $base_url );
		$monetized_url = trim( $monetized_url );

		$query   = '
            INSERT INTO ' . $lasso_db->amazon_products . "
                (
                    amazon_id, default_product_name, latest_price, base_url, 
                    monetized_url, default_image, last_updated, is_prime, 
                    currency, features, savings_amount, savings_percent, 
                    savings_basis, is_manual, out_of_stock
                )
            VALUES
                (
                    %s, %s, %s, %s, 
                    %s, %s, %s, %d, 
                    %s, %s, %s, %d,
                    %s, %d, %d
                )
            ON DUPLICATE KEY UPDATE
                amazon_id = %s,
                default_product_name = %s,
                latest_price = %s,
                base_url = %s,
                monetized_url = %s,
                default_image = (CASE WHEN %s='' or %s IS NULL THEN `default_image` ELSE %s END),
                last_updated = %s,
                is_prime = %d,
                currency  = %s,
                features = %s,
                savings_amount = %s,
                savings_percent = %d,
                savings_basis = %s,
                is_manual = %d,
                out_of_stock = %d
            ;
		";
		$prepare = $wpdb->prepare(
		// phpcs:ignore
			$query,
			// ? First for insert
			$amazon_id,
			$default_product_name,
			$latest_price,
			$base_url,
			$monetized_url,
			$default_image,
			$last_updated,
			$is_prime,
			$currency,
			$features,
			$savings_amount,
			$savings_percent,
			$savings_basis,
			$is_manual,
			$out_of_stock,
			// ? Second for update
			$amazon_id,
			$default_product_name,
			$latest_price,
			$base_url,
			$monetized_url,
			$default_image,
			$default_image,
			$default_image,
			$last_updated,
			$is_prime,
			$currency,
			$features,
			$savings_amount,
			$savings_percent,
			$savings_basis,
			$is_manual,
			$out_of_stock
		);

		$lasso_db->query( $prepare );

		return true;
	}

	/**
	 * Get amazon product from DB
	 *
	 * @param string $product_id Amazon Product id.
	 */
	public function get_amazon_product_from_db( $product_id ) {
		if ( empty( $product_id ) ) {
			return false;
		}

		global $wpdb;

		$lasso_db = new Lasso_DB();

		$sql = '
			SELECT * 
			FROM ' . $lasso_db->amazon_products . ' 
			WHERE amazon_id = %s
		';

		$prepare = $wpdb->prepare( $sql, $product_id ); // phpcs:ignore
		$result  = $lasso_db->get_row( $prepare, ARRAY_A );

		if ( $result ) {
			$result                  = apply_filters( self::FILTER_AMAZON_PRODUCT, $result );
			$result['monetized_url'] = self::get_amazon_product_url( $result['monetized_url'] );
			$result['base_url']      = self::get_amazon_product_url( $result['base_url'], false );
			$result['features']      = json_decode( $result['features'] );
		}

		return $result;
	}

	/**
	 * Extract result to an array
	 *
	 * @param object $response    Data from Amazon.
	 * @param bool   $large_image Get large image size. Default to false.
	 * @param string $product_id  Product Id. Default to empty.
	 * @param string $product_url Product url. Default to empty.
	 *
	 * @return array
	 */
	private function extract_search_result_v5( $response, $large_image = false, $product_id = '', $product_url = '' ) {
		$image = '';
		if ( isset( $response->Images->Primary ) ) { // phpcs:ignore
			$image = $large_image ? $response->Images->Primary->Large->URL : $response->Images->Primary->Small->URL; // phpcs:ignore
		}

		// @codingStandardsIgnoreStart
		$result = array(
			'product_id'      => $product_id ? $product_id : ( $response->ASIN ?? 0 ),
			'title'           => $response->ItemInfo->Title->DisplayValue ?? '',
			'url'             => $product_url ? $product_url : ( $response->DetailPageURL ?? '' ),
			'default_url'     => $response->DetailPageURL ?? '',
			'image'           => $image,
			'quantity'        => $response->Offers->Summaries[0]->OfferCount ?? 0,
			'is_prime'        => $response->Offers->Listings[0]->DeliveryInfo->IsPrimeEligible ?? false,
			'price'           => $response->Offers->Listings[0]->Price->DisplayAmount ?? 0,
			'amount'          => $response->Offers->Listings[0]->Price->Amount ?? 0,
			'currency'        => $response->Offers->Listings[0]->Price->Currency ?? '',
			'features'        => $response->ItemInfo->Features->DisplayValues ?? array(),
			'savings_amount'  => $response->Offers->Listings[0]->Price->Savings->Amount ?? 0.0,
			'savings_percent' => $response->Offers->Listings[0]->Price->Savings->Percentage ?? 0,
			'savings_basis'   => $response->Offers->Listings[0]->SavingBasis->Amount ?? 0.0,
		);
		// @codingStandardsIgnoreEnd

		return $result;
	}

	/**
	 * Query amazon v5
	 *
	 * @param array   $parameters      Amazon API params.
	 * @param boolean $lasso_settings Lasso settings. Default to false.
	 *
	 * @return array
	 */
	public function query_amazon_v5( $parameters, $lasso_settings = false ) {
		try {
			if ( ! $lasso_settings ) {
				$lasso_settings = Setting::get_settings();
			}

			$this->amazon_access_key_id = $lasso_settings['amazon_access_key_id'] ?? '';
			$this->amazon_secret_key    = $lasso_settings['amazon_secret_key'] ?? '';
			$this->amazon_tracking_id   = $lasso_settings['amazon_tracking_id'] ?? '';

			$result = $this->aws_signed_request_v5( $parameters, $this->amazon_access_key_id, $this->amazon_secret_key, $this->amazon_tracking_id );

			if ( isset( $result->Errors ) ) { // phpcs:ignore
				$error = $result->Errors[0]; // phpcs:ignore
			}

			return $result;
		} catch ( \Exception $e ) {
			return array();
		}
	}

	/**
	 * Get Amazon product by product id
	 *
	 * @param string $product_id Amazon product id.
	 *
	 * @return object
	 */
	public function get_product_by_id_v5( $product_id ) {
		$parameters = array(
			'Operation' => 'GetItems',
			'ItemIds'   => array( $product_id ),
			'Resources' => array(
				'Images.Primary.Small',
				'Images.Primary.Large',
				'ItemInfo.Title',
				'ItemInfo.ContentRating',
				'ItemInfo.Features',
				'ItemInfo.ProductInfo',
				'ItemInfo.TechnicalInfo',
				'Offers.Listings.Price',
				'Offers.Listings.SavingBasis',
				'Offers.Summaries.OfferCount',
				'Offers.Listings.DeliveryInfo.IsPrimeEligible',
			),
		);

		$json_response = $this->query_amazon_v5( $parameters );

		return $json_response;
	}

	/**
	 * Get product from Amazon by product name
	 *
	 * @param string $keyword      Keyword.
	 * @param string $product_type Product type.
	 *
	 * @return object
	 */
	public function get_product_by_keyword_v5( $keyword, $product_type ) {
		$parameters = array(
			'Operation'   => 'SearchItems',
			'Keywords'    => $keyword,
			'SearchIndex' => $product_type,
			'Resources'   => array(
				'Images.Primary.Small',
				'Images.Primary.Large',
				'ItemInfo.Title',
				'ItemInfo.ContentRating',
				'ItemInfo.Features',
				'ItemInfo.ProductInfo',
				'ItemInfo.TechnicalInfo',
				'Offers.Listings.Price',
				'Offers.Listings.SavingBasis',
				'Offers.Summaries.OfferCount',
				'Offers.Listings.DeliveryInfo.IsPrimeEligible',
			),
		);

		$json_response = $this->query_amazon_v5( $parameters );

		return $json_response;
	}

	/**
	 * Sign request v5
	 *
	 * @param array  $params               Amazon params.
	 * @param string $amazon_access_key_id Amazon access key.
	 * @param string $amazon_secret_key    Amazon secret key.
	 * @param string $amazon_tracking_id   Amazon tracking id.
	 *
	 * @return object|bool
	 */
	private function aws_signed_request_v5( $params, $amazon_access_key_id, $amazon_secret_key, $amazon_tracking_id ) {
		// phpcs:ignore
		// $amazon_domain = 'www.amazon.com';
		// $pa_endpoint = 'webservices.amazon.com';

		$country       = Setting::get_setting( 'amazon_default_tracking_country', 'usa' );
		$countries     = self::get_amazon_api_countries();
		$amazon_domain = $countries[ $country ]['amazon_domain'];
		$pa_endpoint   = $countries[ $country ]['pa_endpoint'];
		$amazon_region = $countries[ $country ]['region'];

		$params['Marketplace'] = $amazon_domain;
		$params['PartnerType'] = 'Associates';
		$params['PartnerTag']  = $amazon_tracking_id;
		$post_fields           = wp_json_encode( $params );

		$aws_v5 = new AwsV5( $amazon_access_key_id, $amazon_secret_key );
		$aws_v5->setHost( $pa_endpoint );
		$aws_v5->setRegionName( $amazon_region );
		$aws_v5->setPayload( $post_fields );
		$aws_v5->addHeader( 'x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.' . $params['Operation'] );
		$headers = $aws_v5->getHeaders( true );
		$url     = "https://$pa_endpoint/paapi5/searchitems";

		// @codingStandardsIgnoreStart
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_fields );
		curl_setopt( $ch, CURLOPT_POST, 1 );

		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$result = curl_exec( $ch );
		if ( curl_errno( $ch ) ) {
			// phpcs:ignore
			// $error = curl_error( $ch );
			return false;
		}
		curl_close( $ch );
		// @codingStandardsIgnoreEnd

		return json_decode( $result );
	}

	/**
	 * Get Amazon product id by url
	 *
	 * @param string $url Amazon link.
	 * @return string|bool
	 */
	public static function get_product_id_by_url( $url ) {
		$url = Helper::add_https( $url );

		if ( ! self::is_amazon_url( $url ) || strpos( $url, '.' ) === false ) {
			return false;
		}

		$parse_url           = wp_parse_url( $url );
		$amazon_domain       = trim( $parse_url['host'] ?? '', 'www.' );
		$amazon_domain_regex = str_replace( '.', '\.', str_replace( 'www.', '', $amazon_domain ) );

		$reg     = '#(?:https?://(?:www\.){0,1}' . $amazon_domain_regex . '(?:/.*){0,1}(?:/dp/|/gp/product/|/ASIN/|/gp/video/detail/))([a-zA-Z0-9]*)(.*?)(?:/.*|$)#';
		$matches = array();
		preg_match( $reg, $url, $matches );

		return isset( $matches[1] ) && ! empty( $matches[1] ) ? $matches[1] : false;
	}

	/**
	 * Get amazon tracking id by url
	 * This function will be deprecated in the future. Please use Helper::get_argument_from_url() instead
	 *
	 * @param string $link Amazon link.
	 * @return string
	 */
	public static function get_amazon_tracking_id_by_url( $link ) {
		$search  = '/([?|&|&amp;|\/]{1})tag=([a-zA-Z0-9\-\_]*)/i';
		$matches = array();
		preg_match( $search, $link, $matches );

		return $matches[2] ?? '';
	}

	/**
	 * Make product url to shorten url if this setting is enabled
	 *
	 * @param string $product_url        Amazon product url.
	 * @param bool   $monetize           Monetize link or not. Default to true.
	 * @param bool   $check_lasso_post   Check Lasso post or ignore. Default to true.
	 * @param string $custom_tracking_id Allow to use custom tracking id.
	 */
	public static function get_amazon_product_url( $product_url, $monetize = true, $check_lasso_post = true, $custom_tracking_id = '' ) {
		// ? amazon link but it is not product url
		if ( ! self::is_amazon_url( $product_url ) || self::is_amazon_shortented_url( $product_url ) ) {
			return $product_url;
		}

		$product_id = self::get_product_id_by_url( $product_url );

		$tag    = Helper::get_argument_from_url( $product_url, 'tag' );
		$maas   = Helper::get_argument_from_url( $product_url, 'maas' );
		$ref_   = Helper::get_argument_from_url( $product_url, 'ref_' );
		$s_args = Helper::get_argument_from_url( $product_url, 's' );

		$maas_args = $maas ? 'maas=' . $maas : '';
		$ref__args = $ref_ ? 'ref_=' . $ref_ : '';
		$s_args    = $s_args ? 's=' . $s_args : '';

		// ? remove all url queries, just keep needed args
		$url_without_params = explode( '?', $product_url )[0];
		if ( $product_id && ! $monetize ) {
			$args = Helper::build_url_parameter_string( array( $maas_args, $ref__args, $s_args ) );

			return $args ? $url_without_params . '?' . $args : $url_without_params;
		}

		$lasso_settings               = Setting::get_settings();
		$amazon_multiple_tracking_id  = $lasso_settings['amazon_multiple_tracking_id'] ?? true;
		$amazon_tracking_id_whitelist = $lasso_settings['amazon_tracking_id_whitelist'] ?? array();
		$amazon_tracking_id           = trim( $lasso_settings['amazon_tracking_id'] ?? '' );

		if ( ! $amazon_multiple_tracking_id ) {
			$amazon_tracking_id_whitelist = array();
		}

		$lasso_db      = new Lasso_DB();
		$amz_cache_key = self::OBJECT_KEY . '_' . self::FUNCTION_NAME_GET_LASSO_ID_BY_PRODUCT_ID_AND_TYPE . '_' . $product_id . '_' . self::PRODUCT_TYPE;
		$lasso_id      = Cache_Per_Process::get_instance()->get_cache( $amz_cache_key, null );
		if ( null === $lasso_id ) {
			$lasso_id = $lasso_db->get_lasso_id_by_product_id_and_type( $product_id );
			Cache_Per_Process::get_instance()->set_cache( $amz_cache_key, $lasso_id );
		}

		if ( $check_lasso_post && ! $lasso_id ) {
			return $product_url;
		}

		$tag = $custom_tracking_id ? $custom_tracking_id : $tag;
		$tag = $tag ? $tag : '';
		$tag = ! empty( $amazon_tracking_id ) && ! in_array( $tag, $amazon_tracking_id_whitelist, true )
			? $amazon_tracking_id : $tag;

		// ? Return the remove all url queries for product url
		if ( $product_id ) {
			$tag_args = $tag ? 'tag=' . $tag : '';
			$args     = Helper::build_url_parameter_string( array( $tag_args, $maas_args, $ref__args, $s_args ) );

			$product_url = $args ? $url_without_params . '?' . $args : $url_without_params;
		} else {
			$product_url = str_replace( '&amp;', '&', $product_url );
			$parse       = wp_parse_url( $product_url );
			parse_str( $parse['query'] ?? '', $query );

			// ? set tag id (tracking id) at the end of the url
			if ( $tag ) {
				$query['tag'] = $tag;
			} elseif ( ! empty( $amazon_tracking_id ) ) {
				$query['tag'] = $amazon_tracking_id;
			}

			if ( ! $monetize ) {
				unset( $query['tag'] );
			}

			$parse['query'] = Helper::get_query_from_array( $query );
			$product_url    = Helper::get_url_from_parse( $parse );
			$product_url    = trim( $product_url );
			$product_url    = trim( $product_url, '?' );
		}

		return $product_url;
	}

	/**
	 * Get Amazon product info from url accept url or post_id
	 *
	 * @param string|int $url_or_post_id URL or post id.
	 */
	public function get_amazon_product( $url_or_post_id ) {

		if ( is_numeric( $url_or_post_id ) ) {
			// ? get amazon product using post_id
			$post_id   = $url_or_post_id;
			$amazon_id = Affiliate_Link::get_amazon_id( $post_id );
			$product   = $this->get_amazon_product_from_db( $amazon_id );
			return $product;
		} else {
			$url = $url_or_post_id;
		}

		// ? get amazon prodcut using url
		if ( empty( $url ) ) {
			return '';
		}

		$url            = trim( $url, '/' );
		$product_id     = self::get_product_id_by_url( $url );
		$product        = $this->fetch_product_info( $product_id, true ); // ? Let's save all Amazon details as well
		$amazon_product = '';

		if ( 'success' === $product['status'] ) {
			$product        = $product['product'];
			$shorten_url    = self::get_amazon_product_url( $product['url'] );
			$amazon_product = array(
				'id'          => $product['product_id'],
				'name'        => $product['title'],
				'price'       => $product['price'],
				'url'         => $shorten_url,
				'image'       => $product['image'],
				'description' => '',
			);
		}

		return $amazon_product;
	}

	/**
	 * Check whether a URL is amazon search page
	 *
	 * @param string $url URL.
	 *
	 * @return bool|string
	 */
	public static function is_amazon_search_page( $url ) {
		$amazon_id      = self::get_product_id_by_url( $url );
		$parse          = wp_parse_url( $url );
		$path           = $parse['path'] ?? '';
		$path           = rtrim( $path, '/' );
		$keywords       = Helper::get_argument_from_url( $url, 'keywords' );
		$field_keywords = Helper::get_argument_from_url( $url, 'field-keywords' );
		$k              = Helper::get_argument_from_url( $url, 'k' );
		$k              = $k ? $k : $field_keywords;
		$k              = $k ? $k : $keywords;

		if ( ! $amazon_id && ( '/s' === substr( $path, -2 ) || strpos( $path, '/s/' ) !== false ) && $k ) {
			return $k;
		}

		return false;
	}

	/**
	 * Check whether a URL is amazon search page
	 *
	 * @param string $url URL.
	 *
	 * @return bool|string
	 */
	public static function get_search_page_title( $url ) {
		$new_title = 'Amazon';
		$k         = self::is_amazon_search_page( $url );
		if ( $k ) {
			$base_domain  = Helper::get_base_domain( $url );
			$title_prefix = ucfirst( $base_domain );
			$new_title    = $title_prefix . ' : ' . $k;
		}

		return $new_title;
	}

	/**
	 * Validate Amazon tracking id
	 *
	 * @param string $tracking_id Amazon tracking id.
	 * @return boolean
	 */
	public static function validate_tracking_id( $tracking_id ) {
		return (bool) preg_match( '/' . self::TRACKING_ID_REGEX . '/i', $tracking_id );
	}

	/**
	 * Get Amazon link by product id
	 *
	 * @param string $product_id Amazon product id.
	 * @param string $amz_link   Amazon link. Default to empty.
	 */
	public function get_amazon_link_by_product_id( $product_id, $amz_link = '' ) {
		if ( ! $product_id ) {
			return $amz_link;
		}

		if ( '' !== $amz_link ) {
			$parse = wp_parse_url( $amz_link );
			$host  = $parse['host'] ?? '';
			if ( '' !== $host ) {
				return 'https://' . $host . '/dp/' . $product_id;
			}
		}

		$country       = Setting::get_setting( 'amazon_default_tracking_country', 'usa' );
		$countries     = self::get_amazon_api_countries();
		$amazon_domain = $countries[ $country ]['amazon_domain'];

		return 'https://' . $amazon_domain . '/dp/' . $product_id;
	}

	/**
	 * Check whether a URL is Amazon redirect page
	 *
	 * @param string $url Amazon URL.
	 */
	public static function is_amazon_redirect_page( $url ) {
		if ( ! self::is_amazon_url( $url ) || strpos( $url, '/gp/slredirect/' ) === false ) {
			return false;
		}

		return true;
	}

	/**
	 * Get redirect url
	 *
	 * @param string $url Amazon URL.
	 */
	public static function get_redirect_url( $url ) {
		if ( self::is_amazon_redirect_page( $url ) ) {
			$url_param     = Helper::get_argument_from_url( $url, 'url' );
			$amazon_domain = Helper::get_base_domain( $url );
			$amazon_domain = Helper::add_https( $amazon_domain );
			$new_url       = $amazon_domain . $url_param;
			$product_id    = self::get_product_id_by_url( $new_url );

			if ( $product_id ) {
				$url = $new_url;
			}
		}

		return $url;
	}

	/**
	 * Format price
	 * Ex: convert 19.89USD to $19.89
	 *
	 * @param string $price        Price.
	 * @param string $currency_iso Currency ISO.
	 * @return string
	 */
	public static function format_price( $price, $currency_iso = null ) {
		$currency_iso = $currency_iso ? $currency_iso : self::get_currency_iso_from_price_text( $price );

		if ( $price && $currency_iso ) {
			return self::build_price_with_currency_iso( $price, $currency_iso );
		}

		return $price;
	}

	/**
	 * Get Currency ISO from price text
	 *
	 * @param string $price Price text.
	 * @return mixed|string
	 */
	public static function get_currency_iso_from_price_text( $price ) {
		$result = '';

		foreach ( self::CURRENCY_ISO as $currency_iso ) {
			if ( strpos( $price, $currency_iso ) !== false ) {
				return $currency_iso;
			}
		}

		return $result;
	}

	/**
	 * Build price final format base on the currency ISO
	 *
	 * @param string $price_value  Price value.
	 * @param string $currency_iso Currency ISO.
	 * @return string
	 */
	public static function build_price_with_currency_iso( $price_value, $currency_iso ) {
		$currency_symbol   = Helper::get_currency_symbol_from_iso_code( $currency_iso );
		$price_without_iso = str_replace( $currency_iso, '', $price_value );
		$price_value       = Helper::get_price_value_from_price_text( $price_without_iso, $currency_symbol );
		$currency_position = preg_match( '/[€]|R\$|TL|kr|zł/', $currency_symbol ) ? 'end' : 'begin';
		$price_format      = preg_match( '/[€]|R\$|TL|kr|zł/', $currency_symbol ) ? number_format( $price_value, 2, ',', '.' ) : number_format( $price_value, 2, '.', ',' );

		return 'begin' === $currency_position ? $currency_symbol . $price_format : $price_format . $currency_symbol;
	}

	/**
	 * Get the variation item in stock
	 *
	 * @param string $product_id     Product id.
	 * @param string $product_url    Product url.
	 * @param int    $variation_page Variation page.
	 * @return array
	 */
	public function get_product_variation( $product_id, $product_url, $variation_page = 1 ) {
		$result = $this->get_product_variations_by_id_v5( $product_id, $variation_page );
		$items  = $result->VariationsResult->Items ?? array(); // phpcs:ignore

		if ( ! empty( $items ) ) {
			$items              = $result->VariationsResult->Items; // phpcs:ignore
			$product_variations = array();

			// ? Get product variation list
			foreach ( $items as $item ) {
				$product_variations[] = $this->extract_search_result_v5( $item, true, $product_id, $product_url );
			}

			// ? Sort price from lowest to highest
			usort(
				$product_variations,
				function( $a, $b ) {
					return strcmp( $a['amount'], $b['amount'] );
				}
			);

			// ? Get the in-stock product
			foreach ( $product_variations as $product_variation ) {
				if ( $product_variation['quantity'] && $product_variation['price'] ) {
					return $product_variation;
				}
			}

			// ? If all variation products in this page unavailable, we request to the next page
			$page_count = $result->VariationsResult->VariationSummary->PageCount ?? 1; // phpcs:ignore
			if ( $variation_page < self::VARIATION_PAGE_LIMIT && $variation_page < $page_count ) {
				sleep( 1 ); // ? Delay for a while before call the next request
				return $this->get_product_variation( $product_id, $product_url, $variation_page + 1 );
			}
		}

		return array();
	}

	/**
	 * Get Amazon product variations by product id
	 *
	 * @param string $product_id     Amazon product id.
	 * @param int    $variation_page Variation page.
	 *
	 * @return object
	 */
	public function get_product_variations_by_id_v5( $product_id, $variation_page = 1 ) {
		$parameters = array(
			'Operation'     => 'GetVariations',
			'ASIN'          => $product_id,
			'Condition'     => 'New',
			'VariationPage' => $variation_page,
			'Resources'     => array(
				'Images.Primary.Small',
				'Images.Primary.Large',
				'ItemInfo.Title',
				'ItemInfo.ContentRating',
				'ItemInfo.Features',
				'ItemInfo.ProductInfo',
				'ItemInfo.TechnicalInfo',
				'Offers.Listings.Price',
				'Offers.Listings.SavingBasis',
				'Offers.Summaries.OfferCount',
				'Offers.Listings.DeliveryInfo.IsPrimeEligible',
			),
		);

		$json_response = $this->query_amazon_v5( $parameters );

		return $json_response;
	}
}

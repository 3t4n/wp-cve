<?php

namespace CTXFeed\V5\API\V1;

use CTXFeed\V5\API\RestController;
use WP_REST_Server;
/**
 * Class ProductTaxonomy
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class ProductTaxonomy extends RestController {

	/**
	 * Default country code
	 * @var string
	 */
	protected $country_code = 'en-US';
	/**
	 * Default merchant
	 * @var string
	 */
	protected $merchant = 'google';
	/**
	 * Default file name will be with id Example: taxonomy-with-ids.en-US.txt. If $with_id is false file name
	 * Example: taxonomy.en-US.txt for google merchant.
	 * this role will not applicable with facebook catalog.
	 * @var bool
	 */
	protected $with_id = true;
	protected $ext = 'txt';

	/**
	 * The single instance of the class
	 *
	 * @var ProductTaxonomy
	 *
	 */
	protected static $_instance = null;

	private function __construct() {
		parent::__construct();
		$this->rest_base = 'product_taxonomy';
	}

	/**
	 * Main ProductTaxonomy Instance.
	 *
	 * Ensures only one instance of ProductTaxonomy is loaded or can be loaded.
	 *
	 * @return ProductTaxonomy Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				/**
				 * @method GET
				 * @endpoint wp-json/ctxfeed/v1/product_taxonomy/?country_code=en-US&merchant=google
				 * @description  will return stored merchant data.
				 * @param $country_code
				 * @param $merchant
				 *
				 * @endpoint wp-json/ctxfeed/v1/product_taxonomy/?country_code=en-US&merchant=google&update=true
				 * @description  Download merchant taxonomy file.
				 * @param $country_code String
				 * @param $merchant String
				 * @param $update Boolean if true the taxonomy file will be downloaded based on merchant and country code.
				 */
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'manage_taxonomy' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'country_code' => [
							'description' => __( 'Country code.' ),
							'type'        => 'string',
							'required'    => true
						],
						'merchant'     => [
							'description' => __( 'Merchant name' ),
							'type'        => 'string',
							'required'    => true
						],
						'with_id'      => [
							'description' => __( 'If true then taxonomy will be downloaded with id' ),
							'type'        => 'boolean',
							'required'    => false,
							'default'     => true
						],
						'ext'          => [
							'description' => __( 'File extension default is txt' ),
							'type'        => 'string',
							'required'    => false,
							'default'     => 'txt'
						],
						'update'       => [
							'description' => __( 'Should update if value is true' ),
							'type'        => 'boolean',
							'required'    => false,
							'default'     => false
						],
					],
				],
			]
		);
	}

	/**
	 * Base on parameter taxonomy file either updated or try to get file from specific folder( WOO_FEED_FREE_ADMIN_URL . 'partials/templates/taxonomies/')
	 * if parameter 'update' is passed, and it's value  is true then file will be updated.
	 * else file will be, get.
	 *
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function manage_taxonomy( $request ) {
		$this->country_code = $request->get_param( 'country_code' );
		$this->merchant     = $request->get_param( 'merchant' );
		$this->with_id      = $request->get_param( 'with_id' );
		$this->ext          = $request->get_param( 'ext' );
		$is_update          = $request->get_param( 'update' );

		if ( true == $is_update ) {
			$this->update_taxonomy();
		} else {
			$this->get_taxonomy();
		}

		return $this->success( $this->response );
	}

	/**
	 *
	 * @return bool
	 */
	public function get_taxonomy() {
		$url = WOO_FEED_FREE_ADMIN_URL . 'partials/templates/taxonomies/';
		if ( 'google' == $this->merchant ) {
			$url .= 'google_taxonomy.txt';
		} elseif ( 'facebook' == $this->merchant ) {
			$url .= 'fb_taxonomy.txt';
		} else {
			$url .= 'google_taxonomy.txt';
		}
		$response = file_get_contents( $url );
		if ( $response ) {
			$this->success( $response );
		} else {
			$this->error( sprintf( __( 'No data found with this url:  %s', 'woo-feed' ), $url ) );
		}

		return true;
	}

	/**
	 * @param $request
	 *
	 * @return Boolean
	 */
	public function update_taxonomy() {
		/**
		 * Depending on parameter like merchant and file extension file will be downloaded.
		 * if url doesn't has any resource response will be false.
		 */
		$url      = $this->get_url();
		$response = wp_safe_remote_get( $url );
		if ( '200' == wp_remote_retrieve_response_code( $response ) ) {
			$response_body = wp_remote_retrieve_body( $response );
			$path          = WOO_FEED_FREE_ADMIN_PATH . 'partials/templates/taxonomies/';
			$filepath      = $path . '/' . $this->get_filename_for_save();
			/**
			 *  data will be put on targeted folder.
			 */
			$fp = fopen( $filepath, 'w' );//phpcs:ignore
			fwrite( $fp, $response_body );//phpcs:ignore
			fclose( $fp );//phpcs:ignore
			$this->success( $url );
		} else {
			$this->error( sprintf( __( 'No data found with this url:  %s', 'woo-feed' ), $url ) );
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->get_merchant_url() . $this->get_filename_for_download();
	}

	/**
	 * @return string|void
	 */
	private function get_filename_for_save() {
		$filename = 'google_taxonomy_US.txt';
		if ( 'google' === $this->merchant ) {
			$filename = 'google_taxonomy_' . $this->country_code . '.' . $this->ext;
		} elseif ( 'facebook' === $this->merchant ) {
			$filename = 'facebook_taxonomy_' . $this->country_code . '.' . $this->ext;
		}

		return $filename;
	}

	/**
	 * @return string|void
	 */
	private function get_filename_for_download() {
		$filename     = 'taxonomy.en-US.txt';
		$country_code = $this->country_code;
		$codes        = $this->get_country_codes();
		foreach ( $codes as $code ) {
			if ( false !== strpos( $code, $this->country_code ) ) {
				$country_code = $code;
				break;
			}
		}
		if ( 'google' === $this->merchant ) {
			$filename = ( true == $this->with_id ) ? 'taxonomy-with-ids.' . $country_code . '.' . $this->ext : 'taxonomy.' . $country_code . '.' . $this->ext;
		} elseif ( 'facebook' === $this->merchant ) {
			$country_code = str_replace( '-', '_', $country_code );
			$filename     = $country_code . '.' . $this->ext;
		}

		return $filename;
	}

	/**
	 * taxonomy with ids example: https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt
	 * taxonomy without id example: https://www.google.com/basepages/producttype/taxonomy.en-US.txt
	 * @return mixed
	 */
	private function get_merchant_url() {
		$urls = apply_filters( 'ctxfeed_mechant_ur', [
			'google'   => 'https://www.google.com/basepages/producttype/',
			'facebook' => 'https://www.facebook.com/products/categories/'
		] );

		return $urls[ $this->merchant ];
	}

	/**
	 * @return mixed|null
	 */
	private function get_country_codes() {
		return apply_filters( 'ctxfeed_country_codes', [
			"af",
			"ak",
			"sq",
			"am",
			"ar",
			"hy",
			"rup-MK",
			"as",
			"az",
			"az-TR",
			"ba",
			"eu",
			"bel",
			"bn-BD",
			"bs-BA",
			"bg-BG",
			"my-MM",
			"ca",
			"bal",
			"zh-CN",
			"zh-HK",
			"zh-TW",
			"co",
			"hr",
			"cs-CZ",
			"da-DK",
			"dv",
			"nl-NL",
			"nl-BE",
			"en-US",
			"en-AU",
			"en-CA",
			"en-GB",
			"eo",
			"et",
			"fo",
			"fi",
			"fr-BE",
			"fr-FR",
			"fy",
			"fuc",
			"gl-ES",
			"ka-GE",
			"de-DE",
			"de-CH",
			"el",
			"gn",
			"gu-IN",
			"haw-US",
			"haz",
			"he-IL",
			"hi-IN",
			"hu-HU",
			"is-IS",
			"ido",
			"id-ID",
			"ga",
			"it-IT",
			"ja",
			"jv-ID",
			"kn",
			"kk",
			"km",
			"kin",
			"ky-KY",
			"ko-KR",
			"ckb",
			"lo",
			"lv",
			"li",
			"lin",
			"lt-LT",
			"lb-LU",
			"mk-MK",
			"mg-MG",
			"ms-MY",
			"ml-IN",
			"mr",
			"xmf",
			"mn",
			"me-ME",
			"ne-NP",
			"nb-NO",
			"nn-NO",
			"ory",
			"os",
			"ps",
			"fa-IR",
			"fa-AF",
			"pl-PL",
			"pt-BR",
			"pt-PT",
			"pa-IN",
			"rhg",
			"ro-RO",
			"ru-RU",
			"ru-UA",
			"rue",
			"sah",
			"sa-IN",
			"srd",
			"gd",
			"sr-RS",
			"sd-PK",
			"si-LK",
			"sk-SK",
			"sl-SI",
			"so-SO",
			"azb",
			"es-AR",
			"es-CL",
			"es-CO",
			"es-MX",
			"es-PE",
			"es-PR",
			"es-ES",
			"es-VE",
			"su-ID",
			"sw",
			"sv-SE",
			"gsw",
			"tl",
			"tg",
			"tzm",
			"ta-IN",
			"ta-LK",
			"tt-RU",
			"te",
			"th",
			"bo",
			"tir",
			"tr-TR",
			"tuk",
			"ug-CN",
			"uk",
			"ur",
			"uz-UZ",
			"vi",
			"wa",
			"cy",
			"yor"
		] );
	}
}

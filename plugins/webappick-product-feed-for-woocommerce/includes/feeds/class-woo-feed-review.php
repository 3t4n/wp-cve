<?php /** @noinspection PhpUnusedPrivateMethodInspection, PhpUndefinedMethodInspection, PhpUnused, PhpUnusedPrivateFieldInspection, PhpUnusedLocalVariableInspection, DuplicatedCode, PhpUnusedParameterInspection, PhpForeachNestedOuterKeyValueVariablesConflictInspection, RegExpRedundantEscape */

/**
 * Class Google Product Review
 *
 * Responsible for processing and generating feed for Google.com
 *
 * @since 1.0.0
 * @package Google
 *
 */
class Woo_Feed_Review {

	/**
	 * This variable is responsible for holding all product attributes and their values
	 *
	 * @since   1.0.0
	 * @var     array $products Contains all the product attributes to generate feed
	 * @access  public
	 */
	public $products;

	/**
	 * This variable is responsible for making error number
	 *
	 * @since   1.0.0
	 * @var     int $errorCounter Generate error number
	 * @access  public
	 */
	public $errorCounter;

	/**
	 * Feed Wrapper text for enclosing each product information
	 *
	 * @since   1.0.0
	 * @var     string $feedWrapper Feed Wrapper text
	 * @access  public
	 */
	public $feedWrapper = 'review';

	/**
	 * Store product information
	 *
	 * @since   1.0.0
	 * @var     array $storeProducts
	 * @access  public
	 */
	private $storeProducts;

	/**
	 * Product Ids
	 *
	 * @since   1.0.0
	 * @var     array $ids
	 * @access  public
	 */
	private $ids;

	/**
	 * Review Data
	 *
	 * @since   1.0.0
	 * @var     array $data
	 * @access  public
	 */
	private $data;

	/**
	 * Define the core functionality to generate feed.
	 *
	 * Set the feed rules. Map products according to the rules and Check required attributes
	 * and their values according to merchant specification.
	 * @var Woo_Generate_Feed $feedRule Contain Feed Configuration
	 * @since    1.0.0
	 */
	public function __construct( $feedRule ) {

		$feedRule['itemWrapper'] = $this->feedWrapper;

		$this->products = new Woo_Feed_Products_v3( $feedRule );

		// When update via cron job then set productIds.
		if ( ! isset( $feedRule['productIds'] ) ) {
			$feedRule['productIds'] = $this->products->query_products();
		}

		$products = $this->products->get_products( $feedRule['productIds'] );

		$this->ids = $feedRule['productIds'];

		$this->rules = $feedRule;

		$this->data = $this->processReviewsData( $feedRule );
	}

	/**
	 * Process Reviews Data
	 *
	 * @param mixed $config feed configuration
	 *
	 */
	private function processReviewsData( $config ) {
		$ids                        = $this->ids;
		$feed                       = array();
		$feed['version']            = '2.3';
		$feed['aggregator']['name'] = 'review';
		$feed['publisher']['name']  = 'CTX Feed – WooCommerce Product Feed Generator by Webappick';
		$feed['reviews']            = array();

		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );

			$reviews = get_comments(
				array(
					'post_id'     => $id,
					'status'      => 'approve',
					'post_status' => 'publish',
					'post_type'   => 'product',
					'parent'      => 0
				)
			);

			$review = array();
			$i      = 0;
			if ( $reviews && is_array( $reviews ) ) {
				foreach ( $reviews as $single_review ) {

					$review_content = $single_review->comment_content;
					if ( empty( $review_content ) ) {
						continue;
					}

					$rating = get_comment_meta( $single_review->comment_ID, 'rating', true );
					if ( empty( $rating ) ) {
						continue;
					}

					$review_time = ! empty( $single_review->comment_date_gmt ) ? gmdate( 'c', strtotime( $single_review->comment_date_gmt ) ) : "";


					//Review Content
					//strip tags and spacial characters
					$strip_review_content = woo_feed_strip_all_tags( wp_specialchars_decode( $review_content ) );
					$review_content       = ! empty( strlen( $strip_review_content ) ) && 0 < strlen( $strip_review_content ) ? $strip_review_content : $review_content;

					$review_product_url = ! empty( $product->get_permalink() ) ? $product->get_permalink() : "";

					$review_id      = ! empty( $single_review->comment_ID ) ? $single_review->comment_ID : "";
					$review_author  = ! empty( $single_review->comment_author ) ? $single_review->comment_author : "";
					$review_user_id = ! empty( $single_review->user_id ) ? $single_review->user_id : "";

					$review['review']['review_id']               = $review_id;
					$review['review']['reviewer']['name']        = $review_author;
					$review['review']['reviewer']['reviewer_id'] = $review_user_id;
					$review['review']['content']                 = $review_content;
					$review['review']['review_timestamp']        = $review_time;
					$review['review']['review_url']              = $review_product_url;
					$review['review']['ratings']["overall"]      = $rating;
					$review['review']['products']                = array();
					$review['review']['products']['product']     = array();

					$review['review']['products']['product']['product_name'] = ! empty( $product->get_name() ) ? $product->get_name() : "";
					$review['review']['products']['product']['product_url']  = $review_product_url;

					// Get Product Attribute values by type and assign to product array
					foreach ( $config['attributes'] as $attr_key => $attribute ) {
						$merchant_attribute = isset( $config['mattributes'][ $attr_key ] ) ? $config['mattributes'][ $attr_key ] : '';

						// Add Prefix and Suffix into Output
						$prefix = $config['prefix'][ $attr_key ];
						$suffix = $config['suffix'][ $attr_key ];

						if ( 'pattern' === $config['type'][ $attr_key ] ) {
							$attributeValue = $config['default'][ $attr_key ];
						} else {
							$attributeValue = $this->products->getAttributeValueByType( $product, $attribute, $merchant_attribute );
						}

						//add prefix - suffix to attribute value
						$attributeValue = $prefix . $attributeValue . $suffix;
						$attributeValue = ! empty( $attributeValue ) ? $attributeValue : "";

						if ( "review_temp_gtin" === $merchant_attribute) {
							$review['review']['products']['product']['product_ids']['gtins'] = $this->get_product_ids( $product, $config, $attr_key, $attribute, $merchant_attribute, 'gtin' );
						} elseif ( "review_temp_mpn" === $merchant_attribute) {
							$review['review']['products']['product']['product_ids']['mpns'] = $this->get_product_ids( $product, $config, $attr_key, $attribute, $merchant_attribute, 'mpn' );
						} elseif ( "review_temp_sku" === $merchant_attribute ) {
							$review['review']['products']['product']['product_ids']['skus'] = $this->get_product_ids( $product, $config, $attr_key, $attribute, $merchant_attribute, 'sku' );
						} elseif ( "review_temp_brand" === $merchant_attribute) {
							$review['review']['products']['product']['product_ids']['brands'] = $this->get_product_ids( $product, $config, $attr_key, $attribute, $merchant_attribute, 'brand' );
						}
					}

					$feed['reviews'][] = $review;
					$i ++;
				}
			}

		}

		return $feed;
	}

	/**
	 * Get Product Ids associated with a review (Ex: variations)
	 *
	 * @param $product
	 * @param $config
	 * @param $attr_key
	 * @param $attribute
	 * @param $merchant_attribute
	 * @param $id_type
	 *
	 * @return array
	 */
	public function get_product_ids( $product, $config, $attr_key, $attribute, $merchant_attribute, $id_type ) {
		if ( $product->is_type( 'variable' ) ) {
			$variations = $product->get_children();
			if ( ! empty( $variations ) ) {
				$variation_ids = [];
				foreach ( $variations as $key => $variation ) {
					$variation = wc_get_product( $variation );
					if ( 'pattern' === $config['type'][ $attr_key ] ) {
						$variation_ids[ $key ][ $id_type ] = $config['default'][ $attr_key ];
					} else {
//						echo "<pre>";echo $attribute; die();
						$variation_ids[ $key ][ $id_type ] = $this->products->getAttributeValueByType( $variation, $attribute, $merchant_attribute );
					}
				}

				return $variation_ids;
			}
		}

		// For non variation products
		$attributeValue = "";
		if ( 'pattern' === $config['type'][ $attr_key ] ) {
			$attributeValue = $config['default'][ $attr_key ];
		} else {
			$attributeValue = $this->products->getAttributeValueByType( $product, $attribute, $merchant_attribute );
		}

		return [ $id_type => $attributeValue ];
	}

	/**
	 * Convert an array to XML
	 *
	 * @param array $array array to convert
	 * @param mixed $xml xml object
	 *
	 */
	function woo_feed_array_to_xml( $array, &$xml ) {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( ! is_numeric( $key ) ) {
					$subnode = $xml->addChild( "$key" );
					$this->woo_feed_array_to_xml( $value, $subnode );
				} else {
					$this->woo_feed_array_to_xml( $value, $xml );
				}
			} else {
				if ( "overall" === $key ) {
					$rating = $xml->addChild( $key, "$value" );
					$rating->addAttribute( 'min', '1' );
					$rating->addAttribute( 'max', '5' );
				} elseif ( "review_url" === $key ) {
					$rating = $xml->addChild( $key, "$value" );
					$rating->addAttribute( 'type', 'group' );
				} else {
					$value = htmlspecialchars( $value );
					$xml->addChild( "$key", "$value" );
				}
			}
		}
	}

	/**
	 * Make XML Feed
	 *
	 * @return string
	 */
	public function make_review_xml_feed() {
		// create simpleXML object
		$xml = new SimpleXMLElement( "<?xml version=\"1.0\" encoding=\"utf-8\"?><feed xmlns:vc=\"http://www.w3.org/2007/XMLSchema-versioning\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.google.com/shopping/reviews/schema/product/2.3/product_reviews.xsd\"></feed>" );
		$this->woo_feed_array_to_xml( $this->data, $xml );
		$feedBody = $xml->asXML();

		$data                     = new DOMDocument();
		$data->preserveWhiteSpace = false;
		$data->formatOutput       = true;
		$data->loadXML( $feedBody );
		$feedBody = $data->saveXML();

		return $feedBody;
	}
}

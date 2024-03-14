<?php
/**
 * Class Custom2Structure
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */
namespace CTXFeed\V5\Structure;

use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Utility\Config;

/**
 * Class representing the structure for Custom2.
 * Implements the StructureInterface for Custom2-related operations.
 */

class Custom2Structure implements StructureInterface {

	/**
	 * Configuration settings.
	 *
	 * @var Config $config
	 */
	private $config;

	/**
	 * @var false|int|string
	 */
	private $for_sub_loop;
	/**
	 * @var int
	 */
	private $variation_elements_start;

	/**
	 * Constructor for Custom2Structure.
	 *
	 * @param mixed $config Configuration settings.
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}

	/**
	 * Retrieves the XML structure.
	 *
	 * @return array The constructed XML data structure.
	 */
	public function get_xml_structure() {
		$xml = \trim( \preg_replace( '/\+/', '', $this->config->feed_config_custom2 ) );

		// Get XML nodes for each product
		$get_feed_body = FeedHelper::get_string_between( $xml, '{{each product start}}', '{{each product end}}' );
		// Explode each element by new line
		$get_elements = \explode( "\n", $get_feed_body );

		$elements = array();
		$i        = 1;

		$sub_loops_start = [
			'ifVariationAvailable' => '{{if variation available}}',
			'variation'            => '{{each variation start}}',
			'images'               => '{{each image start}}',
			'shipping'             => '{{each shipping start}}',
			'tax'                  => '{{each tax start}}',
			'categories'           => '{{each category start}}',
			'crossSale'            => '{{each crossSale start}}',
			'upSale'               => '{{each upSale start}}',
			'relatedProducts'      => '{{each relatedProducts start}}',
			'associatedProduct'    => '{{each associatedProduct start}}'
		];

		$sub_loops_end = [
			'ifVariationAvailableEnd' => '{{endif variation}}',
			'variationEnd'            => '{{each variation end}}',
			'imagesEnd'               => '{{each image end}}',
			'shippingEnd'             => '{{each shipping end}}',
			'taxEnd'                  => '{{each tax end}}',
			'categoryEnd'             => '{{each category end}}',
			'crossSaleEnd'            => '{{each crossSale end}}',
			'upSaleEnd'               => '{{each upSale end}}',
			'relatedProductsEnd'      => '{{each relatedProducts end}}',
			'associatedProductEnd'    => '{{each associatedProduct end}}'
		];

		if ( ! empty( $get_elements ) ) {
			foreach ( $get_elements as $value ) {
				if ( ! empty( $value ) ) {

					if ( \in_array( \trim( $value ), $sub_loops_start ) ) {
						$this->for_sub_loop = \array_search( \trim( $value ), $sub_loops_start, false );
						if ( $this->for_sub_loop === 'variation' ) {
							$this->variation_elements_start = $i;
						}
						continue;
					}

					if ( \in_array( \trim( $value ), $sub_loops_end ) ) {
						$loop_key = \array_search( \trim( $value ), $sub_loops_end, false );
						if ( $loop_key === 'ifVariationAvailableEnd' ) {
							$elements[ $i - 1 ]['for'] = 'ifVariationAvailable';
						}
						$this->for_sub_loop = "";
						continue;
					}

					// Get Element info
					$element = FeedHelper::get_string_between( $value, '<', '>' );

					if ( empty( $element ) ) {
						continue;
					}

					// Set Element for
					$elements[ $i ]['for'] = $this->for_sub_loop;

					// Get starting element
					$elements[ $i ]['start'] = $this->remove_quotation( $element );
					// Get ending element
					$elements[ $i ]['end'] = FeedHelper::get_string_between( $value, '</', '>' );

					// Set CDATA status and remove CDATA
					$element_text_info                 = FeedHelper::get_string_between( $value, '>', '</' );
					$elements[ $i ]['include_cdata'] = 'no';
					if ( \stripos( $element_text_info, 'CDATA' ) !== false ) {
						$elements[ $i ]['include_cdata'] = 'yes';
						$element_text_info                 = $this->remove_CDATA( $element_text_info );
					}
					// Get Pattern of the xml node
					$elements[ $i ]['elementTextInfo'] = $element_text_info;

					if ( ! empty( $elements[ $i ]['elementTextInfo'] ) ) {
						// Get type of the attribute pattern
						if ( \strpos( $element_text_info, '{' ) === false && \strpos( $element_text_info, '}' ) === false ) {
							$elements[ $i ]['attr_type']  = 'text';
							$elements[ $i ]['attr_value'] = $element_text_info;
						} elseif ( \strpos( $element_text_info, 'return' ) !== false ) {
							$elements[ $i ]['attr_type'] = 'return';
							$return                      = FeedHelper::get_string_between( $element_text_info, '{(', ')}' );
							$elements[ $i ]['to_return'] = $return;
						} elseif ( \strpos( $element_text_info, 'php ' ) !== false ) {
							$elements[ $i ]['attr_type'] = 'php';
							$php                         = FeedHelper::get_string_between( $element_text_info, '{(', ')}' );
							$elements[ $i ]['to_return'] = \str_replace( 'php', '', $php );
						} else {
							$elements[ $i ]['attr_type'] = 'attribute';
							$attribute                   = FeedHelper::get_string_between( $element_text_info, '{', '}' );
							$get_attr_base_format        = \explode( ',', $attribute );

							$attr_info = $get_attr_base_format[0];
							if ( \count( $get_attr_base_format ) > 1 ) {
								$j = 0;
								foreach ( $get_attr_base_format as $_value ) {
									if ( $value !== "" ) {
										$formatters = FeedHelper::get_string_between( $_value, '[', ']' );
										if ( ! empty( $formatters ) ) {
											$elements[ $i ]['formatter'][ $j ] = $formatters;
											$j ++;
										}
									}
								}
							}

							$get_attr_codes                = \explode( '|', $attr_info );
							$elements[ $i ]['attr_code'] = $get_attr_codes[0];
							$elements[ $i ]['id_type']   = isset( $get_attr_codes[1] ) ? $get_attr_codes[1] : '';
						}

						// Get prefix of the attribute node value
						$elements[ $i ]['prefix'] = '';
						if ( 'text' !== $elements[ $i ]['attr_type'] && \strpos( \trim( $element_text_info ), '{' ) !== 0 ) {
							$get_prefix                 = \explode( '{', $element_text_info );
							$elements[ $i ]['prefix']   = ( \count( $get_prefix ) > 1 ) ? $get_prefix[0] : '';
						}
						// Get suffix of the attribute node value
						$elements[ $i ]['suffix'] = '';
						if ( 'text' != $elements[ $i ]['attr_type'] && \strpos( \trim( $element_text_info ), '}' ) !== 0 ) {
							$get_suffix                 = \explode( '}', $element_text_info );
							$elements[ $i ]['suffix']   = ( \count( $get_suffix ) > 1 ) ? $get_suffix[1] : '';
						}
					}

					\preg_match_all( '/{(.*?)}/', $element, $matches );
					$start_codes                    = ( isset( $matches[0] ) ? $matches[0] : '' );
					$elements[ $i ]['start_code']   = \array_filter( $start_codes );
					$i ++;
				}
			}
		}

		return [
			'variationElementsStart' => $this->variation_elements_start,
			'structure'              => $elements
		];
	}

	/** Remove CDATA from String
	 *
	 * @param string $output
	 *
	 * @return string
	 */
	private function remove_CDATA( $output ) {
		return \str_replace( [ "<![CDATA[", "]]>" ], "", $output );
	}

	/**
	 * If provided static attribute titler than no need to Remove Quotation mark else Remove Quotation mark from xml element.
	 *
	 * @return string
	 */
	private function remove_quotation( $string ) {
		$static_attribute_title = '/="[a-zA-Z0-9 ]+"/';
		if ( preg_match( $static_attribute_title, $string ) ) {
			return $string;
		}else{
			return wp_unslash( str_replace( array( "'", "\"", "&quot;" ), "", $string ) );
		}

	}

	public function get_csv_structure() {
		// TODO: Implement getCSVStructure() method.
	}

	public function get_tsv_structure() {
		// TODO: Implement getTSVStructure() method.
	}

	public function get_txt_structure() {
		// TODO: Implement getTXTStructure() method.
	}

	public function get_xls_structure() {
		// TODO: Implement getXLSStructure() method.
	}

	public function get_json_structure() {
		// TODO: Implement getJSONStructure() method.
	}

}

<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The main class for getting the XML-code of the product
 *
 * @package			XML for Google Merchant Center
 * @subpackage		
 * @since			1.0.0
 * 
 * @version			1.0.0 (07-02-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @see				
 * 
 * @param	string 	$post_id (require)
 * @param	string 	$feed_id (require)
 *
 * @return 	string	$result_xml
 * @return 	string	$ids_in_xml
 * @return 	array	$skip_reasons_arr		
 *
 * @depends			class:		WC_Product_Variation
 *								XFGMC_Get_Unit_Offer
 *								(XFGMC_Get_Unit_Offer_Simple)
 *								(XFGMC_Get_Unit_Offer_Varible)
 *					traits:		XFGMC_Trait_Get_Post_Id
 *								XFGMC_Trait_Get_Feed_Id;
 *								XFGMC_Trait_Get_Product
 *								XFGMC_Trait_Get_Skip_Reasons_Arr
 *					methods:	
 *					functions:	common_option_get
 *					constants:	XFGMC_PLUGIN_VERSION
 *					options:	
 *
 */

class XFGMC_Get_Unit {
	use XFGMC_Trait_Get_Post_Id;
	use XFGMC_Trait_Get_Feed_Id;
	use XFGMC_Trait_Get_Product;
	use XFGMC_Trait_Get_Skip_Reasons_Arr;

	protected $result_xml;
	protected $ids_in_xml = '';

	public function __construct( $post_id, $feed_id ) {
		$this->post_id = $post_id;
		$this->feed_id = $feed_id;

		$args_arr = [ 
			'post_id' => $post_id,
			'feed_id' => $feed_id
		];

		do_action( 'before_wc_get_product', $args_arr );

		$product = wc_get_product( $post_id );

		do_action( 'after_wc_get_product', $args_arr, $product );
		$this->product = $product;
		do_action( 'after_wc_get_product_this_product', $args_arr, $product );

		$this->create_code(); // создаём код одного простого или вариативного товара и заносим в $result_xml
	}

	public function get_result() {
		return $this->result_xml;
	}

	public function get_ids_in_xml() {
		return $this->ids_in_xml;
	}

	protected function create_code() {
		$product = $this->get_product();
		$feed_id = $this->get_feed_id();
		$post_id = $this->get_post_id();

		if ( $product == null ) {
			$this->result_xml = '';
			array_push( $this->skip_reasons_arr, __( 'There is no product with this ID', 'xml-for-google-merchant-center' ) );
			return $this->get_result();
		}

		if ( $product->is_type( 'variable' ) ) {
			$variations_arr = $product->get_available_variations();
			$variation_count = count( $variations_arr );
			for ( $i = 0; $i < $variation_count; $i++ ) {
				$offer_id = $variations_arr[ $i ]['variation_id'];
				$offer = new WC_Product_Variation( $offer_id ); // получим вариацию

				$args_arr = [ 
					'feed_id' => $feed_id,
					'product' => $product,
					'offer' => $offer,
					'variation_count' => $variation_count
				];

				$offer_variable_obj = new XFGMC_Get_Unit_Offer_Variable( $args_arr );
				$r = $this->set_result( $offer_variable_obj );
				if ( true === $r ) {
					$this->ids_in_xml .= $product->get_id() . ';' . $offer->get_id() . ';' . $offer_variable_obj->get_feed_price() . ';' . $offer_variable_obj->get_feed_category_id() . PHP_EOL; /* с версии 3.1.0 */
					$one_variable = xfgmc_optionGET( 'xfgmc_one_variable', $this->feed_id, 'set_arr' );
					if ( $one_variable == 'on' ) {
						break;
					}
				}
			}
		} else {
			$args_arr = [ 
				'feed_id' => $feed_id,
				'product' => $product
			];
			$offer_simple_obj = new XFGMC_Get_Unit_Offer_Simple( $args_arr );
			$r = $this->set_result( $offer_simple_obj );
			if ( true === $r ) {
				$this->ids_in_xml .= $product->get_id() . ';' . $product->get_id() . ';' . $offer_simple_obj->get_feed_price() . ';' . $offer_simple_obj->get_feed_category_id() . PHP_EOL; /* с версии 3.1.0 */
			}
		}

		return $this->get_result();
	}

	// ожидается потомок класса XFGMC_Get_Unit_Offer
	protected function set_result( XFGMC_Get_Unit_Offer $offer_obj ) {
		if ( ! empty( $offer_obj->get_skip_reasons_arr() ) ) {
			foreach ( $offer_obj->get_skip_reasons_arr() as $value ) {
				array_push( $this->skip_reasons_arr, $value );
			}
		}
		if ( true === $offer_obj->get_do_empty_product_xml() ) {
			$this->result_xml = '';
			return false;
		} else { // если нет причин пропускать товар
			$this->result_xml .= $offer_obj->get_product_xml();
			return true;
		}
	}
}
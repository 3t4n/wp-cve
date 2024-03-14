<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The abstract class for getting the XML-code or skip reasons
 *
 * @author		Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @since		1.0.0
 *
 * @param string $post_id (require)
 * @param string $feed_id (require)
 *
 * @return 		$result_xml (string)
 * @return 		$ids_in_xml (string)
 * @return 		$skip_reasons_arr (array)
 *
 * @depends		class:	XFGMC_Error_Log
 *				traits:	XFGMC_Trait_Get_Post_Id
 *						XFGMC_Trait_Get_Feed_Id;
 *						XFGMC_Trait_Get_Product
 *						XFGMC_Trait_Get_Skip_Reasons_Arr
 */

abstract class XFGMC_Get_Unit_Offer {
	//	use XFGMC_Trait_Get_Post_Id;
	use XFGMC_Trait_Get_Feed_Id;
	use XFGMC_Trait_Get_Product;
	use XFGMC_Trait_Get_Skip_Reasons_Arr;

	public $feed_category_id;
	public $feed_price;

	protected $input_data_arr; // массив, который пришёл в класс. Этот массив используется в фильтрах трейтов

	protected $offer = null;
	protected $variations_arr = null;

	protected $result_product_xml;
	protected $do_empty_product_xml = false;

	/*
	 * $args_arr [
	 *	'feed_id' 			- string (require)
	 *	'product' 			- object (require)
	 *	'offer' 			- object (not require)
	 *	'variation_count' 	- int (not require)
	 * ]
	 */
	public function __construct( $args_arr ) {
		// без этого не будет работать вне адмники is_plugin_active
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->input_data_arr = $args_arr;
		$this->feed_id = $args_arr['feed_id'];
		$this->product = $args_arr['product'];

		if ( isset( $args_arr['offer'] ) ) {
			$this->offer = $args_arr['offer'];
		}
		if ( isset( $args_arr['variation_count'] ) ) {
			$this->variation_count = $args_arr['variation_count'];
		}

		$r = $this->generation_product_xml();

		// если нет нужды пропускать
		if ( empty( $this->get_skip_reasons_arr() ) ) {
			$this->result_product_xml = $r;
		} else {
			// !!! - тут нужно ещё раз подумать и проверить
			// с простыми товарами всё чётко
			$this->result_product_xml = '';
			if ( null == $this->get_offer() ) { // если прстой товар - всё чётко
				$this->set_do_empty_product_xml( true );
			} else {
				// если у нас вариативный товар, то как быть, если все вариации пропущены
				// мы то возвращаем false (см ниже), возможно надо ещё вести учёт вариций
				// также см функцию set_result() в классе class-xfgmc-get-unit.php
				$this->set_do_empty_product_xml( false );
			}
		}
	}

	abstract public function generation_product_xml();


	public function get_product_xml() {
		return $this->result_product_xml;
	}

	public function set_do_empty_product_xml( $v ) {
		$this->do_empty_product_xml = $v;
	}

	public function get_do_empty_product_xml() {
		return $this->do_empty_product_xml;
	}

	public function get_feed_category_id() {
		return $this->feed_category_id;
	}

	public function get_feed_price() {
		return $this->feed_price;
	}

	protected function add_skip_reason( $reason ) {
		if ( isset( $reason['offer_id'] ) ) {
			$reason_string = sprintf(
				'FEED № %1$s; Вариация товара (postId = %2$s, offer_id = %3$s) пропущена. Причина: %4$s; Файл: %5$s; Строка: %6$s',
				$this->feed_id, $reason['post_id'], $reason['offer_id'], $reason['reason'], $reason['file'], $reason['line']
			);
		} else {
			$reason_string = sprintf(
				'FEED № %1$s; Товар с postId = %2$s пропущен. Причина: %3$s; Файл: %4$s; Строка: %5$s',
				$this->feed_id, $reason['post_id'], $reason['reason'], $reason['file'], $reason['line']
			);
		}
		$this->set_skip_reasons_arr( $reason_string );
		new XFGMC_Error_Log( $reason_string );
	}

	protected function get_input_data_arr() {
		return $this->input_data_arr;
	}

	protected function get_offer() {
		return $this->offer;
	}
}
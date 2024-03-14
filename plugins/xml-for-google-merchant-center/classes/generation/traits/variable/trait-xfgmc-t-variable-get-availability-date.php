<?php
/**
 * Traits Availability_Date for variable products
 *
 * @package                 XML for Google Merchant Center
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 3.0.7 (08-09-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 *
 * @depends                 classes:    Get_Paired_Tag
 *                          traits:     
 *                          methods:    get_product
 *                                      get_feed_id
 *                          functions:  common_option_get
 *                          constants:  
 */
defined( 'ABSPATH' ) || exit;

trait XFGMC_T_Variable_Get_Availability_Date {
	/**
	 * Summary of get_availability_date
	 * 
	 * @param string $tag_name
	 * @param string $result_xml 
	 * 
	 * @return string
	 */
	public function get_availability_date( $tag_name = 'g:availability_date', $result_xml = '' ) {
		$tag_value = '';
		$availability_date = xfgmc_optionGET( 'xfgmc_availability_date', $this->get_feed_id(), 'set_arr' );

		if (!empty($availability_date)) {
			$tag_value = $availability_date;
		}
		$tag_value = apply_filters(
			'x4gmc_f_variable_tag_value_availability_date',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'x4gmc_f_variable_tag_name_availability_date',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer()
				],
				$this->get_feed_id()
			);
			$result_xml = new Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'x4gmc_f_variable_tag_availability_date',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		return $result_xml;
	}
}
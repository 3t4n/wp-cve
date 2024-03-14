<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Get unit for Variable Products 
 *
 * @link			https://icopydoc.ru/
 * @since		1.0.0
 */

class XFGMC_Get_Unit_Offer_Variable extends XFGMC_Get_Unit_Offer {
	use XFGMC_Trait_Get_Cat_Id;

	use XFGMC_T_Variable_Get_Availability_Date;

	use XFGMC_Trait_Variable_Get_Id;
	use XFGMC_Trait_Variable_Group_Id;
	use XFGMC_Trait_Variable_Get_Name;
	use XFGMC_Trait_Variable_Get_Description;
	use XFGMC_Trait_Variable_Get_Google_Product_Category;
	use XFGMC_Trait_Simple_Get_Fb_Product_Category;
	use XFGMC_Trait_Variable_Get_Tax_Category;
	use XFGMC_Trait_Variable_Get_Product_Type;
	use XFGMC_Trait_Variable_Get_Link;
	use XFGMC_Trait_Variable_Get_Image_Link;
	use XFGMC_Trait_Variable_Get_Quantity;
	use XFGMC_Trait_Variable_Get_Availability;
	use XFGMC_Trait_Variable_Get_Identifier_Exists;
	use XFGMC_Trait_Variable_Get_Adult;
	use XFGMC_Trait_Variable_Get_Is_Bundle;
	use XFGMC_Trait_Variable_Get_Multipack;
	use XFGMC_Trait_Variable_Get_Condition;
	use XFGMC_Trait_Variable_Get_Custom_Label;
	use XFGMC_Trait_Variable_Get_Price;
	use XFGMC_Trait_Variable_Get_Pricing_Measure;
	use XFGMC_Trait_Variable_Get_Base_Measure;
	use XFGMC_Trait_Variable_Get_Return_Rule_Label;
	use XFGMC_Trait_Variable_Get_Store_Code;

	use XFGMC_Trait_Variable_Get_Gtin;
	use XFGMC_Trait_Variable_Get_Mpn;
	use XFGMC_Trait_Variable_Get_Age_Group;
	use XFGMC_Trait_Variable_Get_Brand;
	use XFGMC_Trait_Variable_Get_Color;
	use XFGMC_Trait_Variable_Get_Material;
	use XFGMC_Trait_Variable_Get_Pattern;
	use XFGMC_Trait_Variable_Get_Gender;

	use XFGMC_Trait_Variable_Get_Size;
	use XFGMC_Trait_Variable_Get_Size_Type;
	use XFGMC_Trait_Variable_Get_Size_System;

	use XFGMC_Trait_Variable_Get_Shipping_Xml;
	use XFGMC_Trait_Get_USA_Tax_Info;

	use XFGMC_Trait_Skips;

	public function generation_product_xml() {
		$this->get_skips();
		$result_xml = '<item>' . PHP_EOL;

		$result_xml .= $this->get_id();
		$result_xml .= $this->get_group_id();
		$result_xml .= $this->get_name();
		$result_xml .= $this->get_description();
		$result_xml .= $this->get_google_product_category();
		$result_xml .= $this->get_fb_product_category();


		$result_xml .= $this->get_tax_category();
		$result_xml .= $this->get_product_type();

		$result_url = $this->get_link();
		$result_xml .= $result_url;

		$result_xml .= $this->get_image_link();
		$result_xml .= $this->get_quantity();
		$result_xml .= $this->get_availability();
		$result_xml .= $this->get_identifier_exists(); // вызывает $this->get_gtin() и $this->get_mpn()
		// $result_xml .= $this->get_gtin();
		// $result_xml .= $this->get_mpn();

		$result_xml .= $this->get_adult();
		$result_xml .= $this->get_is_bundle();
		$result_xml .= $this->get_multipack();
		$result_xml .= $this->get_condition();
		$result_xml .= $this->get_custom_label();

		if ( class_exists( 'WOOCS' ) ) {
			$xfgmc_wooc_currencies = xfgmc_optionGET( 'xfgmc_wooc_currencies', $this->get_feed_id(), 'set_arr' );
			if ( $xfgmc_wooc_currencies !== '' ) {
				global $WOOCS;
				$WOOCS->set_currency( $xfgmc_wooc_currencies );
			}
		}
		$result_xml .= $this->get_price();
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			$WOOCS->reset_currency();
		}

		$result_xml .= $this->get_unit_pricing_measure();
		$result_xml .= $this->get_unit_pricing_base_measure();

		$result_xml .= $this->get_store_code();
		$result_xml .= $this->get_return_rule_label();

		$result_xml .= $this->get_age_group();
		$result_xml .= $this->get_brand();
		$result_xml .= $this->get_color();
		$result_xml .= $this->get_material();
		$result_xml .= $this->get_pattern();
		$result_xml .= $this->get_gender();

		$result_xml .= $this->get_size();
		$result_xml .= $this->get_size_type();
		$result_xml .= $this->get_size_system();

		$result_xml .= $this->get_shipping_xml();
		$result_xml .= $this->get_usa_tax_info();

		/*	$result_xml .= $this->get_shipping_label();
			   $result_xml .= $this->get_shipping_xml();
			   $result_xml .= $this->get_shipping_weight();
			   $result_xml .= $this->get_shipping_length();
			   $result_xml .= $this->get_shipping_width();
			   $result_xml .= $this->get_shipping_height();

			   $result_xml .= $this->get_min_handling_time();
			   $result_xml .= $this->get_max_handling_time(); 
	   */
		do_action( 'xfgmc_append_variable_offer', $this->feed_id );
		$result_xml = apply_filters(
			'xfgmc_append_variable_offer_filter',
			$result_xml,
			$this->product,
			$this->offer,
			$result_url,
			$this->feed_id
		); /* с версии 2.2.1 добавлен $result_url */

		$result_xml .= '</item>' . PHP_EOL;
		return $result_xml;
	}
}